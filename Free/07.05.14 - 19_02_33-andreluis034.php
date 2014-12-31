<?php exit() ?>--by andreluis034 188.251.54.16
--Encrypt this line and below
-- Auto Updater info
local UPDATE_HOST = 'raw.githubusercontent.com'
local UPDATE_PATH = '/andreluis034/AndreRepo/master/extrahud.lua?rand='..math.random(1,10000) -- 'Disable' caching to prevent older versions from being retrieved
local UPDATE_FILE_PATH = SCRIPT_PATH..'ExtraHud.lua'
local UPDATE_URL = 'https://'..UPDATE_HOST..UPDATE_PATH
local ServerData

local autoDisabledPings    = false
local autoDisabledSS	   = false
local heroVisibleTimeout   = 2500   -- mimimum time in ms that a hero has to be invisible to be pinged,a
local heroPingTimeout      = 10000 -- minimum time in ms until the same hero can be pinged again
local pingTimeout          = 3000  -- minimum time in ms between separate pings
local heroVisibleThreshold = 100   -- minimum time in ms a hero has to be visible again to get pinged
local eTable, enemyTable, spriteTable = {}, {}, {}
local Saved = GetSave('ExtraHud')
local SavedPos = GetSave('ExtraHudPos')
local ID = myHero.networkID..myHero.charName
local alreadyWarnedGult = {}
--[[local lvlDeathTimer = {
    [1] = 8,        [2] = 10,      [3] = 12,      [4] = 15,     [5] = 18,     [6] = 20,
    [7] = 23,     [8] = 25,     [9] = 28,     [10] = 30,     [11] = 32,    [12] = 35,
    [13] = 37,   [14] = 40,   [15] = 42,   [16] = 45,   [17] = 47,   [18] = 49}]]

local enemies = GetEnemyHeroes()
for i, enemy in ipairs(GetEnemyHeroes()) do
	ID =  ID..enemy.networkID
end


local turrets 
local LastKnownPing     = nil
local EnergyChamps	= { 'Akali',		'Kennen',	'LeeSin',		'Shen',	'Zed' }
local FuryChamps	= { 'Renekton',		'Shyvana',	'Tryndamere',	'Aatrox'	  } -- Aatrox not really Fury but it is red so wtvr
local OtherChamps	= { 'Mordekaiser',	'Yasuo'									  } -- To be added
local HeatChamps	= { 'Rumble'												  } -- Fuck you too rito
local GlobalChamps  = { 'Karthus',		'Shen', 	'Soraka', 		'Gangplank', 	'Pantheon', 	'TwistedFate', 		'Nocturne'}
local SSpells 		= { {Name='SummonerFlash',          sprite = nil},
						{Name='SummonerHaste',          sprite = nil},
						{Name='SummonerDot',            sprite = nil},
						{Name='SummonerBarrier',        sprite = nil},
						{Name='SummonerSmite',          sprite = nil},
						{Name='SummonerExhaust',        sprite = nil},
						{Name='SummonerHeal',           sprite = nil},
						{Name='SummonerTeleport',       sprite = nil},
						{Name='SummonerBoost',          sprite = nil},
						{Name='SummonerMana',           sprite = nil},
						{Name='SummonerClairvoyance',   sprite = nil},
						{Name='SummonerRevive',         sprite = nil},
						{Name='SummonerOdinGarrison',   sprite = nil},
						}
local lanesCenter 	= { {lane = 'top', x=1437,y=myHero.y,z=12708},
					 	{lane = 'mid', x=6985,y=myHero.y,z=7186},
					 	{lane = 'bot', x=12367,y=myHero.y,z=1963},
					 	}
local stateQ,stateW,stateE,stateR,stateS1,stateS2 = {},{},{},{},{},{}
local Chatcolor = '00DD00'
local TickLimit = 0
local initDone = false
local x = 0
local y = 0
local FirstTick = false
local lastPing	 = nil
local defaultscalevalue = {x = WINDOW_W * (1/1600), y = WINDOW_H * (1/900)}
local lastClock = 0
local CurrentClock = 0
--print(defaultscalevalue.x)
local scalevalue = {x=0, y=0}
local diff={x=nil, y=nil}
local move = false
local isMousePressed = false
local alreadyWarnedSprite=false

--Main functions

function OnLoad()
	menu()
	if Menu.appearance.extrahudcolor == 2 then
		Chatcolor = '00DD00'
	else
		Chatcolor = '3FBFFC'
	end
	init()
	PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Version '.._G.ExtraHudVersion..' Loaded'..'</font>')
	CheckUpdates()
end

function OnTick()	
	if IsWallOfGrass(D3DXVECTOR3(myHero.x, myHero.y, myHero.z)) then
	end

	local enemyTable = Saved['eTable']
	if initDone then
		if os.clock() - TickLimit > 1 then
			TickLimit = os.clock()

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
		MoveableUI()
		SetScaleHUD()
		for i=1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team then
				PingLastKnownLocation(i, enemy)
			end
		end
	end
end

function OnDraw()
	--DrawText("x:"..round(mousePos.x), 20, 100,100, ARGB(255,255,255,255))
	--DrawText("y:"..mousePos.z, 20, 100,200, ARGB(255,255,255,255))
	if CanDraw(Menu.experimental.refreshRate) then
		local UI = SavedPos['UI']
		if FirstTick and Menu.appearance.skin ~= 4  then
			local enemyTable = Saved['eTable']

			local HUDpos={x=UI.x, y=UI.y}
			local BGHUDpos={x=UI.x-15*scalevalue.x, y=UI.y-16*scalevalue.y}
			if Menu.appearance.skin == 1 then
				HUD:SetScale(scalevalue.x, scalevalue.y)
				HUDBG:SetScale(scalevalue.x, scalevalue.y)
				HUDBG:Draw(BGHUDpos.x,BGHUDpos.y, 0xFF)
			end
			for i=1, #SSpells, 1 do
				local spell = SSpells[i]
				--spell.sprite = FindSprite(PATH..spell.Name..'.png')
				spell.sprite:SetScale(0.29*scalevalue.x, 0.36*scalevalue.y)
			end

			for i = 1, heroManager.iCount, 1 do
				local enemy = heroManager:getHero(i)
				if enemy.team ~= myHero.team then
					spriteTable[i].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)
					if Menu.appearance.champtrack[enemy.charName] then
						IconDraw(i, enemy)
						HPandMANAbars(i, enemy)
						SSandDEATHtimers(i, enemy)
						CDtrackerUI(i, enemy)
					end
				end
			end
			if Menu.appearance.skin == 1 then
				HUD:Draw(HUDpos.x,HUDpos.y, 0xFF)
			elseif Menu.appearance.skin == 2 then
				HUD2:SetScale(scalevalue.x, scalevalue.y)
				HUD2:Draw(HUDpos.x-13*scalevalue.x,HUDpos.y-15*scalevalue.y, 0xFF)
			end
		end
		TowerHPDraw()
	end
