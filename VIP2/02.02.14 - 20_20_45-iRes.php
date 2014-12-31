<?php exit() ?>--by iRes 176.27.251.246
if myHero.charName ~= "Singed" then return end 

function OnTick()
 if not qOn and myHero:CanUseSpell(_Q) == READY and IsKeyDown(GetKey('T')) then CastSpell(_Q) end
end