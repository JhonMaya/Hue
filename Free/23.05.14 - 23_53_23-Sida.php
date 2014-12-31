<?php exit() ?>--by Sida 81.170.70.121
	if VIP_USER and LibsDone and ScriptStartOver then
		TumblePos = {
		["Circle"] = { ["x"] = 11589, ["y"] = 52, ["z"] = 4657 },
		["Tumble"] = { ["x"] = 11590.95, ["y"] = 4656.26 },
		["Cast"] = { ["x"] = 11334.74, ["y"] = 4517.47 }
		}
		if VIP_USER then
			if GetDistance(TumblePos.Circle) < 125 or GetDistance(TumblePos.Circle, mousePos) < 125 then
				DrawCircle(TumblePos.Circle.x, TumblePos.Circle.y, TumblePos.Circle.z, 100, 0x107458)
			else
				DrawCircle(TumblePos.Circle.x, TumblePos.Circle.y, TumblePos.Circle.z, 100, 0x80FFFF)
			end
		end
	end