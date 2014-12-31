<?php exit() ?>--by UglyOldGuy 86.127.152.238
--Encrypt this line and below
local UPDATE_SCRIPT_NAME = "ExtraHud"
local UPDATE_HOST = "bitbucket.org"
local UPDATE_PATH = "/andreluis034/andrerepo/raw/master/extrahud.lua"
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

local ServerData
if autoupdateenabled then
	GetAsyncWebResult(UPDATE_HOST, UPDATE_PATH, function(d) ServerData = d end)
	function update()
		if ServerData ~= nil then
			local ServerVersion
			local send, tmp, sstart = nil, string.find(ServerData, "local version = \"")
			if sstart then
				send, tmp = string.find(ServerData, "\"", sstart+1)
			end
			if send then
				ServerVersion = tonumber(string.sub(ServerData, sstart+1, send-1))
			end

			if ServerVersion ~= nil and tonumber(ServerVersion) ~= nil and tonumber(ServerVersion) > tonumber(version) then
				DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () print("<font color=\"#00FF00\"><b>"..UPDATE_SCRIPT_NAME..":</b> successfully updated. Reload (double F9) Please. ("..version.." => "..ServerVersion..")</font>") end)     
			elseif ServerVersion then
				print("<font color=\"#00FF00\"><b>"..UPDATE_SCRIPT_NAME..":</b> You have got the latest version: <u><b>"..ServerVersion.."</b></u></font>")
			end		
			ServerData = nil
		end
	end
	AddTickCallback(update)
end

require "MapPosition"


local autoDisableTime      = 1500  
local heroVisibleTimeout   = 500   
local heroPingTimeout      = 10000 
local pingTimeout          = 3000 
local heroChaseRange       = 600   
local heroVisibleThreshold = 100   

local herosVisible = {}
local herosPinged  = {}
local plannedPings = {}
local lastPing     = nil
local mapPosition  = nil

