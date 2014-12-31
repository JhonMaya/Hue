<?php exit() ?>--by UglyOldGuy 86.127.152.238
--Encrypt this line and below
-- Auto Updater info
local UPDATE_HOST = "bitbucket.org"
local UPDATE_PATH = "/andreluis034/andrerepo/raw/master/extrahud.lua?rand="..math.random(1,10000) -- "Disable" caching to prevent older versions from being retrieved
local UPDATE_FILE_PATH = SCRIPT_PATH.."ExtraHud.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH
local ServerData

local autoDisabledPings    = false
local autoDisabledSS	   = false
local heroVisibleTimeout   = 500   -- mimimum time in ms that a hero has to be invisible to be pinged,a
local heroPingTimeout      = 10000 -- minimum time in ms until the same hero can be pinged again
local pingTimeout          = 3000  -- minimum time in ms between separate pings
local heroChaseRange       = 600   -- how far a champ has to be from opponents to be considered roaming
local heroVisibleThreshold = 100   -- minimum time in ms a hero has to be visible again to get pinged
local enemyTable = {}

local lastPing     = nil
local EnergyChamps	= { "Akali",		"Kennen",	"LeeSin",		"Shen",	"Zed" }
local FuryChamps	= { "Renekton",		"Shyvana",	"Tryndamere",	"Aatrox"	  } -- Aatrox not really Fury but it is red so wtvr
local OtherChamps	= { "Mordekaiser",	"Yasuo"									  } -- To be added
local HeatChamps	= { "Rumble"												  } -- Fuck you too rito
local SSpells 		= { {Name="SummonerFlash",          sprite = nil},
						{Name="SummonerHaste",          sprite = nil},
						{Name="SummonerDot",            sprite = nil},
						{Name="SummonerBarrier",        sprite = nil},
						{Name="SummonerSmite",          sprite = nil},
						{Name="SummonerExhaust",        sprite = nil},
						{Name="SummonerHeal",           sprite = nil},
						{Name="SummonerTeleport",       sprite = nil},
						{Name="SummonerBoost",          sprite = nil},
						{Name="SummonerMana",           sprite = nil},
						{Name="SummonerClairvoyance",   sprite = nil},
						{Name="SummonerRevive",         sprite = nil},
						{Name="SummonerOdinGarrison",   sprite = nil},
						}
local lanesCenter 	= { {lane = "top", x=1437,y=myHero.y,z=12708},
					 	{lane = "mid", x=6985,y=myHero.y,z=7186},
					 	{lane = "bot", x=12367,y=myHero.y,z=1963},
					 	}
local stateQ,stateW,stateE,stateR,stateS1,stateS2 = {},{},{},{},{},{}

local TickLimit = 0
local initDone = false
local x = 0
local y = 0
local FirstTick = false
local lastPing	 = nil
local defaultscalevalue = {x = WINDOW_W * (1/1600), y = WINDOW_H * (1/900)}
--print(defaultscalevalue.x)
local scalevalue = {x=0, y=0}
local diff={x=nil, y=nil}
local UI={x=1502*defaultscalevalue.x, y=114*defaultscalevalue.y}
local move = false
local isMousePressed = false
local alreadyWarnedSprite=false

--Main functions

function OnLoad()
	print("<font color=\"#00FF00\">".."Extra Hud "..tostring(_G.ExtraHudVersion).." Loaded".."</font>")
	menu()
	init()
	CheckUpdates()
end

function OnTick()
	if initDone then
		if os.clock() - TickLimit > 1 and initDone then
			TickLimit = os.clock()
			--print("x: ", GetCursorPos().x)
			--print("y: ", GetCursorPos().y)
			--print(myHero.minionKill)
			for i=1, heroManager.iCount, 1 do
				local enemy = heroManager:getHero(i)
				if enemy.team ~= myHero.team then
					if enemy.visible then
						enemyTable[i].lane = GetLane(enemy)
					end
					TrackCds(i, enemy)
					SSTimer(i, enemy)
					DeathTimer(i, enemy)
					AutoSS(i, enemy)
					AutoPingGank(i, enemy)
				end
			end
			AutoDisable()
			FirstTick = true
		end
		OAP()
		MovableUI()
		SetScaleHUD()
	end
end

