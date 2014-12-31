<?php exit() ?>--by ahmedzarga23 197.0.191.143
require 'SOW'
require 'VPrediction'

local Config

function OnWriteMessage(msg) 
print("<font color=\"#4CFF4C\">["..myHero.charName.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") 
end

function OnLoad()
Init()
Menu()
OnWriteMessage('Simple Orbwalker Loaded')
end

function Init() 
Ts = TargetSelector(TARGET_PRIORITY,700,DAMAGE_PHYSICAL)
Ts.name = tostring(myHero.charName).." Orbwalker"

end 

function Menu()
VP = VPrediction()
Orbwalker = SOW(VP)
Config = scriptConfig("Orbwalker","Orbwalker")
Config:addTS(Ts) 
Orbwalker:LoadToMenu(Config)
Config:addParam("Draw", "Draw Auto Attack Range", SCRIPT_PARAM_ONOFF, true)
Config:addParam("Draw2", "Draw Circles around Targets", SCRIPT_PARAM_ONOFF, true)
end 

function OnTick()
Ts:update()
local Target = Ts.target
if Target then
   Orbwalker:ForceTarget(Target)
	end 
end 

function OnDraw() 
Ts:update()
local Target = Ts.target
if Target and Config.Draw2 then 
DrawCircle3D(Target.x, Target.y, Target.z,150,1, ARGB(255, 0, 255, 255))
end 
if Config.Draw then 
DrawCircle3D(myHero.x, myHero.y, myHero.z,myHero.range,1, ARGB(255, 0, 255, 255))
end 
end 