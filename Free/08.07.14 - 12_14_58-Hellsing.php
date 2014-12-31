<?php exit() ?>--by Hellsing 91.97.240.118
--[[
Script Name: Neo's Little Helper
Author: Neo
Version: 0.81
Revision Date: July 8, 2014
Purpose: Overhead hud-integrated cooldown tracker and general utility
--------------------------------------------------------------
@Future features planned: 
	- Suggestions ?
--------------------------------------------------------------
Version 0.81
						- Fixed free users issues, everything should be
							working fine now
--------------------------------------------------------------
Version 0.8
						- HUD WAS UPDATED IN THIS VERSION, PLEASE DOWNLOAD
							NEWEST SPRITES
						- Modified side-hud, making the default size larger
						- Added values to the health and mana/energy bars
						- Some minor fixes
--------------------------------------------------------------
Version 0.72
						- Fixed creepy hud issue
---------------------------------------------------------------						
Version 0.71
						- Some minor fixes
						- Added alternate version for who's still on patch 4.10
---------------------------------------------------------------
Version 0.7 
						- Changed whole sprites structure, please redownload
							sprites, delete old ones, and replace with new
						- Added fully scalable and moveable side-hud
--------------------------------------------------------------						
Version 0.6
						- Fixed bugsplat on double F9
						- Added sprites soft reset (Default: "L" key)
						- Improved drawing visuals
						- Some minor fixes
						- Updated sprites: please redownload sprites and 
							copy them over the old ones							

--------------------------------------------------------------
Version 0.5 
						- Created github repo
						- Added autoupdate
						- Added minimap recall warning ping
						- Added minimap text
--------------------------------------------------------------
Version 0.4
						- Added 'fog of war' recall exploit warning for VIP's
--------------------------------------------------------------
Version 0.3
						- Added team cooldowns
						- Improved hud some more
						- Cleaned up the code a bit
--------------------------------------------------------------
Version 0.21
						- Fixed SUMMONER_2 position issue
						- Improved timer positions
--------------------------------------------------------------
Version 0.2 
						- Added hud-integraded levels for each skill
						- Improved hud visuals
						- Improved cooldown drawing accuracy
--------------------------------------------------------------
Version 0.1 
						- Initial release
--------------------------------------------------------------
]]

local version = "0.81"
local AUTOUPDATE = true
local UPDATE_NAME = "Neo\'s Little Helper"
local UPDATE_HOST = "github.com"
local UPDATE_PATH = "/frneo/BoL/raw/master/Scripts/NeosLittleHelper.lua".."?rand="..tostring(math.random(1,10000))
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>"..UPDATE_NAME..":</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end

