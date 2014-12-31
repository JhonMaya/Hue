<?php exit() ?>--by ragehunter3 46.117.73.179
function OnLoad()
	print("<font color='#00FFFF'>Stream Overlay, has loaded. Please remember that you are required to reload the script when you change the overlay. This is to save any FPS drops</font>")
	print("<font color='#00FFFF'>Please change your HUD and Minimap size to 85.</font>")
	Menu = scriptConfig("Stream Overlay", "SO")
	Menu:addSubMenu("SO: Overlay", "Overlay")
	Menu.Overlay:addParam("Type", "Type of Overlay", SCRIPT_PARAM_SLICE, 0,0,2,0)
	Menu.Overlay:addParam("TypeINFO", "Name of Overlay: ", SCRIPT_PARAM_INFO, "")
	
	Menu:addSubMenu("SO: Sizes", "Size")
	Menu.Size:addParam("Size", "Resolution", SCRIPT_PARAM_SLICE, 1,1,4,0)
	Menu.Size:addParam("SizeINFO", "Resolution Value: ", SCRIPT_PARAM_INFO, "")
	
	if Menu.Overlay.Type == 1 then
		Menu.Overlay.TypeINFO = "Fire Flame"
		if Menu.Size.Size == 1 then
			Menu.Size.SizeINFO = "1280x720"
			sprite = GetWebSprite("http://i.imgur.com/dc3fP7y.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 2 then
			Menu.Size.SizeINFO = "1360x720"
			sprite = GetWebSprite("http://i.imgur.com/9emMDEe.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 3 then
			Menu.Size.SizeINFO = "1600x900"
			sprite = GetWebSprite("http://i.imgur.com/ZxWuRqO.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 4 then
			Menu.Size.SizeINFO = "1920x1080"
			sprite = GetWebSprite("http://i.imgur.com/7XfQ5e6.png", function(data) sprite = data end)
		else
			Menu.Size.SizeINFO = "N/A"
		end
	elseif Menu.Overlay.Type == 2 then
		Menu.Overlay.TypeINFO = "Championship Thresh"
		if Menu.Size.Size == 1 then
			Menu.Size.SizeINFO = "1280x720"
			sprite = GetWebSprite("http://i.imgur.com/pE2c6sM.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 2 then
			Menu.Size.SizeINFO = "1360x720"
			sprite = GetWebSprite("http://i.imgur.com/XpWCteJ.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 3 then
			Menu.Size.SizeINFO = "1600x900"
			sprite = GetWebSprite("http://i.imgur.com/3N6PUKB.png", function(data) sprite = data end)
		elseif Menu.Size.Size == 4 then
			Menu.Size.SizeINFO = "1920x1080"
			sprite = GetWebSprite("http://i.imgur.com/oHuyyUf.png", function(data) sprite = data end)
		else
			Menu.Size.SizeINFO = "N/A"
		end
	else
		Menu.Overlay.TypeINFO = "N/A"
		Menu.Size.SizeINFO = "N/A"
	end
end
function OnTick()
	if Menu.Overlay.Type == 1 then
		Menu.Overlay.TypeINFO = "Fire Flame"
		if Menu.Size.Size == 1 then
			Menu.Size.SizeINFO = "1280x720"
		elseif Menu.Size.Size == 2 then
			Menu.Size.SizeINFO = "1360x720"
		elseif Menu.Size.Size == 3 then
			Menu.Size.SizeINFO = "1600x900"
		elseif Menu.Size.Size == 4 then
			Menu.Size.SizeINFO = "1920x1080"
		else
			Menu.Size.SizeINFO = "N/A"
		end
	elseif Menu.Overlay.Type == 2 then
		Menu.Overlay.TypeINFO = "Championship Thresh"
		if Menu.Size.Size == 1 then
			Menu.Size.SizeINFO = "1280x720"
		elseif Menu.Size.Size == 2 then
			Menu.Size.SizeINFO = "1360x720"
		elseif Menu.Size.Size == 3 then
			Menu.Size.SizeINFO = "1600x900"
		elseif Menu.Size.Size == 4 then
			Menu.Size.SizeINFO = "1920x1080"
		else
			Menu.Size.SizeINFO = "N/A"
		end
	elseif Menu.OVerlay.Type == 3 then
		
	else
		Menu.Overlay.TypeINFO = "N/A"
		Menu.Size.SizeINFO = "N/A"
	end	
end
function OnDraw()
    if sprite then
        sprite:Draw(0,0,0xFF)
    end
end
--[[Overlays]]--
--[[
	http://puu.sh/6p4GX.png - Fire Flame Overlay
	http://i.imgur.com/1aK3w4J.png - Champion Thresh Overlay
	http://i.imgur.com/PHmGdU0.png - Leesin Overlay
	http://i.imgur.com/TGr5kbA.png - Pulsefire Overlay
	http://i.imgur.com/GwVNpAk.png - Rengar Overlay
	http://i.imgur.com/nZ5UeWx.png - Chaox Style Overlay
--]]