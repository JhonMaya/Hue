<?php exit() ?>--by Kain 97.90.203.108
-- Lag Free Circles Extended
-- by Kain, barasia, vadash and viseversa
-- v1.4

version = "1.4"

function OnLoad()
    Menu = scriptConfig("Lag Free Circles: Extended", "LFC")

    Menu:addSubMenu("[Info]", "ScriptInfo")
        Menu.ScriptInfo:addParam("sep","Lag Free Circles: Extended "..version, SCRIPT_PARAM_INFO, "")
        Menu.ScriptInfo:addParam("sep1","Created By: Kain", SCRIPT_PARAM_INFO, "")

    Menu:addSubMenu("[Main]", "Main")
        Menu.Main:addParam("LagFree", "Activate Lag Free Circles", SCRIPT_PARAM_ONOFF, true)
        Menu.Main:addParam("OtherScripts", "Show Circles for Extended Scripts", SCRIPT_PARAM_ONOFF, true)
        Menu.Main:addParam("LineWidth", "Width of Lines", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
        Menu.Main:addParam("LineLength", "Length before snapping", SCRIPT_PARAM_SLICE, 300, 75, 2000, 0)
        Menu.Main:addParam("LineLengthInfo", "*Lower Length means less FPS.", SCRIPT_PARAM_INFO, "")
        -- Menu:addParam("Rotate", "Rotate Effect", SCRIPT_PARAM_ONOFF, false)

    Menu:addSubMenu("[Effects]", "Effects")
        Menu.Effects:addParam("Strobe", "Strobe Effect", SCRIPT_PARAM_ONOFF, false)
        Menu.Effects:addParam("Rainbow", "Taste the Rainbow Effect", SCRIPT_PARAM_ONOFF, false)
        Menu.Effects:addParam("RainbowRandom", "Taste the Random Rainbow Effect", SCRIPT_PARAM_ONOFF, false)
        Menu.Effects:addParam("EffectSpeed", "Speed of Effect", SCRIPT_PARAM_SLICE, 25, 1, 300, 0)

    print("<font color='#FF0000'>[LFC Extended] v1.3 Loaded.</font>")
    _G.oldDrawCircle = rawget(_G, 'DrawCircle')
    _G.DrawCircle = DrawLFCCircle
    rotate = false
    rotateLine = 0
    tick = nil

    InitColors()
end

function InitColors()
    colorsCycle = { RGB(128,0,0), RGB(139,0,0), RGB(165,42,42), RGB(178,34,34), RGB(220,20,60), RGB(255,0,0), RGB(255,99,71), RGB(255,127,80), RGB(205,92,92), RGB(240,128,128), RGB(233,150,122), RGB(250,128,114), RGB(255,160,122), RGB(255,69,0), RGB(255,140,0), RGB(255,165,0), RGB(255,215,0), RGB(184,134,11), RGB(218,165,32), RGB(238,232,170), RGB(189,183,107), RGB(240,230,140), RGB(128,128,0), RGB(255,255,0), RGB(154,205,50), RGB(85,107,47), RGB(107,142,35), RGB(124,252,0), RGB(127,255,0), RGB(173,255,47), RGB(0,100,0), RGB(0,128,0), RGB(34,139,34), RGB(0,255,0), RGB(50,205,50), RGB(144,238,144), RGB(152,251,152), RGB(143,188,143), RGB(0,250,154), RGB(0,255,127), RGB(46,139,87), RGB(102,205,170), RGB(60,179,113), RGB(32,178,170), RGB(47,79,79), RGB(0,128,128), RGB(0,139,139), RGB(0,255,255), RGB(0,255,255), RGB(224,255,255), RGB(0,206,209), RGB(64,224,208), RGB(72,209,204), RGB(175,238,238), RGB(127,255,212), RGB(176,224,230), RGB(95,158,160), RGB(70,130,180), RGB(100,149,237), RGB(0,191,255), RGB(30,144,255), RGB(173,216,230), RGB(135,206,235), RGB(135,206,250), RGB(25,25,112), RGB(0,0,128), RGB(0,0,139), RGB(0,0,205), RGB(0,0,255), RGB(65,105,225), RGB(138,43,226), RGB(75,0,130), RGB(72,61,139), RGB(106,90,205), RGB(123,104,238), RGB(147,112,219), RGB(139,0,139), RGB(148,0,211), RGB(153,50,204), RGB(186,85,211), RGB(128,0,128), RGB(216,191,216), RGB(221,160,221), RGB(238,130,238), RGB(255,0,255), RGB(218,112,214), RGB(199,21,133), RGB(219,112,147), RGB(255,20,147), RGB(255,105,180), RGB(255,182,193), RGB(255,192,203), RGB(250,235,215), RGB(245,245,220), RGB(255,228,196), RGB(255,235,205), RGB(245,222,179), RGB(255,248,220), RGB(255,250,205), RGB(250,250,210), RGB(255,255,224), RGB(139,69,19), RGB(160,82,45), RGB(210,105,30), RGB(205,133,63), RGB(244,164,96), RGB(222,184,135), RGB(210,180,140), RGB(188,143,143), RGB(255,228,181), RGB(255,222,173), RGB(255,218,185), RGB(255,228,225), RGB(255,240,245), RGB(250,240,230), RGB(253,245,230), RGB(255,239,213), RGB(255,245,238), RGB(245,255,250), RGB(112,128,144), RGB(119,136,153), RGB(176,196,222), RGB(230,230,250), RGB(255,250,240), RGB(240,248,255), RGB(248,248,255), RGB(240,255,240), RGB(255,255,240), RGB(240,255,255), RGB(255,250,250), RGB(0,0,0), RGB(105,105,105), RGB(128,128,128), RGB(169,169,169), RGB(192,192,192), RGB(211,211,211), RGB(220,220,220), RGB(245,245,245), RGB(255,255,255) }
    colorsCycleCurrent = 1
    effectTick = 0
    randColor = 0
end

function OnTick()
    tick = GetTickCount()
	if not Menu.Main.LagFree then _G.DrawCircle = _G.oldDrawCircle end

	if Menu.Main.LagFree then
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
    if Menu.Effects.RainbowRandom then
        if tick > effectTick + Menu.Effects.EffectSpeed then
            randColor = math.random(1, #colorsCycle)
            effectTick = tick
        end

        color = colorsCycle[randColor]
    end

    if Menu.Effects.Rainbow then
        if tick > effectTick + Menu.Effects.EffectSpeed then
            colorsCycleCurrent = colorsCycleCurrent + 1
            if colorsCycleCurrent > #colorsCycle then
                colorsCycleCurrent = 1
            end
            effectTick = tick
        end

        color = colorsCycle[colorsCycleCurrent]        
    end

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
    if Menu.Effects.Strobe and not IsTickReady(2) then return end

    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)

    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius

    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))

    if sPos and sPos.x and sPos.y and OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        if Menu.Main.OtherScripts and color and type(color) ~= "userdata" then
            local c1, c2, c3 = hex2rgb(dec2hex(color))
            if c1 and c2 and c3 then
                color = RGB(c1, c2, c3)
            end
        end

        DrawLFCCircleNextLvl(x, y, z, radius, Menu.Main.LineWidth, color, Menu.Main.LineLength) 
    end
end