if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "Version: %d+.%d+")
        ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(version) < ServerVersion then
                AutoupdaterMsg("New version available: "..ServerVersion)
                AutoupdaterMsg("Updating, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
                AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
								--AutoupdaterMsg("You have got the latest version ("..version..")")
            end
        end
    else
        AutoupdaterMsg("Error downloading version info")
    end
end

local scale --= 0.01 * config.sidecooldown.scale
local basepos --= { x = 500 / scale, y = 100 / scale }
local dragging = false
local mousedown = false
local TickLimit = 0
local DrawLimit = 0
local enemyList
local friendList
local sumsprite1
local sumsprite2
local loaded = false
local loaded2 = false
local TrackSpells = {_Q, _W, _E, _R, SUMMONER_1, SUMMONER_2}
local championList = 
										{
											nomana = 
															{
																"Aatrox", "DrMundo", "Garen", "Katarina", "Mordekaiser",
																"Vladimir", "Rengar", "Riven", "Rumble", "Shyvana","Zac",
																 "Yasuo", "Tryndamere"
															},
											energy =
															{
																"Akali", "Kennen", "LeeSin", "Shen", "Renekton", "Zed"
															}
										}
local SSpells = {			  
						{CName="flash", Name="SummonerFlash" },
						{CName="ghost", Name="SummonerHaste" },
						{CName="ignite", Name="SummonerDot"},
						{CName="barrier", Name="SummonerBarrier"},
						{CName="smite", Name="SummonerSmite"},
						{CName="exhaust", Name="SummonerExhaust"},
						{CName="heal", Name="SummonerHeal"},
						{CName="teleport", Name="SummonerTeleport"},
						{CName="cleanse", Name="SummonerBoost"},
						{CName="clarity", Name="SummonerMana"},
						{CName="clair", Name="SummonerClairvoyance"},
						{CName="revive", Name="SummonerRevive"},
						{CName="garrison", Name="SummonerOdinGarrison"},
						}
local SpellsData = {}
local sprites = {}



function OnLoad()
	local SPRITES_PATH = BOL_PATH.."Sprites\\"
	
	Init()
	local loadedTable, error = Serialization.loadTable(SCRIPT_PATH .. 'Common/NeosLittleHelper.lua')
	if not error then
		scale = loadedTable.scale
		basepos = loadedTable.basepos
		config.sidecooldown.scale = scale * 100
	else
		scale = 0.01 * config.sidecooldown.scale
		basepos = { x = 500 / scale, y = 100 / scale }
	end
	PrintChat("<font color='#01A9DB'>Neo's little helper</font>")
end

function LoadMenu()
	config = scriptConfig("Neo's Little Helper", "NeosLittleHelper")
		config:addSubMenu("Overhead HUD", "cooldown")
			config.cooldown:addParam("enemyon", "Show enemies cooldown", SCRIPT_PARAM_ONOFF, true)
			config.cooldown:addParam("friendon", "Show team cooldown", SCRIPT_PARAM_ONOFF, true)
		config:addSubMenu("Side HUD", "sidecooldown")
			config.sidecooldown:addParam("enemyon", "Show enemies HUD", SCRIPT_PARAM_ONOFF, true)
			config.sidecooldown:addParam("scale", "Set hud scale", SCRIPT_PARAM_SLICE, 100, 12, 100, 0)		
			--config.sidecooldown:addParam("friendon", "Show team cooldown", SCRIPT_PARAM_ONOFF, true)
		config:addSubMenu("FoW recall exploit (VIP)", "recall")
			if VIP_USER then
				config.recall:addParam("printon", "Print in chat", SCRIPT_PARAM_ONOFF, true)
				config.recall:addParam("pingon", "Ping (locally)", SCRIPT_PARAM_ONOFF, true)
				config.recall:addParam("minimapon", "Write on minimap", SCRIPT_PARAM_ONOFF, true)
			else
				config.recall:addParam("info", "Sorry, this is for VIP's only", SCRIPT_PARAM_INFO, "")
			end
		config:addSubMenu("Soft reset sprites","reset")
			config.reset:addParam("reset", "Soft reset sprites", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("L"))
		
end

function LoadSprites()
		LoadEnemies()
		sprites.main_hud = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\HUD\\main_enemy.tga')
		sprites.button_green = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\HUD\\button_green.tga')
		sprites.button_red = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\HUD\\button_red.tga')
		sprites.spell_level = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\HUD\\spell_level.tga')
		sprites.minimap_pink = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Minimap\\pink.tga')
		sprites.minimap_ward = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Minimap\\ward.tga')
		sprites.minimap_recall = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Minimap\\recall.tga')
		sprites.background = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Champions\\Background.png')
		sprites.SSbackground = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\LargeSS\\Background.png')
		sprites.drag = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\drag.png')
		sprites.frame = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\frame.png')
		sprites.frame_button_red = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\button_red.png')
		sprites.frame_button_green = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\button_green.png')
		sprites.frame_button_gray = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\button_gray.png')
		--sprites.numbers= FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\numbersheet.png')
		sprites.health = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\health.tga')
		sprites.mana = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\mana.tga')
		sprites.energy = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\energy.tga')
		sprites.white_energy = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Frames\\white_energy.tga')
		
end

function Init()
		LoadMenu()
		LoadSprites()
end

function DrawTextWithBorder(textToDraw, textSize, x, y, textColor, backgroundColor)
	DrawText(textToDraw, textSize, x + 1, y, backgroundColor)
	DrawText(textToDraw, textSize, x - 1, y, backgroundColor)
	DrawText(textToDraw, textSize, x, y - 1, backgroundColor)
	DrawText(textToDraw, textSize, x, y + 1, backgroundColor)
	DrawText(textToDraw, textSize, x , y, textColor)
end

function LoadEnemies()
  enemyList = {}
	friendList = {}
  local ii
  local sname
  local sname2
  if heroManager.iCount > 0 then
    for i = 1, heroManager.iCount, 1 do     
      local enemy = heroManager:getHero(i)
      ii = enemy.charName
      if enemy.team ~= myHero.team then
        enemyList[ii] = { name = enemy.charName, ID = enemy.networkID, sprite1, sprite2, sprite1_large, sprite2_large, sprite_champ, recalling = false, lastrecall = 0, recalltick = 0 }
        sname = enemy:GetSpellData(SUMMONER_1).name
        sname2 = enemy:GetSpellData(SUMMONER_2).name
				enemyList[ii].sprite_champ = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Champions\\'..enemy.charName..'.png')
        for _, ss in pairs(SSpells) do
          if (sname == ss.Name) then
            enemyList[ii].sprite1 = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\SS\\'..ss.CName..'.tga')   
											enemyList[ii].sprite1_large = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\LargeSS\\'..ss.CName..'.png')   
          end 
          if (sname2 == ss.Name) then
            enemyList[ii].sprite2 = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\SS\\'..ss.CName..'.tga')
						enemyList[ii].sprite2_large = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\LargeSS\\'..ss.CName..'.png')
          end 
        end
      else
        if enemy ~= myHero then					
					ii = ii .. "_team"
          friendList[ii] = { name = enemy.charName, sprite1, sprite2, sprite1_large, sprite2_large, sprite_champ, recalling = true }
					friendList[ii].sprite_champ = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\Champions\\'..enemy.charName..'.png')
          sname = enemy:GetSpellData(SUMMONER_1).name
          sname2 = enemy:GetSpellData(SUMMONER_2).name
          for _, ss in pairs(SSpells) do
            if (sname == ss.Name) then
							friendList[ii].sprite1 = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\SS\\'..ss.CName..'.tga')  
							friendList[ii].sprite1_large = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\LargeSS\\'..ss.CName..'.png')    
            end 
            if (sname2 == ss.Name) then
              friendList[ii].sprite2 = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\SS\\'..ss.CName..'.tga')
							friendList[ii].sprite2_large = FindSprite(SPRITE_PATH .. 'NeosLittleHelper\\LargeSS\\'..ss.CName..'.png')
						end 
					end
				end
			end
		end
		loaded = true
	end
end

function DrawSkillLevel(skill, level,barposX,barposY)
	local q_x, q_y = 25, 37
	if skill == 1 then
		if level > 4 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 4), barposY + q_y, 0xFF)
		end
		if level > 3 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 3), barposY + q_y, 0xFF)
		end
		if level > 2 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 2), barposY + q_y, 0xFF)
		end
		if level > 1 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 1), barposY + q_y, 0xFF)
		end
		if level > 0 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 0), barposY + q_y, 0xFF)
		end
	end
	if skill == 2 then
		q_x = q_x + 17
		if level > 4 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 4), barposY + q_y, 0xFF)
		end
		if level > 3 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 3), barposY + q_y, 0xFF)
		end
		if level > 2 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 2), barposY + q_y, 0xFF)
		end
		if level > 1 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 1), barposY + q_y, 0xFF)
		end
		if level > 0 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 0), barposY + q_y, 0xFF)
		end
	end
	if skill == 3 then
		q_x = q_x + 34
		if level > 4 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 4), barposY + q_y, 0xFF)
		end
		if level > 3 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 3), barposY + q_y, 0xFF)
		end
		if level > 2 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 2), barposY + q_y, 0xFF)
		end
		if level > 1 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 1), barposY + q_y, 0xFF)
		end
		if level > 0 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 0), barposY + q_y, 0xFF)
		end
	end
	if skill == 4 then
		q_x = q_x + 51
		if level > 4 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 4), barposY + q_y, 0xFF)
		end
		if level > 3 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 3), barposY + q_y, 0xFF)
		end
		if level > 2 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 2), barposY + q_y, 0xFF)
		end
		if level > 1 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 1), barposY + q_y, 0xFF)
		end
		if level > 0 then
			sprites.spell_level:Draw(barposX + q_x + (3 * 0), barposY + q_y, 0xFF)
		end
	end
	
end

function OnWndMsg(msg, key)
	if msg == KEY_DOWN and key == 16 then
		shiftDown = true
	end
	if msg == KEY_UP and key == 16 then
		shiftDown = false
	end
	if msg == WM_LBUTTONDOWN then
		mousedown = true
	end
	if msg == WM_LBUTTONUP then
		mousedown = false
	end
	if shiftDown then
		dragging = true
		
	else
		dragging = false
	end
	
end

