<?php exit() ?>--by 16hex16 105.236.17.194
function OnLoad()
Menu = scriptConfig("Lag Free Circles", "LFC")
Menu:addParam("INFO", "[Requires a Reload after turning off]", SCRIPT_PARAM_INFO, "")
Menu:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, true)
print("<font color='#FF0000'>[LFC] Loaded.</font>")
end
function OnTick()
	if Menu.LagFree then
		_G.DrawCircle = DrawCircle2
	end
end
function OnDraw()
	--DrawCircle(myHero.x,myHero.y,myHero.z, 500, ARGB(255,255,0,0))
end
-- Lag free circles (by barasia, vadash and viseversa)
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
  quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
  quality = 2 * math.pi / quality
  radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end
function round(num) 
 if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end
function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75) 
    end
end