end

function OnWndMsg(msg,keycode) 
	--print(msg.." keycode: "..keycode)
	if msg == 513 then
		isMousePressed = true
	elseif msg == 514 then
		isMousePressed = false
	end
end

function OnProcessSpell(unit, spellProc)
	local enemyTable = Saved['eTable']
	if unit and unit.valid and unit.type == "obj_AI_Hero" then
		for i=1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if unit.team ~= myHero.team and unit == enemy and ValueExists(GlobalChamps, unit.charName) then
				if tonumber(stateR[i].cd) ~= (0 and -1) and not enemyTable[i].alreadyWarnedGult and enemy:GetSpellData(_R).name == spellProc.name then
					PrintAlert(enemy.charName .. " used ultimate!", 3, Menu.appearance.globalNotifye.onUse[2], Menu.appearance.globalNotifye.onUse[3], Menu.appearance.globalNotifye.onUse[4])

					DelayAction(function() enemyTable[i].alreadyWarnedGult = true end, 5)
				end
			end
		end
	end
end

--'Invisible functions'
function TrackCds(i, hero)
	local enemyTable = Saved['eTable']
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
		
		enemyTable[i].MaxHP = hero.maxHealth
		enemyTable[i].MaxMP = hero.maxMana


	if not hero.visible and not hero.dead and Menu.experimental.FowHPMPregen then
			enemyTable[i].CurrentHP = enemyTable[i].CurrentHP + hero.hpRegen
			enemyTable[i].CurrentMP = enemyTable[i].CurrentMP + hero.mpRegen
			--print(hero.charName.. " With regen: ".. hero.mpRegen)
	else
		enemyTable[i].CurrentHP = hero.health
		enemyTable[i].CurrentMP = hero.mana
	end

	if hero:CanUseSpell(_Q) == NOTLEARNED then
		stateQ[i] = {cd = -1}
	elseif QCD == 0 then
		stateQ[i] = {cd = 0, maxCD = string.format('%.0f', math.floor(QCD) > QMAXCD and math.floor(QCD) or QMAXCD)}
	else
		stateQ[i] = {cd = string.format('%.0f', QCD), maxCD = string.format('%.0f', math.floor(QCD) > QMAXCD and math.floor(QCD) or QMAXCD)}
	end
	if hero:CanUseSpell(_W) == NOTLEARNED then
		stateW[i] = {cd = -1}
	elseif WCD == 0 then
		stateW[i] = {cd = 0, maxCD = string.format('%.0f', math.floor(WCD) > WMAXCD and math.floor(WCD) or WMAXCD)}
	else
		stateW[i] = {cd = string.format('%.0f', WCD), maxCD = string.format('%.0f', math.floor(WCD) > WMAXCD and math.floor(WCD) or WMAXCD)}
	end

	if hero:CanUseSpell(_E) == NOTLEARNED then
		stateE[i] = {cd = -1}
	elseif ECD == 0 then
		stateE[i] = {cd = 0, maxCD = string.format('%.0f', math.floor(ECD) > EMAXCD and math.floor(ECD) or EMAXCD)}
	else
		stateE[i] = {cd = string.format('%.0f', ECD), maxCD = string.format('%.0f', math.floor(ECD) > EMAXCD and math.floor(ECD) or EMAXCD)}
	end

	if hero:CanUseSpell(_R) == NOTLEARNED then
		stateR[i] = {cd = -1}
	elseif RCD == 0 then
		stateR[i] = {cd = 0, maxCD = string.format('%.0f', math.floor(RCD) > RMAXCD and math.floor(RCD) or RMAXCD)}
		if ValueExists(GlobalChamps, hero.charName) and enemyTable[i].alreadyWarnedGult then
			PrintAlert(hero.charName .. " has the ultimate ready!", 3, Menu.appearance.globalNotifye.onReady[2], Menu.appearance.globalNotifye.onReady[3], Menu.appearance.globalNotifye.onReady[4])
			enemyTable[i].alreadyWarnedGult = false
		end
	else
		stateR[i] = {cd = string.format('%.0f', RCD), maxCD = string.format('%.0f', math.floor(RCD) > RMAXCD and math.floor(RCD) or RMAXCD)}
	end

	if S1CD == 0 then
		stateS1[i] = {cd = 0, maxCD = string.format('%.0f', S1MAXCD)}
	else
		stateS1[i] = {cd = string.format('%.0f', S1CD), maxCD = string.format('%.0f', S1MAXCD)}
	end

	if S2CD == 0 then
		stateS2[i] = {cd = 0, maxCD = string.format('%.0f', S2MAXCD)}
	else
		stateS2[i] = {cd = string.format('%.0f', S2CD), maxCD = string.format('%.0f', S2MAXCD)}
	end
end

function SSTimer(i, hero)
	local enemyTable = Saved['eTable']
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
	local enemyTable = Saved['eTable']
	if hero.dead then
		if enemyTable[i].tick_deathTimer == nil then
			enemyTable[i].tick_deathTimer = GetTickCount()
		end
		enemyTable[i].deathTimer = math.ceil(getDeathTimer(hero) - ((GetTickCount() - enemyTable[i].tick_deathTimer)*0.001))
	else
		enemyTable[i].tick_deathTimer = nil
	end