local EnergyChamps	= { "Akali",		"Kennen",	"LeeSin",		"Shen",	"Zed" }
local FuryChamps	= { "Renekton",		"Shyvana",	"Tryndamere",	"Aatrox"	  } -- Aatrox not really Fury but it is red so wtvr
local OtherChamps	= { "Mordekaiser",	"Yasuo"									  } -- To be added
local HeatChamps	= { "Rumble"												  } -- Fuck you too rito
local SSpells = {		{Name="SummonerFlash",          sprite = nil },
						{Name="SummonerHaste",          sprite = nil },
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
--local TrackSpells = {_Q, _W, _E, _R, SUMMONER_1, SUMMONER_2}
local stateQ,stateW,stateE,stateR,stateS1,stateS2 = {},{},{},{},{},{}
local tick_heroes = {}
local MissTimer = {}
local deathTimer = {}
local tick_deathTimer = {}
local TickLimit = 0
local initDone = false
local x = 0
local y = 0
local firsttick = false
local lastPing	 = nil
local scalevalue = {x = WINDOW_W * (1/1600), y = WINDOW_H * (1/900)}
    
function OnLoad()
	menu()
	init()
	print("Extra Hud Loaded")
	mapPosition = MapPosition()
end

function OnTick()
	if os.clock() - TickLimit > 1 then
		TickLimit = os.clock()
		for i=1, heroManager.iCount, 1 do
			local hero = heroManager:getHero(i)
			if hero.team ~= myHero.team then

				if hero.visible then
					enemyTable[hero.charName].lane = GetLane(hero)
				end

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
				enemyTable[hero.charName].CurrentHP = hero.health
				enemyTable[hero.charName].MaxHP = hero.maxHealth
				enemyTable[hero.charName].CurrentMP = hero.mana
				enemyTable[hero.charName].MaxMP = hero.maxMana
--				--CD tracker
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
				--CD tracker off

--	   		--SS and Death Timers Start
				if not hero.visible and not hero.dead then
					if tick_heroes[i] == nil then
						tick_heroes[i] = GetTickCount()
					end
					MissTimer[i] = GetTickCount() - tick_heroes[i]
				else
					tick_heroes[i] = nil
					MissTimer[i] = nil
				end

				if hero.dead then
					if tick_deathTimer[i] == nil then
						tick_deathTimer[i] = GetTickCount()
					end
					deathTimer[i] = math.ceil(hero.deathTimer - ((GetTickCount() - tick_deathTimer[i])*0.001))
				else
					tick_deathTimer[i] = nil
				end
				--SS and Death Timers End

-- 				-- Auto SS start
				if Menu.missing.enabled and MissTimer[i] ~= nil and MissTimer[i] >= Menu.missing.missingtime*1000 and MissTimer[i] < Menu.missing.missingtime*1000 + 1000 and GetTickCount() >= 120000 then
					if Menu.missing.pinglane and enemyTable[hero.charName].lane ~= nil and enemyTable[hero.charName].lane == GetLane(myHero) then
						PingSignal(3, hero.x, hero.y, hero.z, 2)
						if Menu.missing.missingchat then
							if Menu.missingchat.missingtoteam == 2 then
								SendChat("ss "..enemyTable[hero.charName].lane.."("..hero.charName..")")
							else
								print("ss "..enemyTable[hero.charName].lane.."("..hero.charName..")")
							end
						end
					elseif not Menu.missing.pinglane then
						PingSignal(3, hero.x, hero.y, hero.z, 2)
						if Menu.missing.missingchat then
							if Menu.missingchat.missingtoteam == 2 then
								SendChat("ss "..enemyTable[hero.charName].lane.."("..hero.charName..")")
							else
								print("ss "..enemyTable[hero.charName].lane.."("..hero.charName..")")
							end
						end
					end
				end

-- 				--Auto ping gank
				if Menu.awerness.enabled then
					if plannedPings[i] then
						herosVisible[i] = GetTickCount()
						if hero ~= nil and hero.visible and hero.team ~= myHero.team then
							if GetTickCount() - plannedPings[i] >= heroVisibleThreshold then
								if Menu.awerness.pingToTeam == 2 then
									PingSignal(Menu.awerness.pingtype, hero.x,hero.y, hero.z, 1)
								else
									if VIP_USER then
										Packet("R_PING",  {x = hero.x, y = hero.z,type = PING_FALLBACK}):receive()
									else
										PingSignal(Menu.awerness.pingtype, hero.x,hero.y, hero.z, 0)
									end
								end

								lastPing = GetTickCount()
								herosPinged[i] = GetTickCount()
								purgePlannedPings()
							end
						else
							plannedPings[i] = false
						end
					else
						if hero ~= nil and hero.visible and hero.team ~= myHero.team then
							if GetTickCount() - herosVisible[i] >= heroVisibleTimeout and GetTickCount() - herosPinged[i] >= heroPingTimeout and GetTickCount() - lastPing >= pingTimeout and (MapPosition:inInnerJungle(hero) or MapPosition:inInnerRiver(hero)) and not EnemyHeroInRange(hero, heroChaseRange) and EnemyHeroInRange(hero, 4000) then
								plannedPings[i] = GetTickCount()
							end
								herosVisible[i] = GetTickCount()
						end
					end
				end
			end
		end
		firsttick = true
	end
	if Menu.ping.ping and not myHero.dead then
	    if Menu.ping.random then 
	        PingSignal(math.random(1,6), myHero.x, myHero.y, myHero.z, 0)
	    else
		    PingSignal(Menu.ping.pingtype, myHero.x, myHero.y, myHero.z, 0)
		end
	end
	--print(GetTickCount())
end

function OnDraw()
	if initDone and firsttick then
		HUDBG:Draw(0,0, 0xFF)
		for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team then
--		   --Icon draw
				enemyTable[enemy.charName].sprite:Draw(enemyTable[enemy.charName].x , enemyTable[enemy.charName].y, 0xFF) 
				DrawRectangleAL(enemyTable[enemy.charName].x+40*scalevalue.x, enemyTable[enemy.charName].y+52*scalevalue.y , 20*scalevalue.x , 15*scalevalue.y, ARGB(255,0,0,0))
				DrawText(""..enemy.level,12*scalevalue.x,enemyTable[enemy.charName].x+46*scalevalue.x, enemyTable[enemy.charName].y+46*scalevalue.y, ARGB(255,255,255,255))
--			 -- HP BAR AND MP start
				DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 66*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentHP / enemyTable[enemy.charName].MaxHP))*scalevalue.x , 12*scalevalue.y, ARGB(255,0,175,0))
				if valueExists(EnergyChamps, enemy.charName) then
					DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,255,204,51))
				elseif valueExists(FuryChamps, enemy.charName) then
					if enemy.charName == "Shyvana" and enemyTable[enemy.charName].CurrentMP ~= enemyTable[enemy.charName].MaxMP then
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,255,153,102))
					elseif enemy.charName == "Renekton" and enemyTable[enemy.charName].CurrentMP < 50 then
							DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,128,128,128))
					else
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,255,0,0))
					end
				elseif valueExists(OtherChamps, enemy.charName) then -- Yasuo/morde logic
					if enemyTable[enemy.charName].CurrentMP == enemyTable[enemy.charName].MaxMP then
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,255,255,255))
					else
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,128,128,128))
					end
				elseif valueExists(HeatChamps, enemy.charName) then -- Rumble logic
					if enemyTable[enemy.charName].CurrentMP < 50 then
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,128,128,128))
					elseif enemyTable[enemy.charName].CurrentMP >= 50 and enemyTable[enemy.charName].CurrentMP <= 99 then
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,232,232,0))
					else
						DrawRectangleAL(enemyTable[enemy.charName].x+1*scalevalue.x, enemyTable[enemy.charName].y + 78*scalevalue.y, (59 * (enemyTable[enemy.charName].CurrentMP / enemyTable[enemy.charName].MaxMP))*scalevalue.x  , 12*scalevalue.y, ARGB(255,255,0,0))
					end
				end
				-- HP BAR AND MP end

