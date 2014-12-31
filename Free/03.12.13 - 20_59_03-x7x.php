<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Irelia" then return end

---------------------------------- START UPDATE ---------------------------------------------------------------
local versionGOE = 0.92 -- current version
local SCRIPT_NAME_GOE = "aIrelia"
---------------------------------------------------------------------------------------------------------------
local needUpdate_GOE = false
local needRun_GOE = true
local URL_GOE = "http://anonymous-dev.y0.pl/aIrelia2.lua" --"http://dlr5668.cuccfree.org/"..SCRIPT_NAME_GOE..".lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..SCRIPT_NAME_GOE..".lua"
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
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("anonymous-dev.y0.pl", "aIrelia2Ver.lua", CheckVersionGOE) end
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
local Enemies = {}
local ResetAA = false
local LastAATarget = nil
local HaveItemBuff = false
local ItemCooldown = 0
local NextAATick = 0
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,650,DAMAGE_PHYSICAL,false)
local EndOfWindupTick = 0
local enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)

function OnLoad()-- SHEN: 3057, TF: 3078, Gauntlet: 3025
	-- GetInventorySlotItem
	Menu = scriptConfig("aIrelia v0.92", "airelia")
	ts.name = "aIrelia"
	Menu:addTS(ts)
	Menu:addSubMenu("General information", "InfoMenu")
	Menu.InfoMenu:addParam("Information1", "aIrelia v0.92 created by Anonymous", SCRIPT_PARAM_INFO, "")
	Menu.InfoMenu:addParam("Information2", "Enjoy and report bugs on forums!", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu("Combo settings [SBTW]", "ComboHelperMenu")
	Menu.ComboHelperMenu:addParam("ComboHelper","Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.ComboHelperMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
	--Menu.ComboHelperMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("LastHit mode settings", "FarmHelperMenu")
	Menu.FarmHelperMenu:addParam("enabled","Lasthitting helper key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	Menu.FarmHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu.FarmHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
	Menu:addSubMenu("LaneClear mode settings", "ClearHelperMenu")
	Menu.ClearHelperMenu:addParam("enabled","Laneclear helper key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
	Menu.ClearHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu.ClearHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
	--Menu.ClearHelperMenu:addParam("useR","Use R to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
	Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
	--Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawKillable","Draw circle on minions", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("Information32", "that are killable with Q", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu("Other settings", "OtherMenu")
	Menu.OtherMenu:addParam("Rmanager", "Use R to execute passive of:", SCRIPT_PARAM_ONOFF, true)
	Menu.OtherMenu:addParam("trinitymanager","- TrinityForce", SCRIPT_PARAM_INFO, "")
	Menu.OtherMenu:addParam("sheenmanager","- Sheen", SCRIPT_PARAM_INFO, "")
	Menu.OtherMenu:addParam("gauntletmanager","- Iceborn Gauntlet", SCRIPT_PARAM_INFO, "")
	-- add TS
	for i=1, heroManager.iCount do
		local EnemyHero = heroManager:getHero(i)
		if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
	end
end

function OnTick()
	ts:update()
	enemyMinions:update()
	if LastAATarget ~= nil and not LastAATarget.valid then LastAATarget = nil end
	local CanQ = (myHero:CanUseSpell(_Q) == READY)
	local CanW = (myHero:CanUseSpell(_W) == READY)
	local CanE = (myHero:CanUseSpell(_E) == READY)
	local CanR = (myHero:CanUseSpell(_R) == READY)
	sTarget = ts.target
	if Menu.ComboHelperMenu.ComboHelper then
		if sTarget ~= nil then
			if sTarget.valid and ValidTarget(sTarget) then
				if CountEnemyHeroInRange(1200) > 1 then
					if CanQ and GetDistance(sTarget) <= 650 and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
				else
					local MyDistance = GetDistance(sTarget, myHero)
					if MyDistance > 250 then
						local BestTarget = sTarget
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
						if CanQ and IsAfterAttack() and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
					end
				end
				if GetDistance(sTarget) < 425 and CanE and Menu.ComboHelperMenu.useE then CastSpell(_E, sTarget) end
				if GetDistance(sTarget) < 150 and CanW and Menu.ComboHelperMenu.useW then CastSpell(_W) end
			end
		else
			if CanQ and enemyMinions ~= nil then
				for i, Minion in pairs(enemyMinions.objects) do
					if Minion ~= nil and Minion.valid and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) then
						for j, Enemy in pairs(Enemies) do
							if Enemy ~= nil and Enemy.valid and not Enemy.dead and ValidTarget(Enemy) and GetDistance(Minion, Enemy) < 650 then
								if Menu.ComboHelperMenu.useQ then CastSpell(_Q, Minion) end
								if GetDistance(Minion, Enemy) > 350 and Menu.ComboHelperMenu.useQ then CastSpell(_Q, sTarget) end
							end
						end
					end
				end
			end
		end
	end
	if Menu.ClearHelperMenu.enabled then
		for i, Minion in pairs(enemyMinions.objects) do
			if Menu.ClearHelperMenu.useQ and CanQ then
				if Minion ~= nil and Minion.valid and not Minion.dead and Minion.health < getDmg("Q", Minion, myHero) then
					if myHero.mana/myHero.maxMana*100 >= Menu.ClearHelperMenu.minMana then CastSpell(_Q, Minion) end
				end
			end
		end
	end
	if Menu.FarmHelperMenu.enabled then
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
	if Menu.OtherMenu.Rmanager and VIP_USER and CanR and sTarget ~= nil and Menu.ComboHelperMenu.ComboHelper then
		if GetInventoryHaveItem(3057) or GetInventoryHaveItem(3078) or GetInventoryHaveItem(3025) then
			if HaveItemBuff == false and ItemCooldown <= GetTickCount() then
				if GetDistance(sTarget) < 300 then CastSpell(_R, sTarget.x, sTarget.z) end
			end
		end
	end
end

function IsAfterAttack()
	if GetTickCount() > EndOfWindupTick and GetTickCount() < NextAATick then return true else return false end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "IreliaBasicAttack" or unit.isMe and spell.name == "IreliaCritAttack" or unit.isMe and spell.name == "IreliaBasicAttack2" then
		LastAATarget = spell.target
		NextAATick = (spell.animationTime*1000)+GetTickCount()
		EndOfWindupTick = spell.windUpTime*1000+GetTickCount()
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

function OnDraw()
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 650, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 425, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawRrange then DrawCircle(myHero.x, myHero.y, myHero.z, 1000, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and Menu.DrawHelperMenu.drawKillable then
		for i, Minion in pairs(enemyMinions.objects) do
			if Minion ~= nil and Minion.valid and Minion.team ~= myHero.team and not Minion.dead then
				if getDmg("Q", Minion, myHero) >= Minion.health then DrawCircle(Minion.x, Minion.y, Minion.z, 75, 0xFF80FF00) end
			end
		end
	end
end

function round(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end

function CanShoot()
	if NextAATick >= GetTickCount() then return false else return true end
end