end

function GetLane(unit)
	if not unit then return nil end

	local Position = nil

	if MapPosition:onTopLane(unit) then
		Position = 'top'
	elseif MapPosition:onMidLane(unit) then
		Position = 'mid'
	elseif MapPosition:onBotLane(unit) then
		Position = 'bot'
	elseif MapPosition:inOuterJungle(unit) then
		Position = 'Outer Jungle'
	elseif MapPosition:inInnerJungle(unit) then
		Position = 'Inner Jungle'
	elseif MapPosition:inOuterRiver(unit) then
		Position = 'Outer River'
	elseif MapPosition:inInnerRiver(unit) then
		Position = 'Inner River'
	elseif MapPosition:inLeftBase(unit) then
		Position = 'Blue Base'
	elseif MapPosition:inRightBase(unit) then
		Position = 'Purple Base'
	end

	if Position ~= nil then
		return Position
	else
		return nil
	end
end

function FindSprite(file)
	assert(type(file) == 'string', 'GetSprite: wrong argument types (<string> expected for file)')
	if FileExist(file) == true then
		--print('Sprite available')
		return createSprite(file)
	else
		if not alreadyWarnedSprite then
			PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Please Download latest sprites from thread</font>')
			alreadyWarnedSprite = true
		end

		return createSprite('empty.dds')
	end
end

function ValueExists(tbl, value)
	for k = 1, #tbl, 1 do
		local v = tbl[k]
		if value == v then
		return true
		end
	end
end

function AutoSS(i, hero)
	local enemyTable = Saved['eTable']
	if Menu.experimental.AdvSS then
		if Menu.missing.enabled and enemyTable[i].MissTimer ~= nil and enemyTable[i].MissTimer >= Menu.missing.missingtime*1000 and enemyTable[i].MissTimer < Menu.missing.missingtime*1000 + 1000 and GetInGameTimer() >= 120 and not hero.dead and not IsWallOfGrass(D3DXVECTOR3(hero.x, hero.y, hero.z)) then
			if Menu.missing.pinglaneonly and enemyTable[i].lane ~= nil and enemyTable[i].lane == GetLane(myHero) then
				if Menu.missing.pingtolaneorchamp == 2 then
					PingSS(hero.x, hero.y, hero.z,i)
				else
					local lane = enemyTable[i].lane
					if (lane == 'top' or lane == 'mid' or lane == 'bot') then
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
					PingSS(hero.x, hero.y, hero.z,i)
				else
					local lane = enemyTable[i].lane
					if (lane == 'top' or lane == 'mid' or lane == 'bot') then
						local laneCenter = lanesCenter
						for i = 1, #laneCenter, 1 do
							local v = laneCenter[i]
							if v.lane == lane then
								PingSS(v.x,v.y,v.z,i)
							end
						end
					end
				end
			end
		end
	else
		if Menu.missing.enabled and enemyTable[i].MissTimer ~= nil and enemyTable[i].MissTimer >= Menu.missing.missingtime*1000 and enemyTable[i].MissTimer < Menu.missing.missingtime*1000 + 1000 and GetInGameTimer() >= 120 and not hero.dead then
			if Menu.missing.pinglaneonly and enemyTable[i].lane ~= nil and enemyTable[i].lane == GetLane(myHero) then
				if Menu.missing.pingtolaneorchamp == 2 then
					PingSS(hero.x, hero.y, hero.z,i)
				else
					local lane = enemyTable[i].lane
					if (lane == 'top' or lane == 'mid' or lane == 'bot') then
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
					PingSS(hero.x, hero.y, hero.z,i)
				else
					local lane = enemyTable[i].lane
					if (lane == 'top' or lane == 'mid' or lane == 'bot') then
						local laneCenter = lanesCenter
						for i = 1, #laneCenter, 1 do
							local v = laneCenter[i]
							if v.lane == lane then
								PingSS(v.x,v.y,v.z,i)
							end
						end
					end
				end
			end
		end
	end
end

function AutoPingGank(i, hero)
	local enemyTable = Saved['eTable']
	if Menu.awerness.enabled then
		if enemyTable[i].plannedPing then
			enemyTable[i].heroVisible = GetTickCount()
			if hero ~= nil and hero.visible and hero.team ~= myHero.team then
				if GetTickCount() - enemyTable[i].plannedPing >= heroVisibleThreshold then
					if Menu.awerness.pingToTeam == 2 then
						PingSignal(Menu.awerness.pingtype, hero.x,hero.y, hero.z, 1)
					else
						if VIP_USER then
							Packet('R_PING',  {x = hero.x, y = hero.z, type = PING_FALLBACK}):receive()
						elseif Menu.awerness.pingtype == (1 or 2) then
							PingSignal(Menu.awerness.pingtype, hero.x, hero.y, hero.z, 0)
							PlaySoundPS(PATH..Menu.awerness.pingtype..'.wav',1)
						elseif Menu.awerness.pingtype == 3 then
							PingSignal(5, hero.x, hero.y, hero.z, 0)
							PlaySoundPS(PATH..'5.wav',1)
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
	local enemyTable = Saved['eTable']
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
		PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Auto ping on gank/Awerness has been disabled automatically.</font>')
	end
	if not autoDisabledSS and GetInGameTimer() > Menu.missing.autoDisableSS*60 then
		autoDisabledSS = true
		Menu.missing.enabled = false
		PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Auto SS/MIA has been disabled automatically.</font>')
	end
end

function OAP()
	if Menu.ping.ping and not myHero.dead then
	    if Menu.ping.random then 
	        PingSignal(math.random(1,6), myHero.x, myHero.y, myHero.z, 0)
	    else
		    PingSignal(Menu.ping.pingtype, myHero.x, myHero.y, myHero.z, 0)
		    --PlaySoundPS(PATH..Menu.ping.pingtype..'.wav',1)
		end
	end
