<?php exit() ?>--by dienofail 68.48.159.9
local version = "0.11"

-- / Auto-Update Function / --
local autoupdateenabled = true
local UPDATE_SCRIPT_NAME = "Avenger"
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Gharakest/BoL/master/Avenger.lua?chunk="..math.random(1, 1000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

local ServerData
if autoupdateenabled then
	print("<font color=\"#C75DC3\">"..UPDATE_SCRIPT_NAME..": Retreiving infomation about new version...</font>")
	GetAsyncWebResult(UPDATE_HOST, UPDATE_PATH, function(d) ServerData = d end)
	function update()
		if ServerData ~= nil then
			local ServerVersion
			local send, tmp, sstart = nil, string.find(ServerData, "local version = \"")
			if sstart then
				send, tmp = string.find(ServerData, "\"", sstart + 1)
			end
			if send then
				ServerVersion = tonumber(string.sub(ServerData, sstart + 1, send - 1))
			end

			if ServerVersion ~= nil and tonumber(ServerVersion) ~= nil and tonumber(ServerVersion) > tonumber(version) then
				DownloadFile(UPDATE_URL.."?nocache"..myHero.charName..os.clock(), UPDATE_FILE_PATH, function () print("<font color=\"#C75DC3\">"..UPDATE_SCRIPT_NAME..": Successfully updated. Reload (double F9) please.</font>") end)     
			elseif ServerVersion then
				print("<font color=\"#C75DC3\">"..UPDATE_SCRIPT_NAME..": Loaded v"..version.."</font>")
			end		
			ServerData = nil
		end
	end
	AddTickCallback(update)
end
-- / Auto-Update Function / --

local white = ARGB(255, 255, 255, 255)
local black = ARGB(255, 0, 0, 0)
local hero_sprites = {}
local frame = GetSprite("Avenger\\frame.png")
local frame_ult = GetSprite("Avenger\\frame_ult.png")
local enemyHeroes = GetEnemyHeroes()

for i, hero in ipairs(enemyHeroes) do
	if not FileExist(SPRITE_PATH.."Avenger\\"..hero.charName.."_Square_0.png") then
		print("<font color='#C75DC3'>You have to download \"Avenger\" folder before you will use this script!</font>")
		return
	end
end

function OnLoad()
	for i, hero in ipairs(enemyHeroes) do
		hero_sprites[hero.charName] = GetSprite("Avenger\\"..hero.charName.."_Square_0.png")
	end
end

function DrawHero(hero, number)
	hero_sprites[hero.charName]:Draw(WINDOW_W - 42, 112+60*(number-1), 255)
	if hero.dead then
		DrawLine(WINDOW_W - 42 / 2, 112 + 60 * (number - 1), WINDOW_W - 42 / 2, 112 + 60 * (number - 1) + 42, 42, ARGB(175, 100, 0, 0))
	elseif not hero.visible then
		DrawLine(WINDOW_W - 42 / 2, 112 + 60 * (number - 1), WINDOW_W - 42 / 2, 112 + 60 * (number - 1) + 42, 42, ARGB(175, 0, 0, 0))
	end
	DrawHPBar(WINDOW_W - 40, 152 + 60 * (number - 1), 7, 38, hero.health * 100 / hero.maxHealth)
	DrawManaBar(WINDOW_W - 40, 160 + 60 * (number - 1), 7, 38, hero.mana * 100 / hero.maxMana)

	if hero:GetSpellData(_R).currentCd == 0 then
		frame_ult:Draw(WINDOW_W - 42, 100 + 60*(number-1), 255)
	else
		frame:Draw(WINDOW_W - 42, 100 + 60*(number-1), 255)
	end
end

function DrawHPBar(x, y, height, width, percent)
	x2 = x + (percent * width / 100)
	y = y + height / 2
	color = ARGB(255, 255 - (percent * 255 / 100), percent * 255 / 100, 0)
	DrawLine(x, y, x + width, y, height, ARGB(255, 0, 0, 0))
	DrawLine(x, y, x2, y, height, color)
end

function DrawManaBar(x, y, height, width, percent)
	x2 = x + (percent * width / 100)
	y = y + height / 2
	color = ARGB(255, 0, 0, 255)
	DrawLine(x, y, x + width, y, height, ARGB(255, 0, 0, 0))
	DrawLine(x, y, x2, y, height, color)
end

function OnDraw()
	for i, hero in ipairs(enemyHeroes) do
		DrawHero(hero, i)
	end
end