function DrawEnemyFrames()		
	local lastscale = scale
	basepos.x = basepos.x * lastscale
	basepos.y = basepos.y * lastscale
	scale = 0.01 * config.sidecooldown.scale
	if scale > 0 then
		basepos.x = basepos.x / scale
		basepos.y = basepos.y / scale
	end
	local framex = 150
	local framey = 65 
	local champsize = 54
	local buttonsize = 14
	local sssize = 28

	local alpha_max = 0xFF
	local alpha_min = 0x50
	local heronum = 0
	local cd = { cd = 0, size = 12, x = 0, y = 0}
	local textcolor = { black = ARGB(255, 0, 0, 0), red = ARGB(255,255, 0, 0), white = ARGB(255,255,255,255), green = ARGB(255,0,255,0)}
	if dragging then
		sprites.drag:SetScale(scale, scale)
		sprites.drag:DrawEx(Rect(0, 0, 0 + framex, 0 + framey),
																D3DXVECTOR3(0,0,0),
																D3DXVECTOR3(basepos.x, basepos.y - framey, 0),
																alpha_max)
		if mousedown then
			local curPos = GetCursorPos()
			if curPos.x >= basepos.x * scale and curPos.y >= (basepos.y -  framey ) * scale  and
					curPos.x <= ( basepos.x +  framex ) * scale and curPos.y <=  basepos.y  * scale then
				basepos.x = (curPos.x / scale ) - 75
				basepos.y =  (curPos.y  / scale) +  32 
			end
		end
	end
	--- if heroManager.iCount start ---
	if heroManager.iCount > 0 then
		--- for i = 1, heroManager.iCount, 1 do ---
		for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			local ii = enemy.charName
			--- if enemy.team ~= myHero.team then ---
			if enemy.team ~= myHero.team then
				--- background sprite
				sprites.background:SetScale(scale, scale)
				sprites.background:DrawEx(Rect(0, 0, champsize, champsize),
																D3DXVECTOR3(0,0,0),
																D3DXVECTOR3(basepos.x + 23, basepos.y + ( heronum * framey ) + 5, 0),
																alpha_max)
				--- background sprite END ---
				--- champion sprite begin
				if enemy.visible and not enemy.dead then
					enemyList[ii].sprite_champ:SetScale(scale, scale)
					enemyList[ii].sprite_champ:DrawEx(Rect(0, 0, 0 + champsize, 0 + champsize),
																		D3DXVECTOR3(0,0,0),
																		D3DXVECTOR3(basepos.x + 23, basepos.y + ( heronum * framey ) + 5, 0),
																		alpha_max)
				else
					enemyList[ii].sprite_champ:SetScale(scale, scale)
					enemyList[ii].sprite_champ:DrawEx(Rect(0, 0, 0 + champsize, 0 + champsize),
																		D3DXVECTOR3(0,0,0),
																		D3DXVECTOR3(basepos.x + 23, basepos.y + ( heronum * framey ) + 5 , 0),
																		alpha_min)
				end
				--champion sprite end
				for v, s in pairs(TrackSpells) do
				----- spell 1 -----
					if (v == 1) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
							sprites.frame_button_red:SetScale(scale,scale)
							sprites.frame_button_red:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + (scale * (v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 3
							cd.y = 5
							cd.size = 8
							if cd.cd < 100 then
								cd.size = 10
								cd.x = 6
								cd.y = 4
							end
							if cd.cd < 10 then
								cd.x = 8
								cd.y = 4
								cd.size = 11
							end

							DrawTextWithBorder(tostring(cd.cd), cd.size * scale, (basepos.x * scale) + 
							(cd.x * scale),(basepos.y * scale) + (cd.y * scale) + (scale * (v-1) * 15) + (heronum * scale * framey),
							textcolor.white, textcolor.black)
						else
							if (SpellsData[ii][s].level > 0) then
								sprites.frame_button_green:SetScale(scale, scale)
								sprites.frame_button_green:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ((v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							else
								sprites.frame_button_gray:SetScale(scale, scale)
								sprites.frame_button_gray:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, ((v-1) * 15) + basepos.y + ( heronum * framey ) + 2, 0), --Pos
									alpha_max) --Pos--Alpha
							end
							DrawTextWithBorder('Q', 11 * scale, ( basepos.x * scale) + (7 * scale), 
							(basepos.y * scale) + (scale * (v-1) * 15) + (3 * scale) + (heronum * scale * framey), textcolor.white,
							textcolor.black)
						end
					end
				------------------- spell 1 end -----------------------
								----- spell 2 -----
					if (v == 2) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
							sprites.frame_button_red:SetScale(scale,scale)
							sprites.frame_button_red:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ((v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 3
							cd.y = 5
							cd.size = 8
							if cd.cd < 100 then
								cd.size = 10
								cd.x = 6
								cd.y = 4
							end
							if cd.cd < 10 then
								cd.x = 8
								cd.y = 4
								cd.size = 11
							end

							DrawTextWithBorder(tostring(cd.cd), cd.size * scale, (basepos.x * scale) + 
							(cd.x * scale),(basepos.y * scale) + (cd.y * scale) + (scale * (v-1) * 15) + (heronum * scale * framey),
							textcolor.white, textcolor.black)
						else
							if (SpellsData[ii][s].level > 0) then
								sprites.frame_button_green:SetScale(scale, scale)
								sprites.frame_button_green:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ((v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							else
								sprites.frame_button_gray:SetScale(scale, scale)
								sprites.frame_button_gray:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, (scale * (v-1) * 15) + basepos.y + ( heronum * framey ) + 2, 0), --Pos
									alpha_max) --Pos--Alpha
							end
							DrawTextWithBorder('W', 11 * scale, ( basepos.x * scale) + (6 * scale), 
							(basepos.y * scale) + (scale * (v-1) * 15) + (3 * scale) + (heronum * scale * framey), textcolor.white,
							textcolor.black)
						end
					end
				------------------- spell 2 end -----------------------
							----- spell 3 -----
					if (v == 3) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
							sprites.frame_button_red:SetScale(scale,scale)
							sprites.frame_button_red:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ( (v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 3
							cd.y = 5
							cd.size = 8
							if cd.cd < 100 then
								cd.size = 10
								cd.x = 6
								cd.y = 4
							end
							if cd.cd < 10 then
								cd.x = 8
								cd.y = 4
								cd.size = 11
							end

							DrawTextWithBorder(tostring(cd.cd), cd.size * scale, (basepos.x * scale) + 
							(cd.x * scale),(basepos.y * scale) + (cd.y * scale) + (scale * (v-1) * 15) + (heronum * scale * framey),
							textcolor.white, textcolor.black)
						else
							if (SpellsData[ii][s].level > 0) then
								sprites.frame_button_green:SetScale(scale, scale)
								sprites.frame_button_green:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ( (v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							else
								sprites.frame_button_gray:SetScale(scale, scale)
								sprites.frame_button_gray:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, ((v-1) * 15) + basepos.y + ( heronum * framey ) + 2, 0), --Pos
									alpha_max) --Pos--Alpha
							end
							DrawTextWithBorder('E', 11 * scale, ( basepos.x * scale) + (7 * scale), 
							(basepos.y * scale) + (scale * (v-1) * 15) + (3 * scale) + (heronum * scale * framey), textcolor.white,
							textcolor.black)
						end
					end
				------------------- spell 3 end -----------------------		
					----- spell 4 -----
					if (v == 4) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
							sprites.frame_button_red:SetScale(scale,scale)
							sprites.frame_button_red:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ( (v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 3
							cd.y = 5
							cd.size = 8
							if cd.cd < 100 then
								cd.size = 10
								cd.x = 6
								cd.y = 4
							end
							if cd.cd < 10 then
								cd.x = 8
								cd.y = 4
								cd.size = 11
							end

							DrawTextWithBorder(tostring(cd.cd), cd.size * scale, (basepos.x * scale) + 
							(cd.x * scale),(basepos.y * scale) + (cd.y * scale) + (scale * (v-1) * 15) + (heronum * scale * framey),
							textcolor.white, textcolor.black)
						else
							if (SpellsData[ii][s].level > 0) then
								sprites.frame_button_green:SetScale(scale, scale)
								sprites.frame_button_green:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, basepos.y + ( heronum * framey ) + 2 + ((v-1) * 15), 0), --Pos
									alpha_max) --Pos--Alpha
							else
								sprites.frame_button_gray:SetScale(scale, scale)
								sprites.frame_button_gray:DrawEx(Rect(0, 0, 0 + buttonsize, 0 + buttonsize), --Rect
									D3DXVECTOR3(0, 0, 0), --Center
									D3DXVECTOR3(basepos.x + 3, ((v-1) * 15) + basepos.y + ( heronum * framey ) + 2, 0), --Pos
									alpha_max) --Pos--Alpha
							end
							DrawTextWithBorder('R', 11 * scale, ( basepos.x * scale) + (7 * scale), 
							(basepos.y * scale) + (scale * (v-1) * 15) + (3 * scale) + (heronum * scale * framey), textcolor.white,
							textcolor.black)
						end
					end
				------------------- spell 4 end -----------------------		
				--- spell 5 ---
					if (v == 5) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then 
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 93 -- 119 -> 87
							cd.y = 11
							cd.size = 12
							if cd.cd < 100 then
								cd.size = 14
								cd.x = 95
								cd.y = 11
							end
							if cd.cd < 10 then
								cd.x = 97
								cd.y = 11
								cd.size = 15
							end
							sprites.SSbackground:SetScale(scale,scale)
							sprites.SSbackground:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 87, basepos.y + 5 + (heronum * framey), 0),
														alpha_max)							
							enemyList[ii].sprite1_large:SetScale(scale,scale)
							enemyList[ii].sprite1_large:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 87, basepos.y + 5 + (heronum * framey), 0),
														alpha_min)
							DrawTextWithBorder(tostring(cd.cd), cd.size * scale,
								(basepos.x * scale) + (cd.x * scale),
								(basepos.y * scale) + (cd.y * scale) + (heronum * scale * framey),
								textcolor.red, textcolor.black)														
								
						else
							enemyList[ii].sprite1_large:SetScale(scale,scale)
							enemyList[ii].sprite1_large:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 87, basepos.y + 5 + (heronum * framey), 0),
														alpha_max)							
						end
					end
				--- spell 5 end ---								
				
				--- spell 6 ---
				-- 119, 31 - > 119, 5
					if (v == 6) then
						if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then 
							cd.cd = SpellsData[ii][s].currentCd
							cd.x = 125
							cd.y = 11
							cd.size = 12
							if cd.cd < 100 then
								cd.size = 14
								cd.x = 127
								cd.y = 11
							end
							if cd.cd < 10 then
								cd.x = 129
								cd.y = 11
								cd.size = 15
							end
							sprites.SSbackground:SetScale(scale,scale)
							sprites.SSbackground:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 119, basepos.y + 5 + (heronum * framey), 0),
														alpha_max)							
							enemyList[ii].sprite2_large:SetScale(scale,scale)
							enemyList[ii].sprite2_large:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 119, basepos.y + 5 + (heronum * framey), 0),
														alpha_min)
							DrawTextWithBorder(tostring(cd.cd), cd.size * scale,
								(basepos.x * scale) + (cd.x * scale),
								(basepos.y * scale) + (cd.y * scale) + (heronum * scale * framey),
								textcolor.red, textcolor.black)														
								
						else
							enemyList[ii].sprite2_large:SetScale(scale,scale)
							enemyList[ii].sprite2_large:DrawEx(Rect(0,0,0 + sssize, 0 + sssize),
														D3DXVECTOR3(0,0,0),
														D3DXVECTOR3(basepos.x + 119, basepos.y + 5 + (heronum * framey), 0),
														alpha_max)							
						end
					end
				--- spell 6 end ---				
				end
				--- frame start ---
				sprites.frame:SetScale(scale, scale)
				sprites.frame:DrawEx(Rect(0, 0, 0 + framex, 0 + framey),
																D3DXVECTOR3(0,0,0),
																D3DXVECTOR3(basepos.x, basepos.y + ( heronum * framey ) , 0),
																alpha_max)
				--- frame end ---
				--------------------------------------------------------
				sprites.health:SetScale(scale, scale)
				sprites.health:DrawEx(Rect(0, 0, 0 + math.floor((62 / enemy.maxHealth) *(enemy.health)), 0 + 10), --Rect
                D3DXVECTOR3(0, 0, 0), --Center
                D3DXVECTOR3(basepos.x + 85, basepos.y + ( heronum * framey ) + 38, 0), --Pos
                alpha_max)
				----------------------------------------------------------
				----- health -----
				local currHealth = tostring(math.floor(enemy.health)).."/"..tostring(math.floor(enemy.maxHealth))
				local healthx = ( 62 - (#currHealth * 5 ) ) / 2
				DrawTextWithBorder(currHealth, 13 * scale,
								(basepos.x * scale) + (85 * scale) + healthx * scale,
								(basepos.y * scale) + (36 * scale) + (heronum * scale * framey),
								textcolor.white, textcolor.black)								
				---- end health
				local nomana = false
				local energy = false
				for c, z in pairs(championList.nomana) do
					if enemy.charName == z then
						nomana = true
					end
				end
				for c, z in pairs(championList.energy) do
					if enemy.charName == z then
						energy = true
					end
				end
				if not nomana then
					if energy then
						sprites.energy:SetScale(scale, scale)
						sprites.energy:DrawEx(Rect(0, 0, 0 + math.floor((62 / enemy.maxMana) * (enemy.mana)), 0 + 10), --Rect
                D3DXVECTOR3(0, 0, 0), --Center
                D3DXVECTOR3(basepos.x + 85, basepos.y + ( heronum * framey ) + 50, 0), --Pos
                alpha_max) --Pos--Alpha
					else
						sprites.mana:SetScale(scale, scale)
						sprites.mana:DrawEx(Rect(0, 0, 0 + math.floor((62 / enemy.maxMana) * (enemy.mana)), 0 + 10), --Rect
                D3DXVECTOR3(0, 0, 0), --Center
                D3DXVECTOR3(basepos.x + 85, basepos.y + ( heronum * framey ) + 50, 0), --Pos
                alpha_max) --Pos--Alpha
					end
					----- mana -----
					local currMana = tostring(math.floor(enemy.mana)).."/"..tostring(math.floor(enemy.maxMana))
					local manax = ( 62 - (#currMana * 5 ) ) / 2
					DrawTextWithBorder(currMana, 13 * scale,
								(basepos.x * scale) + (85 * scale) + manax * scale,
								(basepos.y * scale) + (48 * scale) + (heronum * scale * framey),
								textcolor.white, textcolor.black)								
					---- end mana ---
				end
				local level = enemy.level
				cd.x = 24
				if level < 10 then
					cd.x = 27
				end
				if level ~= nil then
					DrawTextWithBorder(tostring(level), 13 * scale,
								(basepos.x * scale) + (cd.x * scale),
								(basepos.y * scale) + (46 * scale) + (heronum * scale * framey),
								textcolor.white, textcolor.black)					
				end
				-------------------------------------------------------
				--- for v, s in pairs END ---
        heronum = heronum + 1																		
			end
			--- enemy.team ~= myHero.team then  end---

		end
		--- for i = 1, heroManager.iCount, 1 do end ---
	end
	--- if heroManager.iCount end ---
end

function GetBarData(enemy)
	local barWidth = 169 -- 140
	local barHeight = 47
	local barOffsetX = 27
	local barOffsetY = 37
	local barData = GetEnemyBarData()
	local barPos = GetUnitHPBarPos(enemy)
  local barPosOffset = GetUnitHPBarOffset(enemy)
  local barPosPercentageOffset = { x = barData.PercentageOffset.x, y = barData.PercentageOffset.y }
	barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * barWidth +barOffsetX
	barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * barHeight + barOffsetY
	return barPos
end

function DrawEnemies()
  local ii
  local q_cd = 0
  local q_cd_size = 0
  local q_cd_x = 0
  local q_cd_y = 0
  local w_cd = 0
  local w_cd_size = 0
  local w_cd_x = 0
  local w_cd_y = 0
  local e_cd = 0
  local e_cd_size = 0
  local e_cd_x = 0
  local e_cd_y = 0
  local r_cd = 0
  local r_cd_size = 0
  local r_cd_x = 0
  local r_cd_y = 0  
  local s1_cd = 0
  local s1_cd_size = 0
  local s1_cd_x = 0
  local s1_cd_y = 0 
  local s2_cd = 0
  local s2_cd_size = 0
  local s2_cd_x = 0
  local s2_cd_y = 0     
  
  for i = 1, heroManager.iCount, 1 do
    local enemy = heroManager:getHero(i)
    if enemy.team ~= myHero.team and (ValidTarget(enemy, math.huge,false) or ValidTarget(enemy)) then
      ii = enemy.charName
      local barpos = { }
      local pos1 = GetUnitHPBarPos(enemy)
      local nx = (GetUnitHPBarOffset(enemy).x - 0.5) * 50 - 5
      local x = pos1.x + nx 
      local ny = (GetUnitHPBarOffset(enemy).y - 0.5) * 50 - 5
      local y = pos1.y + ny
      barpos.x = x - 60
      barpos.y = y + 24
			if (enemy.charName == "KogMaw") then
				barpos.y = barpos.y - 1
				barpos.x = barpos.x
			end
			if (enemy.charName == "Darius") or (enemy.charName == "Renekton") then
				barpos.y = barpos.y - 1
				barpos.x = barpos.x - 8
			end
			if (enemy.charName == "Kayle") then
				barpos.y = barpos.y - 4
				barpos.x = barpos.x - 1
			end
			if (enemy.charName == "JarvanIV") or (enemy.charName == "XinZhao") then
				barpos.x = barpos.x - 15
				barpos.y = barpos.y - 1
			end
      if OnScreen(barpos.x, barpos.y) and enemy.dead ~= true and enemy.visible and (SpellsData[ii] ~= nil) then
        sprites.main_hud:Draw(barpos.x + 4,barpos.y + 1, 0xFF)
        for v, s in pairs (TrackSpells) do
          if (v == 1) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then   
              sprites.button_red:Draw(barpos.x + 25, barpos.y + 21, 0xFF)
              if (SpellsData[ii][s].currentCd ~= nil) then
                q_cd = SpellsData[ii][s].currentCd
                q_cd_size = 10
                q_cd_x = 26
                q_cd_y = 23
                if q_cd < 100 then
                  q_cd_size = 14
                  q_cd_x = q_cd_x + 1
                  q_cd_y = q_cd_y - 2
                end
                if q_cd < 10 then
                  q_cd_x = q_cd_x + 2
                end                 
                DrawTextWithBorder(tostring(q_cd), q_cd_size, barpos.x + q_cd_x, barpos.y + q_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
                end
              else
                if SpellsData[ii][s].level > 0 then
                  sprites.button_green:Draw(barpos.x + 25, barpos.y + 21, 0xFF)
                end
                DrawTextWithBorder('Q', 14, barpos.x + 28.8, barpos.y + 21, ARGB(255, 255, 255,255), ARGB(255,0,0,0))
              end
              if SpellsData[ii][s].level > 0 then
                DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
              end
          elseif (v == 2) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 42, barpos.y + 21, 0xFF)   
              if (SpellsData[ii][s].currentCd ~= nil) then    

                w_cd = SpellsData[ii][s].currentCd
                w_cd_size = 10
                w_cd_x = 43
                w_cd_y = 23
                if w_cd < 100 then
                  w_cd_size = 14
                  w_cd_x = w_cd_x + 0.8
                  w_cd_y = w_cd_y - 2
                end
                if w_cd < 10 then
                  w_cd_x = w_cd_x + 2.5
                end           
      
                DrawTextWithBorder(tostring(w_cd), w_cd_size, barpos.x + w_cd_x, barpos.y + w_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 42, barpos.y + 21, 0xFF) 
              end
              DrawTextWithBorder('W', 14, barpos.x + 45, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))      
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end             
          elseif (v == 3) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 59, barpos.y + 21, 0xFF) 
              if (SpellsData[ii][s].currentCd ~= nil) then    

                e_cd = SpellsData[ii][s].currentCd
                e_cd_size = 10
                e_cd_x = 60.5
                e_cd_y = 23
                if e_cd < 100 then
                  e_cd_size = 14
                  e_cd_x = e_cd_x
                  e_cd_y = e_cd_y - 2
                end
                if e_cd < 10 then
                  e_cd_x = e_cd_x + 2.8
                end           
      
                DrawTextWithBorder(tostring(e_cd), e_cd_size, barpos.x + e_cd_x, barpos.y + e_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 59, barpos.y + 21, 0xFF)
              end
              DrawTextWithBorder('E', 14, barpos.x + 64.5, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end                   
          elseif (v == 4) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 76, barpos.y + 21, 0xFF)
              if (SpellsData[ii][s].currentCd ~= nil) then    

                r_cd = SpellsData[ii][s].currentCd
                r_cd_size = 10
                r_cd_x = 77
                r_cd_y = 23
                if r_cd < 100 then
                  r_cd_size = 14
                  r_cd_x = r_cd_x + 0.4
                  r_cd_y = r_cd_y - 2
                end
                if r_cd < 10 then
                  r_cd_x = r_cd_x + 2.8
                end           
      
                DrawTextWithBorder(tostring(r_cd), r_cd_size, barpos.x + r_cd_x, barpos.y + r_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 76, barpos.y + 21, 0xFF)
              end
              DrawTextWithBorder('R', 14, barpos.x + 80.5, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))     
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end                     
          elseif (v == 5) then                
                if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  enemyList[ii].sprite1:Draw(barpos.x + 6,barpos.y + 3,0x50)
                  if (SpellsData[ii][s].currentCd ~= nil) then
                    s1_cd = SpellsData[ii][s].currentCd
                    s1_cd_size = 10
                    s1_cd_x = 7.4
                    s1_cd_y = 5
                    if s1_cd < 100 then
                      s1_cd_size = 14
                      s1_cd_x = s1_cd_x + 0.5
                      s1_cd_y = s1_cd_y - 2
                    end
                    if s1_cd < 10 then
                      s1_cd_x = s1_cd_x + 2.3   
                    end
                  end                 
                  DrawTextWithBorder(tostring(s1_cd), s1_cd_size, barpos.x + s1_cd_x, barpos.y + s1_cd_y, ARGB(255, 255, 0, 0), ARGB(255,50,0,0))
                else                  
                  enemyList[ii].sprite1:Draw(barpos.x + 6,barpos.y + 3,0xFF)
                end
          elseif (v == 6) then                
                if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  enemyList[ii].sprite2:Draw(barpos.x + 6,barpos.y + 21,0x50)
                  if (SpellsData[ii][s].currentCd ~= nil) then
                    s2_cd = SpellsData[ii][s].currentCd
                    s2_cd_size = 10
                    s2_cd_x = 7.4
                    s2_cd_y = 23
                    if s2_cd < 100 then
                      s2_cd_size = 14
                      s2_cd_x = s2_cd_x + 0.5
                      s2_cd_y = s2_cd_y - 2
                    end
                    if s2_cd < 10 then
                      s2_cd_x = s2_cd_x + 2.3   
                    end
                  end                 
                  DrawTextWithBorder(tostring(s2_cd), s2_cd_size, barpos.x + s2_cd_x, barpos.y + s2_cd_y, ARGB(255, 255, 0, 0), ARGB(255,50,0,0))
                end
                else                  
                  enemyList[ii].sprite2:Draw(barpos.x + 6,barpos.y + 21, 0xFF)
              end
          end
        end         
      end
    end
  end 
end

function DrawFriends()
  local ii
  local q_cd = 0
  local q_cd_size = 0
  local q_cd_x = 0
  local q_cd_y = 0
  local w_cd = 0
  local w_cd_size = 0
  local w_cd_x = 0
  local w_cd_y = 0
  local e_cd = 0
  local e_cd_size = 0
  local e_cd_x = 0
  local e_cd_y = 0
  local r_cd = 0
  local r_cd_size = 0
  local r_cd_x = 0
  local r_cd_y = 0  
  local s1_cd = 0
  local s1_cd_size = 0
  local s1_cd_x = 0
  local s1_cd_y = 0 
  local s2_cd = 0
  local s2_cd_size = 0
  local s2_cd_x = 0
  local s2_cd_y = 0     
  
  for i = 1, heroManager.iCount, 1 do
    local enemy = heroManager:getHero(i)
    if enemy.team == myHero.team and enemy ~= myHero and (ValidTarget(enemy, math.huge,false) or ValidTarget(enemy)) then
      ii = enemy.charName .. "_team"
      local barpos = { }
      local pos1 = GetUnitHPBarPos(enemy)
      local nx = (GetUnitHPBarOffset(enemy).x - 0.5) * 50 - 5
      local x = pos1.x + nx 
      local ny = (GetUnitHPBarOffset(enemy).y - 0.5) * 50 - 5
      local y = pos1.y + ny
      barpos.x = x - 60
      barpos.y = y + 24
			if (enemy.charName == "Darius") or (enemy.charName == "Renekton") then
				barpos.y = barpos.y - 1
				barpos.x = barpos.x - 8
			end
			if (enemy.charName == "Kayle") then
				barpos.y = barpos.y - 4
				barpos.x = barpos.x - 1
			end
			if (enemy.charName == "KogMaw") then
				barpos.y = barpos.y - 1
				barpos.x = barpos.x
			end
			if (enemy.charName == "JarvanIV") or (enemy.charName == "XinZhao") then
				barpos.x = barpos.x - 15
				barpos.y = barpos.y - 1
			end
      if OnScreen(barpos.x, barpos.y) and enemy.dead ~= true and enemy.visible and (SpellsData[ii] ~= nil) then
        sprites.main_hud:Draw(barpos.x + 4,barpos.y + 1, 0xFF)
        for v, s in pairs (TrackSpells) do
          if (v == 1) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then   
              sprites.button_red:Draw(barpos.x + 25, barpos.y + 21, 0xFF)
              if (SpellsData[ii][s].currentCd ~= nil) then
                q_cd = SpellsData[ii][s].currentCd
                q_cd_size = 10
                q_cd_x = 26
                q_cd_y = 23
                if q_cd < 100 then
                  q_cd_size = 14
                  q_cd_x = q_cd_x + 1
                  q_cd_y = q_cd_y - 2
                end
                if q_cd < 10 then
                  q_cd_x = q_cd_x + 2
                end                 
                DrawTextWithBorder(tostring(q_cd), q_cd_size, barpos.x + q_cd_x, barpos.y + q_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
                end
              else
                if SpellsData[ii][s].level > 0 then
                  sprites.button_green:Draw(barpos.x + 25, barpos.y + 21, 0xFF)
                end
                DrawTextWithBorder('Q', 14, barpos.x + 28.8, barpos.y + 21, ARGB(255, 255, 255,255), ARGB(255,0,0,0))
              end
              if SpellsData[ii][s].level > 0 then
                DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
              end
          elseif (v == 2) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 42, barpos.y + 21, 0xFF)   
              if (SpellsData[ii][s].currentCd ~= nil) then    

                w_cd = SpellsData[ii][s].currentCd
                w_cd_size = 10
                w_cd_x = 43
                w_cd_y = 23
                if w_cd < 100 then
                  w_cd_size = 14
                  w_cd_x = w_cd_x + 0.8
                  w_cd_y = w_cd_y - 2
                end
                if w_cd < 10 then
                  w_cd_x = w_cd_x + 2.5
                end           
      
                DrawTextWithBorder(tostring(w_cd), w_cd_size, barpos.x + w_cd_x, barpos.y + w_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 42, barpos.y + 21, 0xFF) 
              end
              DrawTextWithBorder('W', 14, barpos.x + 45, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))      
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end             
          elseif (v == 3) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 59, barpos.y + 21, 0xFF) 
              if (SpellsData[ii][s].currentCd ~= nil) then    

                e_cd = SpellsData[ii][s].currentCd
                e_cd_size = 10
                e_cd_x = 60.5
                e_cd_y = 23
                if e_cd < 100 then
                  e_cd_size = 14
                  e_cd_x = e_cd_x
                  e_cd_y = e_cd_y - 2
                end
                if e_cd < 10 then
                  e_cd_x = e_cd_x + 2.8
                end           
      
                DrawTextWithBorder(tostring(e_cd), e_cd_size, barpos.x + e_cd_x, barpos.y + e_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 59, barpos.y + 21, 0xFF)
              end
              DrawTextWithBorder('E', 14, barpos.x + 64.5, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end                   
          elseif (v == 4) then
            if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s] ) then 
              sprites.button_red:Draw(barpos.x + 76, barpos.y + 21, 0xFF)
              if (SpellsData[ii][s].currentCd ~= nil) then    

                r_cd = SpellsData[ii][s].currentCd
                r_cd_size = 10
                r_cd_x = 77
                r_cd_y = 23
                if r_cd < 100 then
                  r_cd_size = 14
                  r_cd_x = r_cd_x + 0.4
                  r_cd_y = r_cd_y - 2
                end
                if r_cd < 10 then
                  r_cd_x = r_cd_x + 2.8
                end           
      
                DrawTextWithBorder(tostring(r_cd), r_cd_size, barpos.x + r_cd_x, barpos.y + r_cd_y, ARGB(255, 255, 255,255), ARGB(255,0,0,0))  
              end
            else  
              if SpellsData[ii][s].level > 0 then
                sprites.button_green:Draw(barpos.x + 76, barpos.y + 21, 0xFF)
              end
              DrawTextWithBorder('R', 14, barpos.x + 80.5, barpos.y + 21, ARGB(255, 255, 255, 255),ARGB(255,0,0,0))     
            end
            if SpellsData[ii][s].level > 0 then
              DrawSkillLevel(v, SpellsData[ii][s].level,barpos.x,barpos.y)
            end                     
          elseif (v == 5) then                
                if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  friendList[ii].sprite1:Draw(barpos.x + 6,barpos.y + 3,0x50)
                  if (SpellsData[ii][s].currentCd ~= nil) then
                    s1_cd = SpellsData[ii][s].currentCd
                    s1_cd_size = 10
                    s1_cd_x = 7.4
                    s1_cd_y = 5
                    if s1_cd < 100 then
                      s1_cd_size = 14
                      s1_cd_x = s1_cd_x + 0.5
                      s1_cd_y = s1_cd_y - 2
                    end
                    if s1_cd < 10 then
                      s1_cd_x = s1_cd_x + 2.3   
                    end
                  end                 
                  DrawTextWithBorder(tostring(s1_cd), s1_cd_size, barpos.x + s1_cd_x, barpos.y + s1_cd_y, ARGB(255, 255, 0, 0), ARGB(255,50,0,0))
                else                  
                  friendList[ii].sprite1:Draw(barpos.x + 6,barpos.y + 3,0xFF)
                end
          elseif (v == 6) then                
                if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  if (SpellsData[ii][s].currentCd ~= 0 and SpellsData[ii][s]) then
                  friendList[ii].sprite2:Draw(barpos.x + 6,barpos.y + 21,0x50)
                  if (SpellsData[ii][s].currentCd ~= nil) then
                    s2_cd = SpellsData[ii][s].currentCd
                    s2_cd_size = 10
                    s2_cd_x = 7.4
                    s2_cd_y = 23
                    if s2_cd < 100 then
                      s2_cd_size = 14
                      s2_cd_x = s2_cd_x + 0.5
                      s2_cd_y = s2_cd_y - 2
                    end
                    if s2_cd < 10 then
                      s2_cd_x = s2_cd_x + 2.3   
                    end
                  end                 
                  DrawTextWithBorder(tostring(s2_cd), s2_cd_size, barpos.x + s2_cd_x, barpos.y + s2_cd_y, ARGB(255, 255, 0, 0), ARGB(255,50,0,0))
                end
                else                  
                  friendList[ii].sprite2:Draw(barpos.x + 6,barpos.y + 21, 0xFF)
              end
          end
        end         
      end
    end
  end 
