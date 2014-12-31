<?php exit() ?>--by ahmedzarga23 197.0.240.163
if myHero.charName ~= "Singed" then return end
local autoQ = false
function OnLoad()
Menu = scriptConfig("SingedInvisQ", "SingedInvisQ")
Menu:addParam("InvisQ", "InvisQ!", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T")) -- its toggle
end
function OnTick()
if Menu.InvisQ then
autoQ = not autoQ
Menu.InvisQ = false
end
if autoQ and myHero:CanUseSpell(_Q) == READY then
Packet("S_CAST", {spellId = _Q, targetNetworkId = myHero.networkID}):send()
end
end 