function OnDraw()
	if FirstTick then
		local HUDpos={x=UI.x, y=UI.y}
		local BGHUDpos={x=UI.x-15*scalevalue.x, y=UI.y-16*scalevalue.y}
		HUD:SetScale(scalevalue.x, scalevalue.y)
		HUDBG:SetScale(scalevalue.x, scalevalue.y)
		HUDBG:Draw(BGHUDpos.x,BGHUDpos.y, 0xFF)
		for i=1, #SSpells, 1 do
			local spell = SSpells[i]
			--spell.sprite = FindSprite(PATH..spell.Name..".png")
			spell.sprite:SetScale(0.29*scalevalue.x, 0.36*scalevalue.y)
		end

		for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team then
				enemyTable[i].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)
				IconDraw(i, enemy)
				HPandMANAbars(i, enemy)
				SSandDEATHtimers(i, enemy)
				CDtrackerUI(i, enemy)
			end
		end
		HUD:Draw(HUDpos.x,HUDpos.y, 0xFF)
	end
end

function OnWndMsg(msg,keycode) 
	if msg == 513 then
		isMousePressed = true
	elseif msg == 514 then
		isMousePressed = false
	end
end

--"Invisible functions"
function TrackCds(i, hero)
	local QREADY = hero:CanUseSpell(_Q) == (hero.isMe and READY or 3)
	local WREADY = hero:CanUseSpell(_W) == (hero.isMe and READY or 3)
	local EREADY = hero:CanUseSpell(_E) == (hero.isMe and READY or 3)
	local RREADY = hero:CanUseSpell(_R) == (hero.isMe and READY or 3)
	local S1READY = hero:CanUseSpell(SUMMONER_1) == (hero.isMe and READY or 3)
	local S2READY = hero:CanUseSpell(SUMMONER_2) == (hero.isMe and READY or 3)
	local QCD = hero:GetSpellData(_Q).currentCd
	local WCD = hero:GetSpellData(_W).currentCd
	local ECD = hero:GetSpellData(_E).currentCd
	local RCD = hero:GetSpellData(_R).currentCd
	local S1CD = hero:GetSpellData(SUMMONER_1).currentCd
	local S2CD = hero:GetSpellData(SUMMONER_2).currentCd
	local QMAXCD = hero:GetSpellData(_Q).cd
	local WMAXCD = hero:GetSpellData(_W).cd
	local EMAXCD = hero:GetSpellData(_E).cd
	local RMAXCD = hero:GetSpellData(_R).cd
	local S1MAXCD = hero:GetSpellData(SUMMONER_1).cd
	local S2MAXCD = hero:GetSpellData(SUMMONER_2).cd
	enemyTable[i].CurrentHP = hero.health
	enemyTable[i].MaxHP = hero.maxHealth
	enemyTable[i].CurrentMP = hero.mana
	enemyTable[i].MaxMP = hero.maxMana

	if hero:CanUseSpell(_Q) == NOTLEARNED then
		stateQ[i] = {cd = -1}
	elseif QCD == 0 then
		stateQ[i] = {cd = 0, maxCD = string.format("%.0f", math.floor(QCD) > QMAXCD and math.floor(QCD) or QMAXCD)}
	else
		stateQ[i] = {cd = string.format("%.0f", QCD), maxCD = string.format("%.0f", math.floor(QCD) > QMAXCD and math.floor(QCD) or QMAXCD)}
	end
	if hero:CanUseSpell(_W) == NOTLEARNED then
		stateW[i] = {cd = -1}
	elseif WCD == 0 then
		stateW[i] = {cd = 0, maxCD = string.format("%.0f", math.floor(WCD) > WMAXCD and math.floor(WCD) or WMAXCD)}
	else
		stateW[i] = {cd = string.format("%.0f", WCD), maxCD = string.format("%.0f", math.floor(WCD) > WMAXCD and math.floor(WCD) or WMAXCD)}
	end

	if hero:CanUseSpell(_E) == NOTLEARNED then
		stateE[i] = {cd = -1}
	elseif ECD == 0 then
		stateE[i] = {cd = 0, maxCD = string.format("%.0f", math.floor(ECD) > EMAXCD and math.floor(ECD) or EMAXCD)}
	else
		stateE[i] = {cd = string.format("%.0f", ECD), maxCD = string.format("%.0f", math.floor(ECD) > EMAXCD and math.floor(ECD) or EMAXCD)}
	end

	if hero:CanUseSpell(_R) == NOTLEARNED then
		stateR[i] = {cd = -1}
	elseif RCD == 0 then
		stateR[i] = {cd = 0, maxCD = string.format("%.0f", math.floor(RCD) > RMAXCD and math.floor(RCD) or RMAXCD)}
	else
		stateR[i] = {cd = string.format("%.0f", RCD), maxCD = string.format("%.0f", math.floor(RCD) > RMAXCD and math.floor(RCD) or RMAXCD)}
	end

	if S1CD == 0 then
		stateS1[i] = {cd = 0, maxCD = string.format("%.0f", S1MAXCD)}
	else
		stateS1[i] = {cd = string.format("%.0f", S1CD), maxCD = string.format("%.0f", S1MAXCD)}
	end

	if S2CD == 0 then
		stateS2[i] = {cd = 0, maxCD = string.format("%.0f", S2MAXCD)}
	else
		stateS2[i] = {cd = string.format("%.0f", S2CD), maxCD = string.format("%.0f", S2MAXCD)}
	end
