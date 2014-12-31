<?php exit() ?>--by ahmedzarga23 197.0.194.187
require 'SOW'
require 'VPrediction'

local Config
local TS

function OnWriteMessage(msg) 
print("<font color=\"#4CFF4C\">["..myHero.charName.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") 
end

function OnLoad()
Init()
Menu()
OnWriteMessage('Simple Orbwalker Loaded')
end

function Init() 
TS = scriptConfig("TS","TS")
Enemies = GetEnemyHeroes()
for i,enemy in pairs (Enemies) do
	TS:addParam(tostring(enemy.charName),tostring(enemy.charName), SCRIPT_PARAM_SLICE, 1, 1, 5)
end 
end 

function TargetSelector() 
	Enemies = GetEnemyHeroes()
	for i,enemy in pairs (Enemies) do
		if not enemy.dead and ValidTarget(enemy,700) and TS[enemy.charName] == 1 then 
			return enemy
		 
		elseif not enemy.dead and ValidTarget(enemy,700) and TS[enemy.charName] == 2 then 
			return enemy
		 
		elseif not enemy.dead and ValidTarget(enemy,700) and TS[enemy.charName] == 3 then 
			return enemy 
		 
	    elseif  not enemy.dead and ValidTarget(enemy,700) and TS[enemy.charName] == 4 then 
			return enemy
		
		elseif not enemy.dead and ValidTarget(enemy,700) and TS[enemy.charName] == 5 then 
			return enemy
		  end
	end 
end 


function Menu()
VP = VPrediction()
Orbwalker = SOW(VP)
Config = scriptConfig("Orbwalker","Orbwalker")
Orbwalker:LoadToMenu(Config)
Config:addParam("Draw", "Draw Auto Attack Range", SCRIPT_PARAM_ONOFF, true)
Config:addParam("Draw2", "Draw Circles around Targets", SCRIPT_PARAM_ONOFF, true)
end 

function OnTick()
	local Target = TargetSelector() 
	if Target then 
	  Orbwalker:ForceTarget(Target)
	end 
end 

function OnDraw() 
if Config.Draw then 
local Target = TargetSelector()
if Target then 
DrawCircle3D(Target.x, Target.y, Target.z,150,1, ARGB(255, 0, 255, 255))
end 
DrawCircle3D(myHero.x, myHero.y, myHero.z,myHero.range,1, ARGB(255, 0, 255, 255))
end 
end 