end

function MoveableUI()
	--print(UI.x)
	local UI = SavedPos['UI']
	if Menu.appearance.unlock and isMousePressed then 
		if UI.x <= GetCursorPos().x and UI.x+54*scalevalue.x >= GetCursorPos().x and GetCursorPos().y >= UI.y and GetCursorPos().y <= UI.y+421*scalevalue.y then
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
	local UI = SavedPos["UI"]
	local enemyTable = Saved['eTable']
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

function PingSS(x,y,z,i)
	local enemyTable = Saved['eTable']
	PingSignal(3, x, y, z, 2)
	if Menu.missing.missingchat then
		if Menu.missing.missingtoteam == 2 then
			SendChat('SS '..enemyTable[hero.charName].lane..'('..hero.charName..')')
		else
			PrintChat('<font color=\'#F8F8F8\'>Extra Hud: SS '..enemyTable[i].lane..'('..hero.charName..')'..'</font>')
		end
	end
end

function PingLastKnownLocation(i, hero)
	if isMousePressed and GetTickCount()-LastKnownPing>=500 and not Menu.appearance.unlock then
		--LastKnownPing = GetTickCount()
		local enemyTable = Saved['eTable']
		local x = enemyTable[i].x
		local y = enemyTable[i].y
		--print(y)
		if x <= GetCursorPos().x and x+54*scalevalue.x >= GetCursorPos().x and GetCursorPos().y >= y and GetCursorPos().y <= y+54*scalevalue.y then
			if Menu.pinglastknown.clientorserver == 2 then
				PingSignal(Menu.pinglastknown.pingtype, hero.x,hero.y, hero.z, 1)
				LastKnownPing = GetTickCount()
			else
				if VIP_USER then
					Packet('R_PING',  {x = hero.x, y = hero.z, type = PING_NORMAL}):receive()
				else
					PingSignal(Menu.pinglastknown.pingtype, hero.x,hero.y, hero.z, 0)
					PlaySoundPS(PATH..Menu.pinglastknown.pingtype..'.wav',1)
					LastKnownPing = GetTickCount()
				end
			end
		end
	end
end

function CanDraw(DrawLimit)
	CurrentClock = os.clock()
	if CurrentClock < lastClock + (1/DrawLimit) then 
		return false
	else
		lastClock = CurrentClock
		return true
	end
end

function getDeathTimer(unit)
	if unit.deathTimer < 8 and Menu.experimental.CustomDT then -- check if it is broken or not
		--print("no valid death timer from bol")
		local ddt = 2.4818*unit.level+5.2 --lvlDeathTimer[unit.level] 
		if GetInGameTimer() < 1800 then 
			return ddt
		elseif GetInGameTimer() <= 3300 then 
			return ddt+(ddt*(1/3000*(GetInGameTimer()-1800)))
		else 
			return ddt+ddt*0.5
		end
	else
		return unit.deathTimer 
	end
end

function round(num, idp)
	local mult = 10^(idp or 0)
	return math.floor(num * mult + 0.5) / mult
end

--'Shit to be drawn'

function DrawRectangleAL(x, y, w, h, color)
	local Points = {}
	Points[1] = D3DXVECTOR2(math.floor(x), math.floor(y))
	Points[2] = D3DXVECTOR2(math.floor(x + w), math.floor(y))
	DrawLines2(Points, math.floor(h), color)
end

function IconDraw(i, hero)
	local enemyTable = Saved['eTable']
	local x = enemyTable[i].x
	local y = enemyTable[i].y
	spriteTable[i].sprite:Draw(x, y, 0xFF)
	if Menu.appearance.skin == 1 then
		DrawRectangleAL(x+40*scalevalue.x, y+52*scalevalue.y , 20*scalevalue.x , 15*scalevalue.y, ARGB(255,0,0,0))
		DrawText(''..hero.level, 12*scalevalue.x, x+47*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255))
	elseif Menu.appearance.skin == 2 then
		DrawRectangleAL(x+0*scalevalue.x, y+52*scalevalue.y , 20*scalevalue.x , 15*scalevalue.y, ARGB(255,0,0,0))
		if hero.level < 10 then
			DrawText(''..hero.level, 12*scalevalue.x, x+10*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255))
		else
			DrawText(''..hero.level, 12*scalevalue.x, x+7*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255))
		end
	end
end

function HPandMANAbars(i, hero)
	local enemyTable = Saved['eTable']
	local x = enemyTable[i].x + 1*scalevalue.x 
	local yHP = enemyTable[i].y +66*scalevalue.y
	local yMP = enemyTable[i].y + 78*scalevalue.y
	local height = 12*scalevalue.y
	--local hpcolor = ARGB(255, 255 - (hero.health * 100 / hero.maxHealth * 255 / 100), hero.health * 100 / hero.maxHealth * 175 / 100, 0)
	if Menu.appearance.HPcolor then
		hpcolor = ARGB(255, 175 - (hero.health * 100 / hero.maxHealth * 175 / 100), hero.health * 100 / hero.maxHealth * 175 / 100, 0)
	elseif not Menu.appearance.HPcolor then
		hpcolor = ARGB(255, 0, 175, 0)
	end
	DrawRectangleAL(x, yHP, HPandMANAbarsWidth(i, 1) , height, hpcolor)
	if ValueExists(EnergyChamps, hero.charName) then
		DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,255,204,51))
	elseif ValueExists(FuryChamps, hero.charName) then
		if hero.charName == 'Shyvana' and enemyTable[i].CurrentMP ~= enemyTable[i].MaxMP then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,255,153,102))
		elseif hero.charName == 'Renekton' and enemyTable[i].CurrentMP < 50 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,128,128,128))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,255,0,0))
		end
	elseif ValueExists(OtherChamps, hero.charName) then -- Yasuo/morde logic
		if enemyTable[i].CurrentMP == enemyTable[i].MaxMP then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,255,255,255))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,128,128,128))
		end
	elseif ValueExists(HeatChamps, hero.charName) then -- Rumble logic
		if enemyTable[i].CurrentMP < 50 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,128,128,128))
		elseif enemyTable[i].CurrentMP >= 50 and enemyTable[i].CurrentMP <= 99 then
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,232,232,0))
		else
			DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,255,0,0))
		end
	else
		DrawRectangleAL(x, yMP, HPandMANAbarsWidth(i, 2)  , height, ARGB(255,0,0,255))
	end