end

function SSTimer(i, hero)
	if not hero.visible and not hero.dead then
		if enemyTable[i].tick_hero == nil then
			enemyTable[i].tick_hero = GetTickCount()
		end
		enemyTable[i].MissTimer = GetTickCount() - enemyTable[i].tick_hero
	else
		enemyTable[i].tick_hero = nil
		enemyTable[i].MissTimer = nil
	end
end

function DeathTimer(i, hero)
	if hero.dead then
		if enemyTable[i].tick_deathTimer == nil then
			enemyTable[i].tick_deathTimer = GetTickCount()
		end
		enemyTable[i].deathTimer = math.ceil(hero.deathTimer - ((GetTickCount() - enemyTable[i].tick_deathTimer)*0.001))
	else
		enemyTable[i].tick_deathTimer = nil
	end
end

function GetLane(unit)
	if not unit then return nil end

	local Position = nil

	if MapPosition:onTopLane(unit) then
		Position = "top"
	elseif MapPosition:onMidLane(unit) then
		Position = "mid"
	elseif MapPosition:onBotLane(unit) then
		Position = "bot"
	elseif MapPosition:inOuterJungle(unit) then
		Position = "Outer Jungle"
	elseif MapPosition:inInnerJungle(unit) then
		Position = "Inner Jungle"
	elseif MapPosition:inOuterRiver(unit) then
		Position = "Outer River"
	elseif MapPosition:inInnerRiver(unit) then
		Position = "Inner River"
	elseif MapPosition:inLeftBase(unit) then
		Position = "Blue Base"
	elseif MapPosition:inRightBase(unit) then
		Position = "Purple Base"
	end

	if Position ~= nil then
		return Position
	else
		return nil
	end
end

function FindSprite(file)
	assert(type(file) == "string", "GetSprite: wrong argument types (<string> expected for file)")
	if FileExist(file) == true then
		--print("Sprite available")
		return createSprite(file)
	else
		if not alreadyWarnedSprite then
			PrintChat("<font color=\"#00FF00\">".."Please Download latest sprites from thread")
			alreadyWarnedSprite = true
		end

		return createSprite("empty.dds")
	end
end

function ValueExists(tbl, value)
 	if tbl[value] then
		return true
	else
		return false
	end
end

function AutoSS(i, hero) 
	local fasterTable = enemyTable
	if Menu.missing.enabled and fasterTable[i].MissTimer ~= nil and fasterTable[i].MissTimer >= Menu.missing.missingtime*1000 and fasterTable[i].MissTimer < Menu.missing.missingtime*1000 + 1000 and GetInGameTimer() >= 120 then
		if Menu.missing.pinglaneonly and fasterTable[i].lane ~= nil and fasterTable[i].lane == GetLane(myHero) then
			if Menu.missing.pingtolaneorchamp == 2 then
				PingSS(hero.x, hero.y, hero.z)
			else
				local lane = fasterTable[i].lane
				if (lane == "top" or lane == "mid" or lane == "bot") then
					local laneCenter = lanesCenter
					for i = 1, #laneCenter, 1 do
						local v = laneCenter[i]
						if v.lane == lane then
							PingSS(v.x,v.y,v.z)
						end
					end
				end
			end
		elseif not Menu.missing.pinglaneonly then
			if Menu.missing.pingtolaneorchamp == 2 then
				PingSS(hero.x, hero.y, hero.z)
			else
				local lane = enemyTable[hero.charName].lane
				if (lane == "top" or lane == "mid" or lane == "bot") then
					local laneCenter = lanesCenter
					for i = 1, #laneCenter, 1 do
						local v = laneCenter[i]
						if v.lane == lane then
							PingSS(v.x,v.y,v.z)
						end
					end
				end
			end
		end
	end
