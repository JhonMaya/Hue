<?php exit() ?>--by Kain 67.212.81.202
--[[
    I'M ALISTAR 2.0 by Klokje
    ========================================================================
 
    Info
    This is a Alistar script. This is one of the script I made for the "I'M SUPPORT" serie. I'm a 1800 main support.


     Changelog
    ~~~~~~~~~
   
	2.0 -           New Initial release(written from start)

]]


-- Load required libraries -----------------------------------------------------
require "MapPosition"


-- Globals ---------------------------------------------------------------------
if myHero.charName ~= "Alistar" then return end

local ts

--Range
local Qrange = 380
local Wrange = 650
local Erange = 287
local Rrange = 0

local noQ = false

local nextTick = 0

-- Code ------------------------------------------------------------------------

function OnLoad()
	PrintChat(" >> I'M ALISTAR 2.0")

	Config = scriptConfig("I'M ALISTAR", "Alistar")      
	Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32) -- Spacebar
	Config:addParam("MoveToMouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	Config:addParam("DrawCircle", "Draw W-Range", SCRIPT_PARAM_ONOFF, false)
	Config:addParam("DrawCircletarget", "Draw target selection", SCRIPT_PARAM_ONOFF, true)

	ts = TargetSelector(TARGET_NEAR_MOUSE,1500,DAMAGE_MAGIC)
    ts.name = "Alistar"
    Config:addTS(ts)

    mapPosition = MapPosition()
end



function OnTick()
	ts:update()

    if nextTick < GetTickCount()  and noQ then 
        noQ = false
    end
end

function OnDraw()
	if myHero.dead then return end

    if ts.target ~= nil and Config.DrawCircletarget then
        if checkMana() then
            DrawCircle(ts.target.x, ts.target.y, ts.target.z, getHitBoxRadius(ts.target), 0xFFDB0A)
        else
            DrawCircle(ts.target.x, ts.target.y, ts.target.z, getHitBoxRadius(ts.target), 0xFF0038)
        end
    end
 
    if Config.DrawCircle then
        DrawCircle(myHero.x, myHero.y, myHero.z, Wrange, 0x33FFCC)
    end


    if Config.Combo then
        combo()
    end
end

function checkMana()
    return (myHero:GetSpellData(_Q).mana  + myHero:GetSpellData(_W).mana)  < myHero.mana
end


function getHitBoxRadius(target)
    return GetDistance(target, target.minBBox)/2
end


function moveToMouse()
	if GetDistance(mousePos)>=Qrange and Config.MoveToMouse then
		myHero:MoveTo(mousePos.x,mousePos.z)
	end
end


function combo()
	if ts.target == nil then moveToMouse() return end

    local PushPos = ts.target + (Vector(ts.target) - myHero):normalized()*650

    if noQ and mapPosition:intersectsWall(LineSegment(Point(ts.target.x, ts.target.z), Point(PushPos.x, PushPos.z))) and mapPosition:inWall(Point(PushPos.x, PushPos.z)) then 
        if myHero:CanUseSpell(_W) == READY and GetDistance(ts.target)<=Wrange and ((myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target)>Qrange) or myHero:CanUseSpell(_Q) ~= READY) then
            nextTick = GetTickCount() + 500
            noQ = true
            CastSpell(_W,ts.target)
        end
        DrawCircle(PushPos.x, PushPos.y, PushPos.z, getHitBoxRadius(ts.target), 0xFFDB0A)
    else 
        DrawCircle(PushPos.x, PushPos.y, PushPos.z, getHitBoxRadius(ts.target), 0xFF0038)
    end

	if myHero:CanUseSpell(_W) == READY and  myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target)<=Wrange and GetDistance(ts.target)>Qrange and checkMana() then
        CastSpell(_W,ts.target)
    end

    if myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target)<=Qrange and not noQ then
    	CastSpell(_Q)
    end 

    if GetDistance(ts.target)>=250 then 
    	moveToMouse()
    end
end

--UPDATEURL=
--HASH=B1DD1947B9D2783966284D068BD54F24
