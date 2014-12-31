<?php exit() ?>--by x7x 89.70.161.4
class 'Plugin'
if myHero.charName ~= "Irelia" then return end
local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = AutoCarry.Helper:GetClasses()
---------------------------------- START UPDATE ---------------------------------------------------------------
local versionGOE = 0.9 -- current version
local SCRIPT_NAME_GOE = "aIrelia"
---------------------------------------------------------------------------------------------------------------
local needUpdate_GOE = false
local needRun_GOE = true
local URL_GOE = "http://anonymous-dev.y0.pl/aIrelia.lua" --"http://dlr5668.cuccfree.org/"..SCRIPT_NAME_GOE..".lua"
local PATH_GOE = BOL_PATH.."Scripts/SidasAutoCarryPlugins\\"..SCRIPT_NAME_GOE..".lua"
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#E97FA5'>AUTOUPDATER: There is a new version of "..SCRIPT_NAME_GOE..". Don't F9 till done...</font>") 
		needUpdate_GOE = true  
	end
end
function UpdateScriptGOE()
	if needRun_GOE then
		needRun_GOE = false
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("anonymous-dev.y0.pl", "aIreliaVer.lua", CheckVersionGOE) end
	end

	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
                if FileExist(PATH_GOE) then
                    print("<font color='#E97FA5'>AUTOUPDATER: Script updated! Reload scripts to use new version!</font>")
                end
            end)
	end
end
AddTickCallback(UpdateScriptGOE)
---------------------------------- END UPDATE -----------------------------------------------------------------

local Target
local Enemies = Helper.EnemyTable
local ResetAA = false
local LastAATarget = nil
local HaveItemBuff = false
local ItemCooldown = 0

function Plugin:__init()
	Crosshair:SetSkillCrosshairRange(650)
end

function Plugin:OnTick()
	if LastAATarget ~= nil and not LastAATarget.valid then LastAATarget = nil end
	local CanQ = (myHero:CanUseSpell(_Q) == READY)
	local CanW = (myHero:CanUseSpell(_W) == READY)
	local CanE = (myHero:CanUseSpell(_E) == READY)
	local CanR = (myHero:CanUseSpell(_R) == READY)
	sTarget = Crosshair:GetTarget()
	if Menu.ComboHelperMenu.ComboHelper then
		if sTarget ~= nil then
			if Keys.AutoCarry then
				if sTarget.valid and ValidTarget(sTarget) then
					if CountEnemyHeroInRange(1200) > 1 then
						if CanQ and GetDistance(sTarget) <= 650 and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
					else
						local MyDistance = GetDistance(sTarget, myHero)
						if MyDistance > 250 then
							local BestTarget = sTarget
							local enemyMinions = minionManager(MINION_ENEMY, 650, myHero)
							if enemyMinions ~= nil then
								for i, Minion in pairs(enemyMinions.objects) do
									if Minion ~= nil and Minion.valid and not Minion.dead then
										local DMG = getDmg("Q", Minion, myHero)
										if DMG >= Minion.health then
											if GetDistance(Minion, Enemy) < MyDistance then BestTarget = Minion end
										end
									end
								end
							end
							if BestTarget ~= nil then
								if CanQ and GetDistance(BestTarget) <= 650 then
									if Menu.ComboHelperMenu.useQ then CastSpell(_Q, BestTarget) end
									if GetDistance(BestTarget, Enemy) > 250 and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
								end
							end
						else
							if CanQ and Orbwalker:IsAfterAttack() and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
						end
					end
					if GetDistance(sTarget) < 425 and CanE and Menu.ComboHelperMenu.useE then CastSpell(_E, sTarget) end
					if GetDistance(sTarget) < 150 and CanW and Menu.ComboHelperMenu.useW then CastSpell(_W) end
				end
			end
		else
			local enemyMinions = minionManager(MINION_ENEMY, 650, myHero)
			if Keys.AutoCarry and CanQ and enemyMinions ~= nil then
				for i, Minion in pairs(enemyMinions.objects) do
					if Minion ~= nil and Minion.valid and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) then
						for j, Enemy in pairs(Enemies) do
							if Enemy ~= nil and Enemy.valid and not Enemy.dead and ValidTarget(Enemy) and GetDistance(Minion, Enemy) < 650 then
								if Menu.ComboHelperMenu.useQ then CastSpell(_Q, Minion) end
								if GetDistance(Minion, Enemy) > 350 and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
								PrintChat("FOUND")
							end
						end
					end
				end
			end
		end
	end
	if Menu.ClearHelperMenu.enabled then
		if Keys.LaneClear then
			local enemyMinions = minionManager(MINION_ENEMY, 650, myHero)
			for i, Minion in pairs(enemyMinions.objects) do
				if Menu.ClearHelperMenu.useQ and CanQ then
					if Minion ~= nil and Minion.valid and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) then
						if myHero.mana/myHero.maxMana*100 >= Menu.ClearHelperMenu.minMana then CastSpell(_Q, Minion) end
					end
				end
			end
		end
	end
	if Menu.FarmHelperMenu.enabled then
		if Keys.LastHit then
			local enemyMinions = minionManager(MINION_ENEMY, 650, myHero)
			for i, Minion in pairs(enemyMinions.objects) do
				if Menu.ClearHelperMenu.useQ and CanQ then
					if Minion ~= nil and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) and not CanShoot() then
						if LastAATarget ~= nil and LastAATarget ~= Minion or LastAATarget == nil then
							if myHero.mana/myHero.maxMana*100 >= Menu.ClearHelperMenu.minMana then CastSpell(_Q, Minion) end
						end
					end
					if Minion ~= nil and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) and GetDistance(Minion) > 350 then
						if myHero.mana/myHero.maxMana*100 >= Menu.ClearHelperMenu.minMana then CastSpell(_Q, Minion) end
					end
				end
			end
		end
	end
	if Menu.OtherMenu.Rmanager and VIP_USER and CanR and sTarget ~= nil and Keys.AutoCarry then
		if GetInventoryHaveItem(3057) or GetInventoryHaveItem(3078) or GetInventoryHaveItem(3025) then
			if HaveItemBuff == false and ItemCooldown <= GetTickCount() then
				if GetDistance(sTarget) < 300 then CastSpell(_R, sTarget.x, sTarget.z) end
			end
		end
	end