end

function AutoPingGank(i, hero)
	if Menu.awerness.enabled then
		if enemyTable[i].plannedPing then
			enemyTable[i].heroVisible = GetTickCount()
			if hero ~= nil and hero.visible and hero.team ~= myHero.team then
				if GetTickCount() - enemyTable[i].plannedPing >= heroVisibleThreshold then
					if Menu.awerness.pingToTeam == 2 then
						PingSignal(Menu.awerness.pingtype, hero.x,hero.y, hero.z, 1)
					else
						if VIP_USER then
							Packet("R_PING",  {x = hero.x, y = hero.z, type = PING_FALLBACK}):receive()
						elseif Menu.awerness.pingtype == (1 or 2) then
							PingSignal(Menu.awerness.pingtype, hero.x, hero.y, hero.z, 0)
							PlaySoundPS(PATH..Menu.awerness.pingtype..".wav",1)
						elseif Menu.awerness.pingtype == 3 then
							PingSignal(5, hero.x, hero.y, hero.z, 0)
							PlaySoundPS(PATH.."5.wav",1)
						end
					end
					
					lastPing = GetTickCount()
					enemyTable[i].heroPinged = GetTickCount()
					purgePlannedPings()
				end
			else
				enemyTable[i].plannedPing = false
			end
		else
			if hero ~= nil and hero.visible and hero.team ~= myHero.team then
				if GetTickCount() - enemyTable[i].heroVisible >= heroVisibleTimeout and GetTickCount() - enemyTable[i].heroPinged >= heroPingTimeout and GetTickCount() - lastPing >= pingTimeout and (MapPosition:inInnerJungle(hero) or MapPosition:inInnerRiver(hero)) and EnemyHeroInRangeToPing(hero, Menu.awerness.minRange, Menu.awerness.maxRange) then
					enemyTable[i].plannedPing = GetTickCount()
				end
				enemyTable[i].heroVisible = GetTickCount()
			end
		end
	end
end

function purgePlannedPings()
	for i=1, heroManager.iCount, 1 do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			enemyTable[i].plannedPing = false
		end
	end
end

function EnemyHeroInRangeToPing(hero, minRange, maxRange)
	for i=1, heroManager.iCount, 1 do
		hero1 = heroManager:getHero(i)
		if hero1.team ~= hero.team and GetDistance(hero, hero1) >= minRange and GetDistance(hero, hero1) <= maxRange then
			return true
		end
	end
	return false
end

function AutoDisable()
	if not autoDisabledPings and GetInGameTimer() > Menu.awerness.autoDisablePings*60 then
		autoDisabledPings = true
		Menu.awerness.enabled = false
		print("<font color=\"#FF0000\"><b>".."Auto ping on gank has been disabled(awerness) (automatically)</b>")
	end
	if not autoDisabledSS and GetInGameTimer() > Menu.missing.autoDisableSS*60 then
		autoDisabledSS = true
		Menu.missing.enabled = false
		print("<font color=\"#FF0000\"><b>".."Auto SS/MIA has been disabled(automatically)</b>")
	end
end

function OAP()
	if Menu.ping.ping and not myHero.dead then
	    if Menu.ping.random then 
	        PingSignal(math.random(1,6), myHero.x, myHero.y, myHero.z, 0)
	    else
		    PingSignal(Menu.ping.pingtype, myHero.x, myHero.y, myHero.z, 0)
		    --PlaySoundPS(PATH..Menu.ping.pingtype..".wav",1)
		end
	end
end