end

function HPandMANAbarsWidth(i, hpormana)
	local enemyTable = Saved['eTable']
	if hpormana == 1 then
		--print(enemyTable[i].CurrentMP)
		return (59 * (enemyTable[i].CurrentHP * (1/enemyTable[i].MaxHP)))*scalevalue.x 
	else
		--print(enemyTable[i].CurrentMP)
		return (59 * (enemyTable[i].CurrentMP * (1/enemyTable[i].MaxMP)))*scalevalue.x
	end
end

function SSandDEATHtimers(i, hero)
	local enemyTable = Saved['eTable']
	local MissTimer = enemyTable[i].MissTimer
	if not hero.visible and MissTimer ~= nil and not hero.dead then
		MissTimer_minute = math.floor(MissTimer * (1 / 60000))
		MissTimer_second =  math.floor((MissTimer % 60000)*0.001)
		DrawRectangleAL(enemyTable[i].x, enemyTable[i].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,0,0,0))
		DrawText('?',50*scalevalue.x, enemyTable[i].x+17*scalevalue.x, enemyTable[i].y+5*scalevalue.y, ARGB(255, 255, 255, 255))
		DrawText(string.format('%02d:%02d', MissTimer_minute, MissTimer_second),15*scalevalue.x, enemyTable[i].x+15*scalevalue.x, enemyTable[i].y+45*scalevalue.y, ARGB(255, 255,255,255))
	end
	local deathTimer = enemyTable[i].deathTimer
	if hero.dead and deathTimer ~= nil then
		DrawRectangleAL(enemyTable[i].x, enemyTable[i].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,20,0,0))
		if deathTimer < 10 then
			DrawText(''..deathTimer, 50*scalevalue.x,enemyTable[i].x+17*scalevalue.x, enemyTable[i].y+5*scalevalue.y, ARGB(255,255,255,255))
		else
			DrawText(''..deathTimer, 50*scalevalue.x,enemyTable[i].x+8*scalevalue.x, enemyTable[i].y+5*scalevalue.y, ARGB(255,255,255,255))
		end
	end
end