end

function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "IreliaBasicAttack" or unit.isMe and spell.name == "IreliaCritAttack" or unit.isMe and spell.name == "IreliaBasicAttack2" then
		LastAATarget = spell.target
		if HaveItemBuff == true then
			HaveItemBuff = false
			ItemCooldown = GetTickCount() + 2010
		end
	end
	if unit.isMe and spell.name == "IreliaGatotsu" or unit.isMe and spell.name == "IreliaHitenStyle" or unit.isMe and spell.name == "IreliaEquilibriumStrike" or unit.isMe and spell.name == "IreliaTranscendentBlades" then
		if GetInventoryHaveItem(3057) or GetInventoryHaveItem(3078) or GetInventoryHaveItem(3025) then
			if ItemCooldown <= GetTickCount() then
				HaveItemBuff = true
			end
		end
	end
end

function Plugin:OnDraw()
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 650, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 425, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawRrange then DrawCircle(myHero.x, myHero.y, myHero.z, 1000, 0xFF80FF00) end
end

function round(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end

function CanShoot()
	if Orbwalker:GetNextAttackTime() >= GetTickCount() then return false else return true end
end
-- SHEN: 3057, TF: 3078, Gauntlet: 3025
-- GetInventorySlotItem
Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "aIrelia v0.9")
Menu:addSubMenu("General information", "InfoMenu")
Menu.InfoMenu:addParam("Information1", "aIrelia v0.9 created by Anonymous", SCRIPT_PARAM_INFO, "")
Menu.InfoMenu:addParam("Information2", "Enjoy and report bugs on forums!", SCRIPT_PARAM_INFO, "")
Menu:addSubMenu("Combo settings [SBTW]", "ComboHelperMenu")
Menu.ComboHelperMenu:addParam("ComboHelper","Enabled", SCRIPT_PARAM_ONOFF, true)
Menu.ComboHelperMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
Menu.ComboHelperMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
Menu.ComboHelperMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
--Menu.ComboHelperMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, true)
Menu:addSubMenu("LastHit mode settings", "FarmHelperMenu")
Menu.FarmHelperMenu:addParam("enabled","Enabled", SCRIPT_PARAM_ONOFF, true)
Menu.FarmHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
Menu.FarmHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
Menu:addSubMenu("LaneClear mode settings", "ClearHelperMenu")
Menu.ClearHelperMenu:addParam("enabled","Enabled", SCRIPT_PARAM_ONOFF, true)
Menu.ClearHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
Menu.ClearHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
--Menu.ClearHelperMenu:addParam("useR","Use R to kill minions", SCRIPT_PARAM_ONOFF, true)
Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
--Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, true)
Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, false)
Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
Menu:addSubMenu("Other settings", "OtherMenu")
Menu.OtherMenu:addParam("Rmanager", "Use R to execute passive of:", SCRIPT_PARAM_ONOFF, true)
Menu.OtherMenu:addParam("trinitymanager","- TrinityForce", SCRIPT_PARAM_INFO, "")
Menu.OtherMenu:addParam("sheenmanager","- Sheen", SCRIPT_PARAM_INFO, "")
Menu.OtherMenu:addParam("gauntletmanager","- Iceborn Gauntlet", SCRIPT_PARAM_INFO, "")