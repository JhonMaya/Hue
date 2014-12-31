<?php exit() ?>--by Kain 97.90.203.108
function OnLoad()
    Menu = scriptConfig("Lag Free Circles: Extended", "LFC")
    Menu:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, true)
    Menu:addParam("OtherScripts", "Show Circles for Extended Scripts", SCRIPT_PARAM_ONOFF, true)
    Menu:addParam("LineWidth", "Width of Lines", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
    -- Menu:addParam("Rotate", "Rotate Effect", SCRIPT_PARAM_ONOFF, false)
    Menu:addParam("Strobe", "Strobe Effect", SCRIPT_PARAM_ONOFF, false)
    Menu:addParam("CL", "Length before snapping", SCRIPT_PARAM_SLICE, 300, 75, 2000, 0)
    Menu:addParam("CLinfo", "*Lower Length means less FPS.", SCRIPT_PARAM_INFO, "")
    print("<font color='#FF0000'>[LFC Extended] v1.3 Loaded.</font>")
    _G.oldDrawCircle = rawget(_G, 'DrawCircle')
    _G.DrawCircle = DrawLFCCircle
    rotate = false
    rotateLine = 0
    tick = nil
end

function OnTick()
	if not Menu.LagFree then _G.DrawCircle = _G.oldDrawCircle end

	if Menu.LagFree then
		_G.DrawCircle = DrawLFCCircle
	end
end

function OnDraw()
	-- DrawCircle(myHero.x,myHero.y,myHero.z, 1000, ARGB(255,255,0,0))
end

function isNumeric(text)
    if type(text) == "string" then
        text = tostring(text)
    end
    return text and true or false
end

-- Lag free circles (by Kain, barasia, vadash and viseversa)
function DrawLFCCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
    quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
    quality = 2 * math.pi / quality
    radius = radius*.92

    local points = {}
    rotateLine = 0
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        -- if not Menu.Rotate or (Menu.Rotate and ((rotate and isNumberEven(rotateLine)) or (not rotate and not isNumberEven(rotateLine)))) then
            points[#points + 1] = D3DXVECTOR2(c.x, c.y)
        --end
        -- rotateLine = rotateLine + 1
    end

    -- rotate = not rotate
    DrawLines2(points, width or 1, color or 4294967295)
end

function isNumberEven(number)
    return math.fmod(number, 2) == 0
end

function round(num) 
    if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function IsTickReady(tickFrequency)
    -- Improves FPS
    if tick ~= nil and math.fmod(tick, tickFrequency) == 0 then
        return true
    else
        return false
    end
end

function dec2hex(inp)
    local hexchars = "0123456789ABCDEF"
    if(inp == 0) then return 0 end
    outstr = ""

    while(inp and inp > 0) do
            local c = inp % #hexchars
            outstr = hexchars:sub(c+1, c+1) .. outstr
            inp = math.floor(inp / #hexchars)
    end
    return outstr
end

function hex2rgb(hex)
    hex = hex:gsub("#","")
    return tonumber("0x"..hex:sub(1,2)), tonumber("0x"..hex:sub(3,4)), tonumber("0x"..hex:sub(5,6))
end

function DrawLFCCircle(x, y, z, radius, color)
    if Menu.Strobe and not IsTickReady(2) then return end

    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)

    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius

    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))

    if sPos and sPos.x and sPos.y and OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        if Menu.OtherScripts and color and type(color) ~= "userdata" then
            local c1, c2, c3 = hex2rgb(dec2hex(color))
            color = RGB(c1, c2, c3)
        end

        DrawLFCCircleNextLvl(x, y, z, radius, Menu.LineWidth, color, Menu.CL) 
    end
end