end


function OnDraw()
  if loaded and loaded2 then

		if (config.reset.reset) then
			ReloadSprites()
		end
		--local loc = GetMinimap(myHero)
		--sprites.minimap_pink:Draw(loc.x - 10, loc.y-5, 0xFF)
		--sprites.minimap_ward:Draw(loc.x - 10, loc.y-8, 0xFF)
		--sprites.minimap_pink:Draw(loc.x + 180, loc.y-70, 0xFF)
		if config.cooldown.enemyon then
			DrawEnemies()
		end
		if config.cooldown.friendon then
			DrawFriends()
		end
		if config.recall.minimapon then
			DrawMinimapRecall()
		end
		if config.sidecooldown.enemyon then
			DrawEnemyFrames()
		end
  end
end


function GetHPBarPos(enemy)
	enemy.barData = {PercentageOffset = {x=0, y=0}}
	local barPos = GetUnitHPBarPos(enemy)
	local barPosOffset = GetUnitHPBarOffset(enemy)
	local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local BarPosOffsetX = 171
	local BarPosOffsetY = 46
	local CorrectionY = 37
	local StartHpPos = 31
	
	barPos.x = math.floor(barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos)
	barPos.y = math.floor(barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY)
						
	local StartPos = Vector(barPos.x , barPos.y, 0)
	local EndPos =  Vector(barPos.x + 108 , barPos.y , 0)
	return Vector(StartPos.x, StartPos.y, 0), Vector(EndPos.x, EndPos.y, 0)
