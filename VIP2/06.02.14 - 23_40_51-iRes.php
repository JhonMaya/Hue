<?php exit() ?>--by iRes 5.71.65.218
if myHero.charName ~= "Singed" then return end 

local singed = {}

function OnLoad()
singed = scriptConfig("Singed Exploit", "Singed")
singed:addParam("Q", "Q Exploit", SCRIPT_PARAM_ONOFF, false)
end

function OnTick()
if not qOn and myHero:CanUseSpell(_Q) == READY and IsKeyDown(GetKey('U')) then CastSpell(_Q) end
if not qOn and singed.Q and myHero:CanUseSpell(_Q) == READY then CastSpell(_Q) 
end
if IsKeyDown(GetKey('T')) and singed.Q == true then singed.Q = false elseif IsKeyDown(GetKey('T')) and singed.Q == false then singed.Q = true end 
end