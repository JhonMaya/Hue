<?php exit() ?>--by pqmailer 217.82.4.23
if myHero.charName ~= "Akali" then return end

if debug.getinfo(GetUser).linedefined > -1 then PrintChat("<font color='#FF0000'> Access Denied! The username: "..GetUser().." has been sent to admins for a review of malicious activity</font>") return end

local AuthTable = {"pqmailer", "toxic 123"}
local authed = false

for _, user in pairs(AuthTable) do
	if GetUser() == user then
		authed = true
		break
	end
end

local ts

function OnLoad()
	Menu = scriptConfig("PQAkali", "pqakali")
	Menu:addParam("focusSelect", "Focus selected target", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))
	Menu:addParam("ksE", "KS with E", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ksQ", "KS with Q", SCRIPT_PARAM_ONOFF, true)

	ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 800, DAMAGE_MAGIC)
	ts.name = "Akali"
	Menu:addTS(ts)

	PrintChat("PQAkali loaded!")
end

function OnTick()
	if not authed then print("not authed") return end
	ts.targetSelected = Menu.focusSelect
	ts:update()

	if Menu.ksQ or Menu.ksE or Menu.ksR then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				local Distance = myHero:GetDistance(enemy)
				if myHero:CanUseSpell(_E) == READY and Menu.ksE and getDmg("E", enemy, myHero) >= enemy.health and Distance <= 325 then
					CastSpell(_E)
				elseif myHero:CanUseSpell(_R) == READY and Menu.ksR and getDmg("R", enemy, myHero) >= enemy.health and Distance <= 800 then
					CastSpell(_R, enemy)
				elseif myHero:CanUseSpell(_Q) == READY and Menu.ksQ and getDmg("Q", enemy, myHero) >= enemy.health and Distance <= 600 then
					CastSpell(_Q, enemy)
				end
			end
		end
	end
	if Menu.combo or Menu.harass then
		if ValidTarget(ts.target) then
			local Distance = myHero:GetDistance(ts.target)
			if Distance <= 700 and Menu.combo and GetInventoryItemIsCastable(3146) then
				CastItem(3146, ts.target)
			end
			if Distance <= 600 then
				if myHero:CanUseSpell(_Q) == READY and Distance <= 600 then
					CastSpell(_Q, ts.target)
				end
				if myHero:CanUseSpell(_R) == READY and Distance <= 800 and Menu.combo then
					CastSpell(_R, ts.target)
				end
				if myHero:CanUseSpell(_E) == READY and Distance <= 325 then
					CastSpell(_E)
				end
			else
				if myHero:CanUseSpell(_R) == READY and Distance <= 800 and Menu.combo then
					CastSpell(_R, ts.target)
				end
				if myHero:CanUseSpell(_Q) == READY and Distance <= 600 then
					CastSpell(_Q, ts.target)
				end
				if myHero:CanUseSpell(_E) == READY and Distance <= 325 then
					CastSpell(_E)
				end
			end
		end
	end
end