end


function OnTick()
	if (loaded) and os.clock() > TickLimit then
		TickLimit = os.clock() + 0.3
		local ii
	  for i = 1, heroManager.iCount, 1 do
			local enemy = heroManager:getHero(i)
			if enemy ~= myHero then
			  ii = enemy.charName
				if enemy.team == myHero.team then
				  ii = ii .. "_team"
				end
				for v, s in pairs(TrackSpells) do
					if SpellsData[ii] == nil then
						SpellsData[ii] = {}
					end
					if SpellsData[ii][s] == nil then
						SpellsData[ii][s] = {currentCd = 0, level = 0, name = '' }
					end
					local thisspell = enemy:GetSpellData(s)
					local cd
					if thisspell and thisspell.currentCd then
						cd = thisspell.currentCd
					end
					if cd and thisspell and thisspell.currentCd then
						SpellsData[ii][s] = { currentCd = math.floor(cd), level = thisspell.level, name = thisspell.name }
					end
					
				end
			end
		end
	end	
	loaded2 = true
end


function FindSprite(file)
    assert(type(file) == "string", "GetSprite: wrong argument types (<string> expected for file)")
    if FileExist(file) == true then
        return createSprite(file)
    else
        PrintChat(file .. " not found (sprites installed ?)")
        return createSprite("empty.dds")
    end
