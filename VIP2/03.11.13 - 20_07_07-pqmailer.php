<?php exit() ?>--by pqmailer 141.101.98.15
class 'Plugin'
if myHero.charName ~= "Olaf" then return end
local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = AutoCarry.Helper:GetClasses()

function Plugin:__init()
	tpQ = ProdictManager.GetInstance():AddProdictionObject(_Q, 1000, 1600, 0.25, 0, myHero, function(unit, pos, spell) if GetDistance(unit) <= 1000 and myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, pos.x, pos.z) end end)
	Crosshair:SetSkillCrosshairRange(1000)
	PrintChat("PQOlaf loaded!")
end

function Plugin:OnTick()
	local Target = Crosshair:GetTarget()
	if (Menu.ksQ and myHero:CanUseSpell(_Q) == READY) or (Menu.ksE and myHero:CanUseSpell(_E) == READY) then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, 325) and getDmg("E", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_E) == READY then
				CastSpell(_E, Enemy)
			elseif ValidTarget(Enemy, 1000) and getDmg("Q", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_Q) == READY then
				tpQ:EnableTarget(Enemy, true, myHero)
			end
		end
	end
	if ValidTarget(Target) then
		local Distance = myHero:GetDistance(Target)
		if ((Keys.AutoCarry and Menu.useQcombo) or ((Keys.MixedMode or Keys.LaneClear) and Menu.useQharass)) and myHero:CanUseSpell(_Q) == READY and Distance <= 1000 then
			tpQ:EnableTarget(Target, true, myHero)
		end
		if Keys.AutoCarry and myHero:CanUseSpell(_W) == READY and Distance <= 200 then
			CastSpell(_W)
		end
		if Keys.AutoCarry and Menu.useEcombo and myHero:CanUseSpell(_E) == READY and Orbwalker:IsAfterAttack() and Distance <= 200 then
			CastSpell(_E, Target)
		end
	end
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "PQOlaf")
Menu:addParam("useQcombo", "Use Q in Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("useEcombo", "Use E in Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("useQharass", "Use Q in MixedMode/LaneClear", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
Menu:addParam("ksQ", "KS with Q", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("ksE", "KS with E", SCRIPT_PARAM_ONOFF, true)