function CDtrackerUI(i, hero)
	local enemyTable = Saved['eTable']
	local WidthSkills = 38*scalevalue.x
	local HeightSkills = 14.95*scalevalue.y
	local CDcolor = ARGB(Menu.appearance.colors.cdcolor[1], Menu.appearance.colors.cdcolor[2], Menu.appearance.colors.cdcolor[3], Menu.appearance.colors.cdcolor[4])
	local RDYcolor = ARGB(Menu.appearance.colors.readycolor[1], Menu.appearance.colors.readycolor[2], Menu.appearance.colors.readycolor[3], Menu.appearance.colors.readycolor[4])
	local x = enemyTable[i].x+61*scalevalue.x
	local y = enemyTable[i].y

	if stateQ[i].cd == -1 then
		DrawRectangleAL(x, y+7*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148)) 
	elseif stateQ[i].cd ~= 0 and stateQ[i].cd ~= -1 then
		DrawRectangleAL(x, y+7*scalevalue.y, DrawProgressBar(stateQ[i].cd, stateQ[i].maxCD, 1), HeightSkills, CDcolor)
		if Menu.appearance.CompleteCDtracker.Qfullcd then
			if tonumber(stateQ[i].cd) > 9  then
				DrawTextWithBorder(''..stateQ[i].cd, 13*scalevalue.x, x+14*scalevalue.x, y+1.5*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			else
				DrawTextWithBorder(''..stateQ[i].cd, 13*scalevalue.x, x+17*scalevalue.x, y+1.5*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			end
		end
	else
		DrawRectangleAL(x, y+7*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end

	if stateW[i].cd == -1 then
		DrawRectangleAL(x, y+22*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateW[i].cd ~= 0 and stateW[i].cd ~= -1 then
		DrawRectangleAL(x, y+22*scalevalue.y, DrawProgressBar(stateW[i].cd, stateW[i].maxCD, 1), HeightSkills, CDcolor)
		if Menu.appearance.CompleteCDtracker.Wfullcd then
			if tonumber(stateW[i].cd) > 9  then
				DrawTextWithBorder(''..stateW[i].cd, 13*scalevalue.x, x+14*scalevalue.x, y+16.5*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			else
				DrawTextWithBorder(''..stateW[i].cd, 13*scalevalue.x, x+17*scalevalue.x, y+16.5*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			end
		end
	else
		DrawRectangleAL(x, y+22*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end

	if stateE[i].cd == -1 then
		DrawRectangleAL(x, y+37*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateE[i].cd ~= 0 and stateE[i].cd ~= -1 then
		DrawRectangleAL(x, y+37*scalevalue.y, DrawProgressBar(stateE[i].cd, stateE[i].maxCD, 1), HeightSkills, CDcolor)
		if Menu.appearance.CompleteCDtracker.Efullcd then
			if tonumber(stateE[i].cd) > 9  then
				DrawTextWithBorder(''..stateE[i].cd, 13*scalevalue.x, x+14*scalevalue.x, y+32*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			else
				DrawTextWithBorder(''..stateE[i].cd, 13*scalevalue.x, x+17*scalevalue.x, y+32*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			end
		end
	else
		DrawRectangleAL(x, y+37*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end	

	if stateR[i].cd == -1 then
		DrawRectangleAL(x, y+52*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
	elseif stateR[i].cd ~= 0 and stateR[i].cd ~= -1 then
		DrawRectangleAL(x, y+52*scalevalue.y, DrawProgressBar(stateR[i].cd, stateR[i].maxCD, 1), HeightSkills, CDcolor)
		if Menu.appearance.CompleteCDtracker.Rfullcd then
			if tonumber(stateR[i].cd) > 99 then
				DrawTextWithBorder(''..stateR[i].cd, 13*scalevalue.x, x+12*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			elseif tonumber(stateR[i].cd) > 9 and tonumber(stateR[i].cd) <99  then
				DrawTextWithBorder(''..stateR[i].cd, 13*scalevalue.x, x+14*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			else
				DrawTextWithBorder(''..stateR[i].cd, 13*scalevalue.x, x+17*scalevalue.x, y+46*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
			end
		end
	else
		DrawRectangleAL(x, y+52*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
	end
 
	local SScolor = ARGB(Menu.appearance.colors.sscolor[1], Menu.appearance.colors.sscolor[2], Menu.appearance.colors.sscolor[3], Menu.appearance.colors.sscolor[4])
	for j=1, #SSpells, 1 do
		local spell = SSpells[j]
		if spell.Name == hero:GetSpellData(SUMMONER_1).name then
			spell.sprite:Draw(x,y+60*scalevalue.y,0xFF)
			if stateS1[i].cd ~= 0 then
				DrawRectangleAL(x, y+72*scalevalue.y, DrawProgressBar(stateS1[i].cd, stateS1[i].maxCD, 2), 25*scalevalue.y, SScolor)
				if tonumber(stateS1[i].cd) > 99 then
					DrawTextWithBorder(''..stateS1[i].cd, 13*scalevalue.x, x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				elseif tonumber(stateS1[i].cd) > 9 and tonumber(stateS1[i].cd) <99  then
					DrawTextWithBorder(''..stateS1[i].cd, 13*scalevalue.x, x+3*scalevalue.x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				else
					DrawTextWithBorder(''..stateS1[i].cd, 13*scalevalue.x, x+7*scalevalue.x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				end
			end
		elseif spell.Name == hero:GetSpellData(SUMMONER_2).name then
			spell.sprite:Draw(x+20*scalevalue.x, y+60*scalevalue.y,0xFF)
			if stateS2[i].cd ~= 0 then
				DrawRectangleAL(x+20*scalevalue.x, y+72*scalevalue.y, DrawProgressBar(stateS2[i].cd, stateS2[i].maxCD, 2), 25*scalevalue.y, SScolor)
				if tonumber(stateS2[i].cd) > 99 then
					DrawTextWithBorder(''..stateS2[i].cd, 13*scalevalue.x, x+20*scalevalue.x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				elseif tonumber(stateS2[i].cd) > 9 and tonumber(stateS2[i].cd) <99  then
					DrawTextWithBorder(''..stateS2[i].cd, 13*scalevalue.x, x+23*scalevalue.x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				else
					DrawTextWithBorder(''..stateS2[i].cd, 13*scalevalue.x, x+27*scalevalue.x, y+65*scalevalue.y, ARGB(255,255,255,255), ARGB(255,0,0,0))
				end
			end
		end
	end
end

function DrawProgressBar(currentValue, maxValue, spellorSS)
	local WidthSkills = 38*scalevalue.x
	if spellorSS == 1 then
		return WidthSkills - math.floor(WidthSkills * currentValue) * (1/maxValue)
	else
		return (WidthSkills*0.5)
	end
end

function DrawTextWithBorder(textToDraw, textSize, x, y, textColor, backgroundColor)
	DrawText(textToDraw, textSize, x + 1, y, backgroundColor)
	DrawText(textToDraw, textSize, x - 1, y, backgroundColor)
	DrawText(textToDraw, textSize, x, y - 1, backgroundColor)
	DrawText(textToDraw, textSize, x, y + 1, backgroundColor)
	DrawText(textToDraw, textSize, x , y, textColor)
end

function TowerHPDraw()
	if Menu.towerhp.enabled then
		local TXTcolor = ARGB(Menu.towerhp.appearance.textColor[1], Menu.towerhp.appearance.textColor[2], Menu.towerhp.appearance.textColor[3], Menu.towerhp.appearance.textColor[4])
		local BORDERcolor = ARGB(Menu.towerhp.appearance.borderColor[1], Menu.towerhp.appearance.borderColor[2], Menu.towerhp.appearance.borderColor[3], Menu.towerhp.appearance.borderColor[4])

		for _, tower in pairs(turrets) do
			if tower.object then
				local threeDpos = tower.object.pos
				local minimap = GetMinimap(threeDpos)
				local towerhp = round(tower.object.health) 
				if towerhp ~= 9999 then -- fountain tower
					if Menu.towerhp.drawInMinimap then
						if Menu.towerhp.appearance.drawBorder then
							DrawTextWithBorder(''..towerhp, Menu.towerhp.appearance.textSize, minimap.x, minimap.y, TXTcolor, BORDERcolor)
						else
							DrawText(''..towerhp, Menu.towerhp.appearance.textSize, minimap.x, minimap.y, TXTcolor)
						end
					end

					if Menu.towerhp.drawInMap then
						DrawText3D(''..towerhp, threeDpos.x, threeDpos.y+500, threeDpos.z,25, TXTcolor, true)
					end


				end
			end
		end
	end
end

--'Menu, update and init'

function init()
	if not FileExist(BOL_PATH..'\\Scripts\\Common\\Saves\\ExtraHudPos.save') then
		--PrintChat("hi")
		local UIsave={x=1502*scalevalue.x, y=114*scalevalue.y}
		SavedPos['UI'] = UIsave
	end
	local UI = SavedPos['UI']
	turrets = GetTurrets()
	SetScaleHUD()
	lastPing = GetTickCount()
	LastKnownPing = GetTickCount()
	--UI={x=1502*scalevalue.x, y=114*scalevalue.y}
	IconUI={x=UI.x, y=UI.y}
	PATH = BOL_PATH..'Sprites\\ExtraHud\\'
	if #enemies ~= 0 then	
		HUD = FindSprite(PATH..'HUD1'..#enemies..'.png')
		HUDBG = FindSprite(PATH..'HUDBG1'..#enemies..'.png')
		HUD2 = FindSprite(PATH..'HUD2'..#enemies..'.png')
	else
		HUD = createSprite('empty.dds')
		HUDBG = createSprite('empty.dds')
		HUD2 = createSprite('empty.dds')
	end

	HUD:SetScale(scalevalue.x, scalevalue.y)
	HUDBG:SetScale(scalevalue.x, scalevalue.y)
	HUD2:SetScale(scalevalue.x, scalevalue.y)
	
	if not tostring(Saved['gameid']):find(ID) then
		for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team then
				eTable[i] = 	{x = IconUI.x, y = IconUI.y,													-- Hud
								 CurrentHP = enemy.health, MaxHP = enemy.maxHealth, CurrentMP = enemy.mana, MaxHP = enemy.maxMana, 							-- ChampStats
								 lane = nil, tick_hero = nil, MissTimer = nil,									-- SS timer
								 tick_deathTimer = nil, deathTimer = nil,										-- Death timer
								 plannedPing = false, heroVisible = GetTickCount(), heroPinged = GetTickCount(),-- Awerness 
								 charName = enemy.charName, HMindex = i, alreadyWarnedGult = false				-- For print alert
								} 
				--eTable[i].sprite = FindSprite(PATH .. enemy.charName .. '_Square_0.dds')
				--eTable[i].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)
				IconUI.y = 83*scalevalue.y + IconUI.y
			end
		end
		Saved['gameid'] = ID
		Saved['eTable'] = eTable
	end

	for i = 1, heroManager.iCount, 1 do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			spriteTable[i]= {sprite = nil}
			spriteTable[i].sprite = FindSprite(PATH .. enemy.charName .. '_Square_0.dds')
			spriteTable[i].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)			
		end
	end


	--UI={x=1502*scalevalue.x, y=114*scalevalue.y}
	for i=1, #SSpells, 1 do
		local spell = SSpells[i]
		spell.sprite = FindSprite(PATH..spell.Name..'.dds')
		spell.sprite:SetScale(0.28*scalevalue.x, 0.36*scalevalue.y)
	end
	initDone= true
end

function menu()
	Menu = scriptConfig('Extra Hud', 'extrahud')
	--Awerness config
	Menu:addSubMenu('Awerness', 'awerness')
		Menu.awerness:addParam('enabled', 'Ping incoming Ganks', SCRIPT_PARAM_ONOFF, true)
   		Menu.awerness:addParam('pingToTeam','Ping: ', SCRIPT_PARAM_LIST, 2, { 'Client-Sided', 'Server-Sided'})
	   	Menu.awerness:addParam('pingtype','Ping type: ', SCRIPT_PARAM_LIST, 2, { 'Normal', 'Danger', 'Fallback'})
	   	Menu.awerness:addParam('minRange', 'Minimum Range needed to ping', SCRIPT_PARAM_SLICE, 600, 600, 1500, 0)
	   	Menu.awerness:addParam('maxRange', 'maximum Range to ping', SCRIPT_PARAM_SLICE, 4000, 4000, 10000, 0)
	   	Menu.awerness:addParam('autoDisablePings', 'Automatically disable at minute:', SCRIPT_PARAM_SLICE, 15, 10, 30, 0)
	   	Menu.awerness:addParam('autoEnableAtStart', 'Automatically enable next game', SCRIPT_PARAM_ONOFF, true)

	--Auto SS config
	Menu:addSubMenu('Auto SS/MIA','missing')
		Menu.missing:addParam('enabled', 'Auto SS/MIA', SCRIPT_PARAM_ONOFF, true)
		Menu.missing:addParam('pinglaneonly', 'Ping only from same lane as mine', SCRIPT_PARAM_ONOFF, true)
		Menu.missing:addParam('pingtolaneorchamp','Ping to lane or last known posistion ', SCRIPT_PARAM_LIST, 1, { 'Center of lane', 'Last Known position'})
		Menu.missing:addParam('missingtime', 'Time missing needed to ping', SCRIPT_PARAM_SLICE, 5, 1, 20, 0)
		Menu.missing:addParam('missingchat', 'Send chat ss with last known position', SCRIPT_PARAM_ONOFF, false)
		Menu.missing:addParam('missingtoteam','Chat: ', SCRIPT_PARAM_LIST, 2, { 'Client-Sided', 'Server-Sided'})
		Menu.missing:addParam('autoDisableSS', 'Automatically disable at minute:', SCRIPT_PARAM_SLICE, 15, 10, 30, 0)
		Menu.missing:addParam('autoEnableAtStart', 'Automatically enable next game', SCRIPT_PARAM_ONOFF, true)

	--Ping last known position
	Menu:addSubMenu('Ping Last Known Location', 'pinglastknown')
		Menu.pinglastknown:addParam('clientorserver','Ping: ', SCRIPT_PARAM_LIST, 2, { 'Client-Sided', 'Server-Sided'})
		Menu.pinglastknown:addParam('pingtype','Ping type: ', SCRIPT_PARAM_LIST, 1, { 'Normal', 'Danger', 'Missing', 'On my way','Fallback','Assistance'})



	--Tower HP in minimap
	Menu:addSubMenu('Tower Health', 'towerhp')
		Menu.towerhp:addParam('enabled', 'Enable/Disable', SCRIPT_PARAM_ONOFF, true)
		Menu.towerhp:addParam('drawInMinimap', 'Draw tower health in Minimap', SCRIPT_PARAM_ONOFF, true)
		Menu.towerhp:addParam('drawInMap', 'Draw tower health in Map', SCRIPT_PARAM_ONOFF, true)
		Menu.towerhp:addSubMenu('Appearance', 'appearance')
			Menu.towerhp.appearance:addParam('drawBorder', 'Draw border arround text', SCRIPT_PARAM_ONOFF, true)
			Menu.towerhp.appearance:addParam('textColor', 'Text color', SCRIPT_PARAM_COLOR, {255, 255, 255, 255})
			Menu.towerhp.appearance:addParam('borderColor', 'Cooldown color', SCRIPT_PARAM_COLOR, {255, 0, 0, 0})
			Menu.towerhp.appearance:addParam('textSize', 'Minimap text size', SCRIPT_PARAM_SLICE, 12, 5, 20, 0)


	--Appearance
	Menu:addSubMenu('Appearance', 'appearance')
		Menu.appearance:addSubMenu('Colors', 'colors')
			Menu.appearance.colors:addParam('cdcolor', 'Cooldown color', SCRIPT_PARAM_COLOR, {255, 214, 114, 0})--orange
			Menu.appearance.colors:addParam('readycolor', 'Ready color', SCRIPT_PARAM_COLOR, {255,0, 175, 0})--green
			Menu.appearance.colors:addParam('sscolor', 'Summoner Spells cooldown color', SCRIPT_PARAM_COLOR, {150, 0, 0, 0})--black light
		Menu.appearance:addSubMenu('Champions to track', 'champtrack')
			for i = 1, heroManager.iCount, 1 do
				local enemy = heroManager:getHero(i)
				if enemy.team ~= myHero.team then
					Menu.appearance.champtrack:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
					Menu.appearance.champtrack[enemy.charName] = true -- for all to be activated in case a user disables one and it continues disabled next game
				end
			end
		Menu.appearance:addSubMenu('Complete cooldown tracker', 'CompleteCDtracker')
			Menu.appearance.CompleteCDtracker:addParam('Qfullcd', 'Always show Q Cooldown', SCRIPT_PARAM_ONOFF, true)
			Menu.appearance.CompleteCDtracker:addParam('Wfullcd', 'Always show W Cooldown', SCRIPT_PARAM_ONOFF, true)
			Menu.appearance.CompleteCDtracker:addParam('Efullcd', 'Always show E Cooldown', SCRIPT_PARAM_ONOFF, true)
			Menu.appearance.CompleteCDtracker:addParam('Rfullcd', 'Always show R Cooldown', SCRIPT_PARAM_ONOFF, true)

		Menu.appearance:addSubMenu('Global ult notify', 'globalNotifye')	
			Menu.appearance.globalNotifye:addParam('onReady', '... has ultimate ready', SCRIPT_PARAM_COLOR, {255,50,255,50})
			Menu.appearance.globalNotifye:addParam('onUse', '... used ultimate', SCRIPT_PARAM_COLOR, {255,50,255,50})


		Menu.appearance:addParam('HPcolor', 'Dynamic Health colors', SCRIPT_PARAM_ONOFF, false)
		Menu.appearance:addParam('skin','Skin type: ', SCRIPT_PARAM_LIST, 1, {'Metro style UI' ,'League of Legends style' ,'None' ,'Disable'})
		Menu.appearance:addParam('extrahudcolor', 'Extra Hud Chat color(Reload required)', SCRIPT_PARAM_LIST,2 , {'Blue', 'Green'})
		Menu.appearance:addParam('scalebyuser', 'Scale(%)', SCRIPT_PARAM_SLICE, 100, 1, 100, 0)
		Menu.appearance:addParam('unlock', 'Move UI', SCRIPT_PARAM_ONKEYDOWN,false, 16)
	--Experimental
	Menu:addSubMenu('Experimental things', 'experimental')
		Menu.experimental:addParam('AdvSS','Extra SS logic(Might be buggy)',SCRIPT_PARAM_ONOFF, true)
		Menu.experimental:addParam('CustomDT', 'Custom Death Timer(Summoners rift only', SCRIPT_PARAM_ONOFF, true)
		Menu.experimental:addParam('FowHPMPregen','Update enemy HP and MP in FOW',SCRIPT_PARAM_ONOFF, false)
		Menu.experimental:addParam('refreshRate', 'Refresh Rate', SCRIPT_PARAM_SLICE, 200, 200, 1000, 0)
	--Awesome ping folower!
	Menu:addSubMenu('Overly Attched Ping(for the lulz)', 'ping')
		Menu.ping:addParam('ping', ' Overly Attched Ping', SCRIPT_PARAM_ONOFF, false)
		Menu.ping:addParam('random', 'Ping type random', SCRIPT_PARAM_ONOFF, false)
		Menu.ping:addParam('pingtype', 'Ping type', SCRIPT_PARAM_SLICE, 3, 1, 6, 0)

	Menu.appearance.unlock = false

	if Menu.awerness.autoEnableAtStart and not Menu.awerness.enabled then
		Menu.awerness.enabled = true
	end
	if Menu.missing.autoEnableAtStart and not Menu.missing.enabled then
		Menu.missing.enabled = true
	end
end


function CheckUpdates()
	if _G.ExtraHudUpdateEnabled then
		GetAsyncWebResult(UPDATE_HOST, UPDATE_PATH, function(d) ServerData = d end)
		if FileExist(PATH.."ExtraHudSpriteVersion.txt") then
			local SpriteVersion = readAll(PATH.."ExtraHudSpriteVersion.txt")
			if _G.ExtraHudSpriteVersion > SpriteVersion then
				PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Please Download latest sprites from thread</font>')
			else
				PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>You have got latest sprites version: <b>'.._G.ExtraHudSpriteVersion..'</b></font>')
			end
		else
			PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>Please Download latest sprites from thread</font>')
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
			DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () PrintChat("<font colors=\"#"..Chatcolor.."\">".."<b>Extra Hud: </b><font color=\"#F8F8F8\"> You successfully updated. Please reload script(double F9). (".._G.ExtraHudVersion.." => "..ServerVersion..")</font>") end)     
		elseif ServerVersion then
			PrintChat('<font color=\'#'..Chatcolor..'\'>'..'<b>Extra Hud: </b><font color=\'#F8F8F8\'>You have got the latest version: <b>'.._G.ExtraHudVersion..'</b></font>')
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