function MovableUI()
	if Menu.appearance.unlock and isMousePressed then 
		if UI.x <= GetCursorPos().x and UI.x+54 >= GetCursorPos().x and GetCursorPos().y >= UI.y and GetCursorPos().y <= UI.y+421 then
			move = true
		end
	else
		move = false
	end

	if move then
		if diff.x == nil or diff.y == nil then
			diff.x = GetCursorPos().x - UI.x
			diff.y = GetCursorPos().y - UI.y
		else
			UI.x = GetCursorPos().x - diff.x
			UI.y = GetCursorPos().y - diff.y
		end
	else
		diff.x = nil
		diff.y = nil
	end
end

function SetScaleHUD()
	scalebyuser = Menu.appearance.scalebyuser * (1/100)
	scalevalue.x = defaultscalevalue.x*scalebyuser
	scalevalue.y = defaultscalevalue.y*scalebyuser
	if initDone then
		local iconpos = {x=UI.x, y=UI.y}
		for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team then			
				enemyTable[i].x =  iconpos.x
				enemyTable[i].y =  iconpos.y
				iconpos.y = 83*scalevalue.y + iconpos.y
			end
		end

	end
end

function PingSS(x,y,z)
	PingSignal(3, x, y, z, 2)
	if Menu.missing.missingchat then
		if Menu.missing.missingtoteam == 2 then
			SendChat("SS "..enemyTable[hero.charName].lane.."("..hero.charName..")")
		else
			print("<font color=\"#FFFFFF\">Extra Hud: SS "..enemyTable[hero.charName].lane.."("..hero.charName..")".."</font>")
		end
	end
end



--"Shit to be drawn"

function DrawRectangleAL(x, y, w, h, color)
	local Points = {}
	Points[1] = D3DXVECTOR2(math.floor(x), math.floor(y))
	Points[2] = D3DXVECTOR2(math.floor(x + w), math.floor(y))
	DrawLines2(Points, math.floor(h), color)
end

function IconDraw(i, hero)
	local fasterTable = enemyTable
	local x = fasterTable[i].x
	local y = fasterTable[i].y
	fasterTable[i].sprite:Draw(x, y, 0xFF)
	DrawRectangleAL(x+40*scalevalue.x, y+52*scalevalue.y , 20*scalevalue.x , 15*scalevalue.y, ARGB(255,0,0,0))
	DrawText(""..hero.level, 12*scalevalue.x, x+47*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255))
	-- Complete rewrite has to be done in laptop
end

function HPandMANAbars(i, hero)
	local fasterTable = enemyTable
	local x = fasterTable[i].x + 1*scalevalue.x 
	local yHP = fasterTable[i].y +66*scalevalue.y
	local yMP = fasterTable[i].y + 78*scalevalue.y
	local height = 12*scalevalue.y
	DrawRectangleAL(x, yHP, HPandMANAbarsWidth(i, hp) , height, ARGB(255,0,175,0))
	if ValueExists(EnergyChamps, hero.charName) then
		DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,255,204,51))
	elseif ValueExists(FuryChamps, hero.charName) then
		if hero.charName == "Shyvana" and fasterTable[i].CurrentMP ~= fasterTable[i].MaxMP then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,255,153,102))
		elseif hero.charName == "Renekton" and fasterTable[i].CurrentMP < 50 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,128,128,128))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,255,0,0))
		end
	elseif ValueExists(OtherChamps, hero.charName) then -- Yasuo/morde logic
		if fasterTable[i].CurrentMP == fasterTable[i].MaxMP then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,255,255,255))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,128,128,128))
		end
	elseif ValueExists(HeatChamps, hero.charName) then -- Rumble logic
		if fasterTable[i].CurrentMP < 50 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,128,128,128))
		elseif fasterTable[i].CurrentMP >= 50 and fasterTable[i].CurrentMP <= 99 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,232,232,0))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,255,0,0))
		end
	else
		DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, mana)  , height, ARGB(255,0,0,255))
	end
end

function HPandMANAbarsWidth(i, hpormana)
	local fasterTable = enemyTable
	if hpormana == hp then
		return (59 * (fasterTable[i].CurrentHP * (1/fasterTable[i].MaxHP)))*scalevalue.x 
	elseif hpormana == mana then
		return (59 * (fasterTable[i].CurrentMP * (1/fasterTable[i].MaxMP)))*scalevalue.x
	end
end

