<?php exit() ?>--by Kain 97.90.203.108
function OnLoad()
    Menu = scriptConfig("Lag Free Circles", "LFC")
    Menu:addParam("LagFree", "Activate Lag Free Circles", 1, true)
    Menu:addParam("OtherScripts", "Show Circles for Extended Scripts", 1, true)
    Menu:addParam("CL", "Length before snapping", 4, 300, 75, 2000, 0)
    Menu:addParam("CLinfo", "The lower your length the better system you need", 5, "")
    print("<font color='#FF0000'>[LFC Extended] v1.2 Loaded.</font>")
    _G.oldDrawCircle = rawget(_G, 'DrawCircle')
    _G.DrawCircle = DrawCircle2
end

function OnTick()
	if not Menu.LagFree then _G.DrawCircle = _G.oldDrawCircle end
	if Menu.LagFree then
		_G.DrawCircle = DrawCircle2
	end
end

function OnDraw()
	--DrawCircle(myHero.x,myHero.y,myHero.z, 1000, ARGB(255,255,0,0))
end

-- Lag free circles (by Kain, barasia, vadash and viseversa)
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

function decToHex(IN)
    local B,K,OUT,I,D=16,"0123456789ABCDEF","",0
    while IN>0 do
        I=I+1
        IN,D=math.floor(IN/B),math.fmod(IN,B)+1
        OUT=string.sub(K,D,D)..OUT
    end
    return OUT
end

function hex2rgb(hex)
    hex = hex:gsub("#","")
    return tonumber("0x"..hex:sub(1,2)), tonumber("0x"..hex:sub(3,4)), tonumber("0x"..hex:sub(5,6))
end

function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        if Menu.OtherScripts and color then
           c1, c2, c3 = hex2rgb(decToHex(color))
           color = RGB(c1, c2, c3)
        end

        DrawCircleNextLvl(x, y, z, radius, 1, color, Menu.CL) 
    end
end