--			 --SS and Death Timers Start
				if not enemy.visible and MissTimer[i] ~= nil and not enemy.dead then
					MissTimer_minute = math.floor(MissTimer[i] * (1 / 60000))
					MissTimer_second =  math.floor((MissTimer[i] % 60000)*0.001)
					DrawRectangleAL(enemyTable[enemy.charName].x, enemyTable[enemy.charName].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,0,0,0))
					DrawText("?",50*scalevalue.x, enemyTable[enemy.charName].x+17*scalevalue.x, enemyTable[enemy.charName].y+5*scalevalue.y, ARGB(255, 255, 255, 255))
					DrawText(string.format("%02d:%02d", MissTimer_minute, MissTimer_second),15*scalevalue.x, enemyTable[enemy.charName].x+15*scalevalue.x, enemyTable[enemy.charName].y+45*scalevalue.y, ARGB(255, 255,255,255))
				end
				if enemy.dead and deathTimer[i] ~= nil then
					DrawRectangleAL(enemyTable[enemy.charName].x, enemyTable[enemy.charName].y+31*scalevalue.y, 60*scalevalue.x, 60*scalevalue.y, ARGB(150,20,0,0))
					if deathTimer[i] < 10 then
						DrawText(""..deathTimer[i], 50*scalevalue.x,enemyTable[enemy.charName].x+17*scalevalue.x, enemyTable[enemy.charName].y+5*scalevalue.y, ARGB(255,255,255,255))
					else
						DrawText(""..deathTimer[i], 50*scalevalue.x,enemyTable[enemy.charName].x+8*scalevalue.x, enemyTable[enemy.charName].y+5*scalevalue.y, ARGB(255,255,255,255))
					end
				end