function SSandDEATHtimers(i, hero)
	local fasterTable = enemyTable
	local MissTimer = fasterTable[i].MissTimer
	if not hero.visible and MissTimer ~= nil and not hero.dead then
		MissTimer_minute = math.floor(MissTimer * (1 / 60000))
		MissTimer_second =  math.floor((MissTimer % 60000)*0.001)
		DrawRectangleAL(fasterTable[i].x, fasterTable[i].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,0,0,0))
		DrawText("?",50*scalevalue.x, fasterTable[i].x+17*scalevalue.x, fasterTable[i].y+5*scalevalue.y, ARGB(255, 255, 255, 255))
		DrawText(string.format("%02d:%02d", MissTimer_minute, MissTimer_second),15*scalevalue.x, fasterTable[i].x+15*scalevalue.x, fasterTable[i].y+45*scalevalue.y, ARGB(255, 255,255,255))
	end
	local deathTimer = fasterTable[i].deathTimer
	if hero.dead and deathTimer ~= nil then
		DrawRectangleAL(fasterTable[i].x, fasterTable[i].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,20,0,0))
		if deathTimer < 10 then
			DrawText(""..deathTimer, 50*scalevalue.x,fasterTable[i].x+17*scalevalue.x, fasterTable[i].y+5*scalevalue.y, ARGB(255,255,255,255))
		else
			DrawText(""..deathTimer, 50*scalevalue.x,fasterTable[i].x+8*scalevalue.x, fasterTable[i].y+5*scalevalue.y, ARGB(255,255,255,255))
		end
	end
end