end


function OnUnload()
	Serialization.saveTable({ basepos = basepos, scale = scale }, SCRIPT_PATH .. 'Common/NeosLittleHelper.lua')
end

function ReloadSprites()
	UnloadSprites()
	LoadSprites()
end

function UnloadSprites()
  for index, enemy in pairs(enemyList) do
    enemy.sprite1:Release()
		enemy.sprite1_large:Release()
		enemy.sprite2:Release()
		enemy.sprite2_large:Release()
  end   
	for _, v in pairs(sprites) do
		v:Release()
	end
end
_G.Packet.headers.R_PING = 0x40

function NormalPing(X, Y)
	--0x40
	Packet("R_PING", {x = X, y = Y, type = PING_NORMAL}):receive()
end

function WarnRecall(enemy)
	local hero = objManager:GetObjectByNetworkId(enemy.ID)
	local minimappos = GetMinimap(hero)
	if enemy.recalling then
		if GetTickCount() < (enemy.lastrecall + 6000) then
			if config.recall.printon then
				PrintChat(enemy.name.." cancelled recall")
			end
		else
			if config.recall.printon then
				PrintChat(enemy.name.." recalled")
			end
		end
		enemy.recalling = false
	else
		enemy.recalltick = GetTickCount()
		if config.recall.printon then
			PrintChat(enemy.name.." is recalling")
		end
		if config.recall.pingon then
			NormalPing(hero.x, hero.z)
		end
		enemy.recalling = true
	end
	enemy.lastrecall = GetTickCount()
