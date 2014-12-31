<?php exit() ?>--by mrsithsquirrel1 121.98.53.201

if myHero.charName ~= "Hecarim" then return end

function OnTick()
	if Menu.spamSkills then
		if (myHero:CanUseSpell(_Q) == READY) then CastSpell(_Q) end

		if (myHero:CanUseSpell(_W) == READY) then CastSpell(_W) end
	end
end

function OnLoad()
	Menu = scriptConfig("URF Hecca", "URF Hecca")
	Menu:addParam("spamSkills", "Spam Skills",  SCRIPT_PARAM_ONKEYDOWN, false, string.byte("D"))
end