--			 --Cooldown Tracker UI
				WidthSkills = 38*scalevalue.x
				HeightSkills = 14.95*scalevalue.y
				local CDcolor = ARGB(Menu.Colors.cdcolor[1], Menu.Colors.cdcolor[2], Menu.Colors.cdcolor[3], Menu.Colors.cdcolor[4])
				local RDYcolor = ARGB(Menu.Colors.readycolor[1], Menu.Colors.readycolor[2], Menu.Colors.readycolor[3], Menu.Colors.readycolor[4])
				if stateQ[i].cd == -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+7*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148)) 
				elseif stateQ[i].cd ~= 0 and stateQ[i].cd ~= -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+7*scalevalue.y, WidthSkills - math.floor(WidthSkills * stateQ[i].cd) / stateQ[i].maxCD, HeightSkills, CDcolor)
				else
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+7*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
				end

				if stateW[i].cd == -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+22*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
				elseif stateW[i].cd ~= 0 and stateW[i].cd ~= -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+22*scalevalue.y, WidthSkills - math.floor(WidthSkills * stateW[i].cd) / stateW[i].maxCD, HeightSkills, CDcolor)
				else
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+22*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
				end

				if stateE[i].cd == -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+37*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
				elseif stateE[i].cd ~= 0 and stateE[i].cd ~= -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+37*scalevalue.y, WidthSkills - math.floor(WidthSkills * stateE[i].cd) / stateE[i].maxCD, HeightSkills, CDcolor)
				else
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+37*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
				end	

				if stateR[i].cd == -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+52*scalevalue.y, WidthSkills, HeightSkills, ARGB(255,148,148,148))
				elseif stateR[i].cd ~= 0 and stateR[i].cd ~= -1 then
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+52*scalevalue.y, WidthSkills - math.floor(WidthSkills * stateR[i].cd) / stateR[i].maxCD, HeightSkills, CDcolor)
				else
					DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+52*scalevalue.y, WidthSkills, HeightSkills, RDYcolor)
				end
 
				local SScolor = ARGB(Menu.Colors.sscolor[1], Menu.Colors.sscolor[2], Menu.Colors.sscolor[3], Menu.Colors.sscolor[4])
				for j, spell in pairs(SSpells) do
					if spell.Name == enemy:GetSpellData(SUMMONER_1).name then
						spell.sprite:Draw(enemyTable[enemy.charName].x+60*scalevalue.x,enemyTable[enemy.charName].y+60*scalevalue.y,0xFF)
						if stateS1[i].cd ~= 0 then
							DrawRectangleAL(enemyTable[enemy.charName].x+60*scalevalue.x, enemyTable[enemy.charName].y+72*scalevalue.y, (WidthSkills*0.5) - math.floor((WidthSkills*0.5) * stateS1[i].cd) / stateS1[i].maxCD, 25*scalevalue.y, SScolor)
						end
					elseif spell.Name == enemy:GetSpellData(SUMMONER_2).name then
						spell.sprite:Draw(enemyTable[enemy.charName].x+80*scalevalue.x,enemyTable[enemy.charName].y+60*scalevalue.y,0xFF)
						if stateS2[i].cd ~= 0 then
							DrawRectangleAL(enemyTable[enemy.charName].x+80*scalevalue.x, enemyTable[enemy.charName].y+72*scalevalue.y, (WidthSkills*0.5) - math.floor((WidthSkills*0.5) * stateS2[i].cd) / stateS2[i].maxCD, 25*scalevalue.y, SScolor)
						end
					end
				end
				-- Cooldown Tracker
			end
		end
		HUD:Draw(0,0, 0xFF)
	end
end

function FindSprite(file)
	assert(type(file) == "string", "GetSprite: wrong argument types (<string> expected for file)")
	if FileExist(file) == true then
		--print("Sprite available")
		return createSprite(file)
	else
		PrintChat(file .. " not found.".."<font color=\"#00FF00\">".." Please Download latest sprites from thread")
		return createSprite("empty.dds")
	end
end