function CDtrackerUI(i, hero)
	local fasterTable = enemyTable
	local WidthSkills = 38*scalevalue.x
	local HeightSkills = 14.95*scalevalue.y
	local CDcolor = ARGB(Menu.appearance.colors.cdcolor[1], Menu.appearance.colors.cdcolor[2], Menu.appearance.colors.cdcolor[3], Menu.appearance.colors.cdcolor[4])
	local RDYcolor = ARGB(Menu.appearance.colors.readycolor[1], Menu.appearance.colors.readycolor[2], Menu.appearance.colors.readycolor[3], Menu.appearance.colors.readycolor[4])
	local x = fasterTable[i].x+61*scalevalue.x
	local y = fasterTable[i].y

	if stateQ[i].cd == -1 then
		DrawRectangleAL(x, y+7*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148)) 
	elseif stateQ[i].cd ~= 0 and stateQ[i].cd ~= -1 then
		DrawRectangleAL(x, y+7*scalevalue.y, DrawProgressBar(stateQ[i].cd, stateQ[i].maxCD, spell), HeightSkills, CDcolor)
	else
		DrawRectangleAL(x, y+7*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end

	if stateW[i].cd == -1 then
		DrawRectangleAL(x, y+22*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateW[i].cd ~= 0 and stateW[i].cd ~= -1 then
		DrawRectangleAL(x, y+22*scalevalue.y, DrawProgressBar(stateW[i].cd, stateW[i].maxCD, spell), HeightSkills, CDcolor)
	else
		DrawRectangleAL(x, y+22*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end

	if stateE[i].cd == -1 then
		DrawRectangleAL(x, y+37*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateE[i].cd ~= 0 and stateE[i].cd ~= -1 then
		DrawRectangleAL(x, y+37*scalevalue.y, DrawProgressBar(stateE[i].cd, stateE[i].maxCD, spell), HeightSkills, CDcolor)
	else
		DrawRectangleAL(x, y+37*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end	

	if stateR[i].cd == -1 then
		DrawRectangleAL(x, y+52*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateR[i].cd ~= 0 and stateR[i].cd ~= -1 then
		DrawRectangleAL(x, y+52*scalevalue.y, DrawProgressBar(stateR[i].cd, stateR[i].maxCD, spell), HeightSkills, CDcolor)
	else
		DrawRectangleAL(x, y+52*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end
 
	local SScolor = ARGB(Menu.appearance.colors.sscolor[1], Menu.appearance.colors.sscolor[2], Menu.appearance.colors.sscolor[3], Menu.appearance.colors.sscolor[4])
	for j=1, #SSpells, 1 do
		local spell = SSpells[j]
		if spell.Name == hero:GetSpellData(SUMMONER_1).name then
			spell.sprite:Draw(x,y+60*scalevalue.y,0xFF)
			if stateS1[i].cd ~= 0 then
				DrawRectangleAL(x, y+72*scalevalue.y, DrawProgressBar(stateS1[i].cd, stateS1[i].maxCD, SS), 25*scalevalue.y, SScolor)
			end
		elseif spell.Name == hero:GetSpellData(SUMMONER_2).name then
			spell.sprite:Draw(x+20*scalevalue.x, y+60*scalevalue.y,0xFF)
			if stateS2[i].cd ~= 0 then
				DrawRectangleAL(x+20*scalevalue.x, y+72*scalevalue.y, DrawProgressBar(stateS2[i].cd, stateS2[i].maxCD, SS), 25*scalevalue.y, SScolor)
			end
		end
	end
end

function DrawProgressBar(currentValue, maxValue, spellorSS)
	local WidthSkills = 38*scalevalue.x
	if spellorSS == spell then
		return WidthSkills - math.floor(WidthSkills * currentValue) * (1/maxValue)
	else
		return (WidthSkills*0.5) - math.floor((WidthSkills*0.5) * currentValue) * (1/maxValue)
	end
end

--"Menu, update and init"

function init()
	SetScaleHUD()
	lastPing = GetTickCount()
	UI={x=1502*scalevalue.x, y=114*scalevalue.y}
	IconUI={x=1502*scalevalue.x, y=114*scalevalue.y}
	PATH = BOL_PATH.."Sprites\\ExtraHud\\"

	HUD = FindSprite(PATH.."HUD.dds")
	HUDBG = FindSprite(PATH.."HUDBG.png")
	HUD:SetScale(scalevalue.x, scalevalue.y)
	HUDBG:SetScale(scalevalue.x, scalevalue.y)

	for i = 1, heroManager.iCount, 1 do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			enemyTable[i] = {sprite = nil, x = UI.x, y = UI.y,												-- Hud
							 CurrentHP = 1, MaxHP = 1, CurrentMP = 1, MaxHP = 1, 							-- ChampStats
							 lane = nil, tick_hero = nil, MissTimer = nil,									-- SS timer
							 tick_deathTimer = nil, deathTimer = nil,										-- Death timer
							 plannedPing = false, heroVisible = GetTickCount(), heroPinged = GetTickCount() -- Awerness 
							} 
			enemyTable[i].sprite = FindSprite(PATH .. enemy.charName .. '_Square_0.dds')
			enemyTable[i].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)
			UI.y = 83*scalevalue.y + UI.y
		end
	end

	UI={x=1502*scalevalue.x, y=114*scalevalue.y}
	for i=1, #SSpells, 1 do
		local spell = SSpells[i]
		spell.sprite = FindSprite(PATH..spell.Name..".dds")
		spell.sprite:SetScale(0.28*scalevalue.x, 0.36*scalevalue.y)
	end
	initDone= true
end

function menu()
	Menu = scriptConfig("Extra Hud", "extrahud")
	--Awerness config
	Menu:addSubMenu("Awerness", "awerness")
		Menu.awerness:addParam("enabled", "Ping incoming Ganks", SCRIPT_PARAM_ONOFF, true)
   		Menu.awerness:addParam("pingToTeam","Ping: ", SCRIPT_PARAM_LIST, 2, { "Client-Sided", "Server-Sided"})
	   	Menu.awerness:addParam("pingtype","Ping type: ", SCRIPT_PARAM_LIST, 2, { "Normal", "Danger", "Fallback"})
	   	Menu.awerness:addParam("minRange", "Minimum Range needed to ping", SCRIPT_PARAM_SLICE, 600, 600, 1500, 0)
	   	Menu.awerness:addParam("maxRange", "maximum Range to ping", SCRIPT_PARAM_SLICE, 4000, 4000, 10000, 0)
	   	Menu.awerness:addParam("autoDisablePings", "Automatically disable at minute:", SCRIPT_PARAM_SLICE, 15, 10, 30, 0)

	--Auto SS config
	Menu:addSubMenu("Auto SS/MIA","missing")
		Menu.missing:addParam("enabled", "Auto SS/MIA", SCRIPT_PARAM_ONOFF, true)
		Menu.missing:addParam("pinglaneonly", "Ping only from same lane", SCRIPT_PARAM_ONOFF, true)
		Menu.missing:addParam("pingtolaneorchamp","Ping to lane or last known posistion ", SCRIPT_PARAM_LIST, 1, { "Center of lane", "Last Known position"})
		Menu.missing:addParam("missingtime", "Time missing needed to ping", SCRIPT_PARAM_SLICE, 5, 1, 20, 0)
		Menu.missing:addParam("missingchat", "Send chat ss with last known position", SCRIPT_PARAM_ONOFF, false)
		Menu.missing:addParam("missingtoteam","Chat: ", SCRIPT_PARAM_LIST, 2, { "Client-Sided", "Server-Sided"})
		Menu.missing:addParam("autoDisableSS", "Automatically disable at minute:", SCRIPT_PARAM_SLICE, 15, 10, 30, 0)	

	--Appearance
	Menu:addSubMenu("Appearance", "appearance")
		Menu.appearance:addSubMenu("Colors", "colors")
			Menu.appearance.colors:addParam("cdcolor", "Cooldown color", SCRIPT_PARAM_COLOR, {255, 214, 114, 0})--orange
			Menu.appearance.colors:addParam("readycolor", "Ready color", SCRIPT_PARAM_COLOR, {255,0, 175, 0})--green
			Menu.appearance.colors:addParam("sscolor", "Summoner Spells cooldown color", SCRIPT_PARAM_COLOR, {150, 0, 0, 0})--black light
		Menu.appearance:addParam("scalebyuser", "Scale(%)", SCRIPT_PARAM_SLICE, 100, 1, 100, 0)
		Menu.appearance:addParam("unlock", "Move UI", SCRIPT_PARAM_ONKEYDOWN,false, 16)

	--Awesome ping folower!
	Menu:addSubMenu("Overly Attched Ping(for the lulz)", "ping")
		Menu.ping:addParam("ping", " Overly Attched Ping", SCRIPT_PARAM_ONOFF, false)
		Menu.ping:addParam("random", "Ping type random", SCRIPT_PARAM_ONOFF, false)
		Menu.ping:addParam("pingtype", "Ping type", SCRIPT_PARAM_SLICE, 3, 1, 6, 0)
end

function CheckUpdates()
	if _G.ExtraHudUpdateEnabled then
		GetAsyncWebResult(UPDATE_HOST, UPDATE_PATH, function(d) ServerData = d end)
		if FileExist(PATH.."ExtraHudSpriteVersion.txt") then
			local SpriteVersion = readAll(PATH.."ExtraHudSpriteVersion.txt")
			if _G.ExtraHudSpriteVersion > SpriteVersion then
				print("<font color=\"#00FF00\"><b>Extra Hud:</b> Please Download latest sprites from thread")
			else
				print("<font color=\"#00FF00\"><b>Extra Hud:</b> You have got latest sprites version: <u><b>".._G.ExtraHudSpriteVersion.."</b></u></font>")	
			end
		else
			print("<font color=\"#00FF00\"><b>Extra Hud:</b> Please Download latest sprites from thread")
		end
		DelayAction(DelayedUpdate, 3)
	end
end

function DelayedUpdate()
	if ServerData ~= nil then
		local ServerVersion
		local send, tmp, sstart = nil, string.find(ServerData, "_G.ExtraHudVersion = \"")
		if sstart then
			send, tmp = string.find(ServerData, "\"", sstart+1)
		end
		if send then
			ServerVersion = tonumber(string.sub(ServerData, sstart+1, send-1))
		end
		if ServerVersion ~= nil and tonumber(ServerVersion) ~= nil and tonumber(ServerVersion) > tonumber(_G.ExtraHudVersion) then
			DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () print("<font color=\"#00FF00\"><b>Extra Hud:</b> successfully updated.Please reload script (double F9). (".._G.ExtraHudVersion.." => "..ServerVersion..")</font>") end)     
		elseif ServerVersion then
			print("<font color=\"#00FF00\"><b>Exta Hud:</b> You have got the latest version: <u><b>".._G.ExtraHudVersion.."</b></u></font>")
		end		
		ServerData = nil
	end
end

function readAll(file)
    local f = io.open(file, "rb")
    local content = f:read("*all")
    f:close()
    return content
end