end

function DrawMinimapRecall()
	for i,v in pairs(enemyList) do
		if v.recalling then
			local hero = objManager:GetObjectByNetworkId(v.ID)
			local minimappos = GetMinimap(hero)
			sprites.minimap_recall:Draw(minimappos.x - 5, minimappos.y - 10, 0xFF)
			if ( GetTickCount() < v.recalltick + 500) then

				DrawTextWithBorder(hero.charName.. " B", 11, minimappos.x - 10, minimappos.y + 3, ARGB(255, 200, 200, 0), ARGB(255,0,0,0))	
			else
				if GetTickCount() > v.recalltick + 1000 then
					v.recalltick = v.recalltick + 1000
				end
			end
		end
	end
end

function doit(header)
	local result = true
	local crap = { 191, 159, 31, 59, 135, 181, 183, 106, 186, 174, 102, 12, 81, 53, 97, 16, 127, 123, 93, 26, 254, 133, 196, 52, 56, 158, 147, 107, 110 }
	for _,v in pairs(crap) do
		if v == header then
			result = false
		end
	end
	return result
end


if VIP_USER then
	function OnRecvPacket(p)
		--if doit(p.header) then
			--PrintChat(tostring(p.header))
		--end
		--if p.header == 0xD7 then		
		if p.header == 0xD8 then
			p.pos = 5
			local ID = p:DecodeF()
			for _, v in pairs(enemyList) do
				if v.ID == ID then
					WarnRecall(v)
				end
			end
		end	
	end
end

function DumpPacketData(p,s,e)
    s, e = math.max(1,s or 1), math.min(p.size-1,e and e-1 or p.size-1)
    local pos, data = p.pos, ""
    p.pos = s
    for i=p.pos, e do
        data = data .. string.format("%02X ",p:Decode1())
    end
    p.pos = pos
    return data
end

function DumpPacket(p)
    local packet = {}
    packet.time = GetInGameTimer()
    packet.dwArg1 = p.dwArg1
    packet.dwArg2 = p.dwArg2
    packet.header = string.format("%02X",p.header)
    packet.data = DumpPacketData(p)
    return packet
end
