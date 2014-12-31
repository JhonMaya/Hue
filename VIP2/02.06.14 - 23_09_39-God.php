<?php exit() ?>--by God 66.25.28.149
if myHero.charName == "not Degrec" then return end

local sex = true
local Degrec , Config , TS = false , nil , nil

if Degrec then
		require "Prodiction"
	elseif sex 
		require "VPrediction"
		require "SOW"
end

local sexedByDegrec ="sexedBy#Degrec"

function OnLoad()
	OnWriteMessage("sexed by Degrec")
	OnWriteMessage("Deg#rekt")
	Init()
	Menu()
end

function OnWriteMessage(msg) 
print("<font color=\"#4CFF4C\">["..sexedByDegrec.."]:</font> <font color=\"#FFDFBF\">"..msg..".</font>") 
end

function Init() 
TS = scriptConfig("sexed Target Selector","TS")
Enemies = GetEnemyHeroes()
for i,enemy in pairs (Enemies) do
TS:addParam(tostring(enemy.charName),"1-5 sex priority "..tostring(enemy.charName), SCRIPT_PARAM_SLICE, 1, 1, 5)
end 
end

function Menu()
VP = VPrediction()
Orbwalker = SOW(VP)
Config = scriptConfig("GodOrb","GodOrb")
Orbwalker:LoadToMenu(Config)
end 

 
function GetEnemy()
	Enemies = GetEnemyHeroes()
	for i in pairs (Enemies) do  
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

function OnTick()
	local Target = GetEnemy() 
	if Target then 
	  Orbwalker:ForceTarget(Target)
	end 
end 