function init()
	lastPing = GetTickCount()
	x=1502*scalevalue.x
	y=114*scalevalue.y
	local PATH = BOL_PATH.."Sprites\\ExtraHud\\"
	HUD = FindSprite(PATH.."HUD.dds")
	HUDBG = FindSprite(PATH.."HUDBG.png")
	HUD:SetScale(scalevalue.x, scalevalue.y)
	HUDBG:SetScale(scalevalue.x, scalevalue.y)
	enemyTable = {}
	for i = 1, heroManager.iCount, 1 do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			enemyTable[enemy.charName] = {sprite = nil, x = 0, y = 0, CurrentHP = 1, MaxHP = 2, CurrentMP = 1, MaxHP = 2, lane = nil} 
			enemyTable[enemy.charName].sprite = FindSprite(PATH .. enemy.charName .. '_Square_0.dds')
			enemyTable[enemy.charName].sprite:SetScale(0.5*scalevalue.x, 0.5*scalevalue.y)
			enemyTable[enemy.charName].x = x
			enemyTable[enemy.charName].y = y
			y = y + 83*scalevalue.y
			herosVisible[i] = GetTickCount()
			herosPinged[i]  = GetTickCount()
			plannedPings[i] = false
		end
	end
	for i, spell in ipairs(SSpells) do
		spell.sprite = FindSprite(PATH..spell.Name..".png")
		spell.sprite:SetScale(0.3*scalevalue.x, 0.35*scalevalue.y)
	end
	mapPosition = MapPosition()
	initDone= true
end

function DrawRectangleAL(x, y, w, h, color)
	local Points = {}
	Points[1] = D3DXVECTOR2(math.floor(x), math.floor(y))
	Points[2] = D3DXVECTOR2(math.floor(x + w), math.floor(y))
	DrawLines2(Points, math.floor(h), color)
end

function valueExists(tbl, value)
	for k,v in pairs(tbl) do
		if value == v then
			return true
		end
	end
	return false
end

function menu()
	Menu = scriptConfig("Extra Hud", "extrahud")
	--Awerness config
	Menu:addSubMenu("Awerness", "awerness")
	Menu.awerness:addParam("enabled", "Ping incoming Ganks", SCRIPT_PARAM_ONOFF, true)
   	Menu.awerness:addParam("pingToTeam","Ping: ", SCRIPT_PARAM_LIST, 2, { "Client-Sided", "Server-Sided"})
   	Menu.awerness:addParam("pingtype","Ping type: ", SCRIPT_PARAM_LIST, 2, { "Normal", "Danger", "Fallback"})

	--Auto SS config
	Menu:addSubMenu("Auto SS/MIA","missing")
	Menu.missing:addParam("enabled", "Auto SS/MIA", SCRIPT_PARAM_ONOFF, true)
	Menu.missing:addParam("pinglane", "Ping only from same lane", SCRIPT_PARAM_ONOFF, true)
	Menu.missing:addParam("missingtime", "Time missing needed to ping", SCRIPT_PARAM_SLICE, 5, 1, 20, 0)
	Menu.missing:addParam("missingchat", "Send chat ss with last known position", SCRIPT_PARAM_ONOFF, false)
	Menu.missing:addParam("missingtoteam","Chat: ", SCRIPT_PARAM_LIST, 2, { "Client-Sided", "Server-Sided"})	

	--select the colors
	Menu:addSubMenu("Spell Colors", "Colors")
	Menu.Colors:addParam("cdcolor", "Cooldown color", SCRIPT_PARAM_COLOR, {255, 214, 114, 0})--orange
	Menu.Colors:addParam("sscolor", "Summoner Spells cooldown color", SCRIPT_PARAM_COLOR, {150, 0, 0, 0})--black light
	Menu.Colors:addParam("readycolor", "Ready color", SCRIPT_PARAM_COLOR, {255,0, 175, 0})--green

	--Awesome ping folower!
	Menu:addSubMenu("Overly Attched Ping(for the lulz)", "ping")
	Menu.ping:addParam("ping", " Overly Attched Ping", SCRIPT_PARAM_ONOFF, false)
	Menu.ping:addParam("random", "Ping type random", SCRIPT_PARAM_ONOFF, false)
	Menu.ping:addParam("pingtype", "Ping type", SCRIPT_PARAM_SLICE, 3, 1, 6, 0)

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


function EnemyHeroInRange(hero, range)
	for j=1, heroManager.iCount, 1 do
		hero1 = heroManager:getHero(j)
		if hero1.team ~= hero.team and GetDistance(hero, hero1) <= range then
			return true
		end
	end

	return false
end

function purgePlannedPings()
	for i=1, heroManager.iCount, 1 do
		plannedPings[i] = false
	end
end