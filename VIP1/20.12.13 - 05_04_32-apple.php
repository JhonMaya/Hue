<?php exit() ?>--by apple 84.107.12.248
--[[ iYas by Apple ]]--

if myHero.charName ~= "Yasuo" then return end

if not VIP_USER then print("iYas: VIP Script - Loading Aborted") return end

require "Prodiction"

--[[ Config ]]--

local HK1 = string.byte("A")
local HK2 = string.byte("T")
local HK3 = string.byte("C")
local HK4 = string.byte("X")

local minHitChance = 0.3

--[[ Constants ]]--

local QRange, QSpeed, QDelay, QWidth, QRadius = 450, 1800, 0.250, 50, 375
local TQRange, TQSpeed, TQDelay, TQWidth = 900, 1200, 0.250, 50
local ERange, EDelay = 475, 0.250
local RRange = 1300
local AARange = 300

--[[ Script Variables ]]--

local ts = TargetSelector(TARGET_LOW_HP_PRIORITY, QRange, DAMAGE_PHYSICAL, false)
local tsFar = TargetSelector(TARGET_LOW_HP_PRIORITY, 2*ERange, DAMAGE_PHYSICAL, false)
local tpQ = TargetPredictionVIP(QRange, QSpeed, QDelay, QWidth)
local tpTQ = TargetPredictionVIP(TQRange, TQSpeed, TQDelay, TQWidth)
local tpE = TargetPredictionVIP(1000, math.huge, EDelay * 1000, 0)
local tpPro = ProdictManager.GetInstance()
local tpProQ = tpPro:AddProdictionObject(_Q, QRange, QSpeed, QDelay, QWidth, myHero)
local tpProTQ = tpPro:AddProdictionObject(_Q, TQRange, TQSpeed, TQDelay, TQWidth, myHero)
local tpProE = tpPro:AddProdictionObject(_E, 1000, math.huge, EDelay, 0, myHero)

local DamageResults = {}
local updateTextTimers = {}
local enemyMinions = {}
local KnockedUp = {}
local DashedUnits = {}
local Turrets = {}
local isDashing = false
local TQReady = false
local SheenBuff = false
local EStacks = 0
local ShotCast = 0
local NextShot = 0
local windUpTime = 0
local animationTime = 0
local DashPos = nil

--[[ Core Callbacks ]]--

function OnLoad()
	iYasConfig = scriptConfig("iYas - The Fruity Swordman", "iYas")

	iYasConfig:addSubMenu("Hotkeys", "hotkeys")
	iYasConfig.hotkeys:addParam("pewpew","PewPew!", SCRIPT_PARAM_ONKEYDOWN, false, HK1)
	iYasConfig.hotkeys:addParam("poke", "Poke!", SCRIPT_PARAM_ONKEYDOWN, false, HK2)
	iYasConfig.hotkeys:addParam("Farm", "Munching Minions!", SCRIPT_PARAM_ONKEYDOWN, false, HK2)

	iYasConfig:addSubMenu("PewPew Settings", "pewpew")
	iYasConfig.pewpew:addParam("orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("UseQ", "Steel Tempest", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("UseE", "Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("UseUlt", "LastBreath", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("StackE", "Sweeping Blade: Stack", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("RangedDash", "Sweeping Blade: Ranged Dash", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.pewpew:addParam("SafeDash", "Sweeping Blade: Turret Check", SCRIPT_PARAM_ONOFF, true)

	iYasConfig:addSubMenu("Poke Settings", "poke")
	iYasConfig.poke:addParam("orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.poke:addParam("UseQ", "Steel Tempest", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.poke:addParam("UseE", "Sweeping Blade", SCRIPT_PARAM_ONOFF, true)

	iYasConfig:addSubMenu("Munching Settings", "munching")
	iYasConfig.munching:addParam("MTM", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.munching:addParam("UseQ", "Steel Tempest", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.munching:addParam("UseE", "Sweeping Blade", SCRIPT_PARAM_ONOFF, true)

	iYasConfig:addSubMenu("Auto Settings", "auto")
	iYasConfig.auto:addParam("AutoKS", "Auto Killsteal", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.auto:addParam("AutoUlt", "Auto Ultimate", SCRIPT_PARAM_ONOFF, false)

	iYasConfig:addSubMenu("Other Settings", "other")
	iYasConfig.other:addParam("tpPro", "Use Prodiction", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.other:addParam("drawcircles", "Draw Circles", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.other:addParam("damageText", "Kill Text", SCRIPT_PARAM_ONOFF, true)
	iYasConfig.other:addParam("DegrecCombo", "Degrec Combo (Fools Only)", SCRIPT_PARAM_ONOFF, false)

	iYasConfig.hotkeys:permaShow("pewpew")
	iYasConfig.hotkeys:permaShow("harass")

	ts.name = "Yasuo"
	iYasConfig:addTS(ts)

	enemyMinions = minionManager(MINION_ENEMY, ERange, myHero, MINION_SORT_HEALTH_ASC)
	Turrets = GetTurrets()
end

function OnTick()
	ts:update()
	tsFar:update()
	enemyMinions:update()
	DamageCalculations()
	UpdateTurrets()
	CheckForSAC()
	AARange = GetDistance(myHero.minBBox) / 2 + myHero.range

	if myHero.dead then return end
	if iYasConfig.other.damageText then damageText() end
	if iYasConfig.auto.AutoKS then AutoKS() end
	if iYasConfig.hotkeys.pewpew then PewPew() end
	if iYasConfig.hotkeys.poke then Poke() end
	if iYasConfig.hotkeys.Farm then AutoFarm() end
end

function OnDraw()
	if myHero.dead then return end
	if iYasConfig.other.drawcircles then
		if ValidTarget(ts.target) then DrawCircle(ts.target.x, ts.target.y, ts.target.z, 100, 0xFFFF0000) end
		DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0xFF80FF00)
		DrawCircle(myHero.x, myHero.y, myHero.z, RRange, 0xFF80FF00)
		DrawCircle(myHero.x, myHero.y, myHero.z, AARange, 0xFF80FF00)
	end
end

function OnAnimation(unit, animationName)
	if unit.isMe then
		isDashing = animationName == "Spell3"
	end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if GainBuff[buff.name] then GainBuff[buff.name](unit, buff) end
		--if buff.name == "yasuoq3w" then
		--	TQReady = GetTickCount()
		--elseif buff.name == "yasuodashscalar" then
		--	EStacks = buff.stack
		--elseif buff.name == "sheen" then
		--	SheenBuff = true
		--end
	elseif unit.team ~= myHero.team then
		if buff.type == 29 and unit.type == myHero.type then
			if myHero:CanUseSpell(_R) == READY and GetDistance(unit) < RRange then
				if iYasConfig.auto.AutoUlt then
					CastSpell(_R, unit)
				end
			end
			KnockedUp[unit.networkID] = true
		elseif buff.name == "YasuoDashWrapper" then
			DashedUnits[unit.networkID] = GetTickCount() + (11 - myHero:GetSpellData(_E).level) * 1000
		end
	end
end

local GainBuff = {
	yasuoq3w = function(unit, buff) TQReady = GetTickCount() end,
	yasuodashscalar = function(unit, buff) EStacks = buff.stack end,
	sheen = function(unit, buff) SheenBuff = true end,
}

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if LoseBuff[buff.name] then LoseBuff[buff.name](unit, buff) end
		--if buff.name == "yasuoq3w" then
		--	TQReady = false
		--elseif buff.name == "yasuodashscalar" then
		--	EStacks = 0
		--elseif buff.name == "sheen" then
		--	SheenBuff = false
		--end
	elseif buff.type == 29 then
		KnockedUp[unit.networkID] = nil
	elseif buff.name == "YasuoDashWrapper" then
		DashedUnits[unit.networkID] = nil
	end
end

local LoseBuff = {
	yasuoq3w = function(unit, buff) TQReady = false end,
	yasuodashscalar = function(unit, buff) EStacks = 0 end,
	sheen = function(unit, buff) SheenBuff = false end,
}

function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "yasuodashscalar" then EStacks = buff.stack end
end

function OnTowerFocus(turret, target)
	if turret.team ~= myHero.team then
		if Turrets[turret.networkID] then
			Turrets[turret.networkID].target = target
		elseif Turrets[turret.name] then
			Turrets[turret.networkID] = Turrets[turret.name]
			Turrets[turret.name] = nil
			Turrets[turret.networkID].target = target
		else
			for i, Turret in pairs(Turrets) do
				if GetDistance(Turret, turret) < 50 then
					Turrets[turret.networkID] = Turret
					Turrets[i] = nil
					Turrets[turret.networkID].target = target
				end
			end
		end
	end
end

function OnTowerIdle(turret)
	if turret.team ~= myHero.team then
		if Turrets[turret.networkID] then
			Turrets[turret.networkID].target = nil
		elseif Turrets[turret.name] then
			Turrets[turret.networkID] = Turrets[turret.name]
			Turrets[turret.name] = nil
			Turrets[turret.networkID].target = nil
		else
			for i, Turret in pairs(Turrets) do
				if GetDistance(Turret, turret) < 50 then
					Turrets[turret.networkID] = Turret
					Turrets[i] = nil
					Turrets[turret.networkID].target = nil
				end
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name:lower():find("attack") then
			if spell.windUpTime and spell.animationTime then
				ShotCast = GetTickCount() + spell.windUpTime * 1000 - GetLatency() / 2
				NextShot = GetTickCount() + spell.animationTime * 1000
				windUpTime = spell.windUpTime
				animationTime = spell.animationTime
			end
		elseif spell.name == myHero:GetSpellData(_E).name then
			ShotCast = GetTickCount()
			NextShot = GetTickCount()
		end
	end
end

function OnSendPacket(packet)
	if packet.header == 0x9A then
		local P = Packet(packet)
		if P.values.spellId == _E then
			local ETarget = objManager:GetObjectByNetworkId(P.values.targetNetworkId)
			if ETarget then
				DashPos,_ = GetDashPos(ETarget)
			end
		end
	end
end

--[[ Combat Functions ]]--

function PewPew()
	if ValidTarget(ts.target) then
		if iYasConfig.other.DegrecCombo then return DegrecCombo() end

		local Damage = DamageResults[ts.target.networkID]
		local THitBox = GetDistance(ts.target, ts.target.minBBox)

		if myHero:CanUseSpell(_E) == READY then
			if GetStage() == STAGE_ORBWALK or GetDistance(ts.target) > AARange then
				if not DashedUnits[ts.target.networkID] then
					if EStacks == 4 or not iYasConfig.pewpew.StackE or Damage.E > ts.target.health then
						local DashPos, Turret = GetDashPos(ts.target)
						local TargetPos, _ = (iYasConfig.other.tpPro and tpProE or tpE):GetPrediction(target)
						if GetDistance(DashPos, TargetPos) < AARange and GetDistance(DashPos, TargetPos) < GetDistance(TargetPos)  then
							if Turret and iYasConfig.pewpew.SafeDash then
								if Turret.target and Turret.target.type == myHero.type then
									CastSpell(_E, ts.target)
								end
							else
								CastSpell(_E, ts.target)
							end
						end
					else
						local DashTarget, DashPos = GetDashTarget(ts.target, AARange)
						if DashTarget then
							CastSpell(_E, DashTarget)
						elseif iYasConfig.pewpew.RangedDash and GetDistance(ts.target) > AARange then
							CastSpell(_E, ts.target)
						end
					end
				else
					local DashTarget, DashPos = GetDashTarget(ts.target, AARange)
					if DashTarget then
						CastSpell(_E, DashTarget)
					end
				end
			end
		end

		if TQReady then
			if myHero:CanUseSpell(_R) == READY or myHero:GetSpellData(_R).cd * 1000 > GetTickCount() - TQReady then
				if isDashing then
					if GetDistance(ts.target, DashPos) < QRadius then
						CastSpell(_Q)
					end
				elseif iYasConfig.other.tpPro then
					local QPos, _, _ = tpProTQ:GetPrediction(ts.target)
					if QPos and GetDistance(QPos) < TQRange then
						CastSpell(_Q, QPos.x, QPos.z)
					end
				else
					local QPos, _, _ = tpTQ:GetPrediction(ts.target)
					if QPos then
						CastSpell(_Q, QPos.x, QPos.z)
					end
				end
			end
		elseif myHero:CanUseSpell(_Q) == READY then
			if isDashing then
				if GetDistance(ts.target, DashPos) < QRadius then
					CastSpell(_Q)
				end
			elseif iYasConfig.other.tpPro then
				if TQReady then
					local QPos, _, _ = tpProTQ:GetPrediction(ts.target)
					if QPos and GetDistance(QPos) < QRange then
						CastSpell(_Q, QPos.x, QPos.z)
					end
				else
					local QPos, _, _ = tpProQ:GetPrediction(ts.target)
					if QPos and GetDistance(QPos) < TQRange then
						CastSpell(_Q, QPos.x, QPos.z)
					end
				end
			elseif TQReady then
				local QPos, _, _ = tpTQ:GetPrediction(ts.target)
				if QPos then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			else
				local QPos, _, _ = tpQ:GetPrediction(ts.target)
				if QPos then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			end
		end
	elseif TQReady and GetTickCount() - TQReady < 9000 then
		if ValidTarget(tsFar.target) then
			if iYasConfig.other.tpPro then
				local QPos, _, _ = tpProTQ:GetPrediction(ts.target)
				if QPos and GetDistance(QPos) < TQRange then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			else
				local QPos, _, _ = tpTQ:GetPrediction(ts.target)
				if QPos then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			end
		end
	elseif iYasConfig.pewpew.RangedDash then
		if ValidTarget(tsFar.target) then
			if myHero:CanUseSpell(_E) == READY then
				local DashTime = DashedUnits[tsFar.target.networkID]
				--if not DashTime or DashTime - GetTickCount() < myHero:GetSpellData(_E).cd * 1000 + EDelay then
				if not DashTime then
					local DashTarget, DashPos = GetDashTarget(tsFar.target)
					if DashTarget then
						CastSpell(_E, DashTarget)
					end
				end
			end
		end
	end
end

function DegrecCombo()
	if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then
		if isDashing then
			if GetDistance(ts.target, DashPos) < QRadius then
				CastSpell(_Q)
			end
		elseif iYasConfig.other.tpPro then
			if TQReady then
				local QPos, _, _ = tpProTQ:GetPrediction(ts.target)
				if QPos and GetDistance(QPos) < QRange then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			else
				local QPos, _, _ = tpProQ:GetPrediction(ts.target)
				if QPos and GetDistance(QPos) < TQRange then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			end
		elseif TQReady then
			local QPos, _, _ = tpTQ:GetPrediction(ts.target)
			if QPos then
				CastSpell(_Q, QPos.x, QPos.z)
			end
		else
			local QPos, _, _ = tpQ:GetPrediction(ts.target)
			if QPos then
				CastSpell(_Q, QPos.x, QPos.z)
			end
		end
	end
end

function Poke()
	if not ValidTarget(ts.target) then return end
	if myHero:CanUseSpell(_Q) == READY then
		if isDashing then
			if GetDistance(ts.target, DashPos) < QRadius then
				CastSpell(_Q)
			end
		elseif iYasConfig.other.tpPro then
			if TQReady then
				local QPos, _, _ = tpProTQ:GetPrediction(ts.target)
				if QPos and GetDistance(QPos) < QRange then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			else
				local QPos, _, _ = tpProQ:GetPrediction(ts.target)
				if QPos and GetDistance(QPos) < TQRange then
					CastSpell(_Q, QPos.x, QPos.z)
				end
			end
		elseif TQReady then
			local QPos, _, _ = tpTQ:GetPrediction(ts.target)
			if QPos then
				CastSpell(_Q, QPos.x, QPos.z)
			end
		else
			local QPos, _, _ = tpQ:GetPrediction(ts.target)
			if QPos then
				CastSpell(_Q, QPos.x, QPos.z)
			end
		end
	end
end

function AutoKS()
	for _, enemy in pairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			if GetDistance(enemy) < ERange then
				local Damage = DamageResults[enemy.networkID]
				if enemy.health < Damage.E and myHero:CanUseSpell(_E) == READY and not DashedUnits[enemy.networkID] then
					CastSpell(_E, enemy)
					return
				elseif enemy.health < Damage.Q and myHero:CanUseSpell(_Q) == READY then
					if isDashing then
						if GetDistance(enemy, DashPos) < QRadius then
							CastSpell(_Q)
							return
						end
					elseif iYasConfig.other.tpPro then
						if TQReady then
							local QPos, _, _ = tpProTQ:GetPrediction(enemy)
							if QPos and GetDistance(QPos) < QRange then
								CastSpell(_Q, QPos.x, QPos.z)
								return
							end
						else
							local QPos, _, _ = tpProQ:GetPrediction(enemy)
							if QPos and GetDistance(QPos) < TQRange then
								CastSpell(_Q, QPos.x, QPos.z)
								return
							end
						end
					elseif TQReady then
						local QPos, _, _ = tpTQ:GetPrediction(enemy)
						if QPos then
							CastSpell(_Q, QPos.x, QPos.z)
							return
						end
					else
						local QPos, _, _ = tpQ:GetPrediction(enemy)
						if QPos then
							CastSpell(_Q, QPos.x, QPos.z)
							return
						end
					end
				elseif Damage.E + Damage.Q > enemy.health and (myHero:CanUseSpell(_Q) == READY or myHero:GetSpellData(_Q).cd * 1000 < EDelay) then
					if isDashing and GetDistance(enemy, DashPos) < QRadius then
						CastSpell(_Q)
						return
					elseif myHero:CanUseSpell(_E) == READY then
						CastSpell(_E, enemy)
						return
					end
				elseif Damage.R > enemy.health then
					if KnockedUp[enemy.networkID] then
						CastSpell(_R, enemy)
					elseif TQReady then
						if isDashing then
							if GetDistance(enemy, DashPos) < QRadius then
								CastSpell(_Q)
								return
							end
						elseif iYasConfig.other.tpPro then
							local QPos, _, _ = tpProTQ:GetPrediction(enemy)
							if QPos and GetDistance(QPos) < TQRange then
								CastSpell(_Q, QPos.x, QPos.z)
								return
							end
						else
							local QPos, _, _ = tpTQ:GetPrediction(enemy)
							if QPos then
								CastSpell(_Q, QPos.x, QPos.z)
								return
							end
						end
					end
				end
			elseif myHero:CanUseSpell(_R) == READY and GetDistance(enemy) < RRange and KnockedUp[enemy.networkID] and DamageResults[enemy.networkID].R > enemy.health then
				CastSpell(_R, enemy)
				return
			end
		end
	end
end

function AutoFarm()
	if GetStage() == STAGE_WINDUP then return end
	local MyAD = myHero.totalDamage
	local BaseQ, BaseE = 20 * myHero:GetSpellData(_Q).level + MyAD, (50 + 20 * myHero:GetSpellData(_E).level) * (1 + EStacks * 0.25) + 0.6 * myHero.ap
	for _, minion in ipairs(enemyMinions.objects) do
		if ValidTarget(minion) then
			if GetStage() == STAGE_NONE then
				if GetDistance(minion) < AARange and myHero:CalcDamage(minion, MyAD) > minion.health then
					Attack(minion)
					return
				end
			elseif iYasConfig.munching.UseE and myHero:CanUseSpell(_E) == READY then
				if myHero:CalcMagicDamage(minion, BaseE) > minion.health then
					CastSpell(_E, minion)
					return
				end
			elseif iYasConfig.munching.UseQ and myHero:CanUseSpell(_Q) == READY then
				if myHero:CalcDamage(minion, BaseQ) > minion.health then
					if isDashing then
						if GetDistance(minion, DashPos) < QRadius then
							CastSpell(_Q)
							return
						end
					else
						CastSpell(_Q, minion.x, minion.z)
						return
					end
				end
			end
		end
	end
	if iYasConfig.munching.MTM then Move(mousePos) end
end

--[[ Orbwalking Functions ]]--

function GetStage()
	return (GetTickCount() > NextShot and STAGE_NONE) or (GetTickCount() > ShotCast and STAGE_ORBWALK) or STAGE_WINDUP
end

function Orbwalk(movePos, target)
	if GetStage() == STAGE_NONE and ValidTarget(target, AARange) then
		myHero:Attack(target)
	elseif GetStage() ~= STAGE_WINDUP then
		myHero:MoveTo(movePos.x, movePos.z)
	end
end

function Attack(target)
	if GetStage() == STAGE_NONE and ValidTarget(target, AARange) then
		myHero:Attack(target)
	end
end

function Move(movePos)
	if GetStage() ~= STAGE_WINDUP then
		myHero:MoveTo(movePos.x, movePos.z)
	end
end

--[[ Predictions and Calculations ]]--

function GetDashTarget(target, maxRange)
	--local TargetPos,_ = (iYasConfig.tpPro and tpProE or tpE):GetPrediction(target)
	local TargetPos = target
	if EStacks == 4 then
		for _, enemy in ipairs(GetEnemyHeroes()) do
			if enemy ~= target and not DashedUnits[enemy.networkID] and ValidTarget(enemy, ERange) then
				local TempDashPos, Turret = GetDashPos(enemy)
				if GetDistance(TempDashPos, TargetPos) < (maxRange or ERange) then
					if Turret and iYasConfig.pewpew.SafeDash then
						if Turret.target and Turret.target.type == myHero.type then
							return enemy, TempDashPos
						end
					else
						return enemy, TempDashPos
					end
				end
			end
		end
		for _, minion in ipairs(enemyMinions.objects) do
			if not DashedUnits[minion.networkID] and ValidTarget(minion, ERange) then
				local TempDashPos, Turret = GetDashPos(minion)
				if GetDistance(TempDashPos, TargetPos) < (maxRange or ERange) then
					if Turret and iYasConfig.pewpew.SafeDash then
						if Turret.target and Turret.target.type == myHero.type then
							return minion, TempDashPos
						end
					else
						return minion, TempDashPos
					end
				end
			end
		end
	else
		for _, minion in ipairs(enemyMinions.objects) do
			if not DashedUnits[minion.networkID] and ValidTarget(minion, ERange) then
				local TempDashPos, Turret = GetDashPos(minion)
				if GetDistance(TempDashPos, TargetPos) < (maxRange or ERange) then
					if Turret and iYasConfig.pewpew.SafeDash then
						if Turret.target and Turret.target.type == myHero.type then
							return minion, TempDashPos
						end
					else
						return minion, TempDashPos
					end
				end
			end
		end
		for _, enemy in ipairs(GetEnemyHeroes()) do
			if not DashedUnits[enemy.networkID] and ValidTarget(enemy, ERange) then
				local TempDashPos, Turret = GetDashPos(enemy)
				if GetDistance(TempDashPos, TargetPos) < (maxRange or ERange) then
					if Turret and iYasConfig.pewpew.SafeDash then
						if Turret.target and Turret.target.type == myHero.type then
							return enemy, TempDashPos
						end
					else
						return enemy, TempDashPos
					end
				end
			end
		end
	end
end

function GetDashPos(target)
	local DashPos = myHero + (Vector(target) - myHero):normalized() * ERange
	for _, Turret in pairs(Turrets) do
		if GetDistance(Turret, DashPos) < Turret.range then
			return DashPos, Turret.object
		end
	end
	return DashPos, nil
end

function DamageCalculations()
	local MyAP, MyAD, MyBAD, QLevel, ELevel, RLevel = myHero.ap, myHero.totalDamage, myHero.addDamage, myHero:GetSpellData(_Q).level, myHero:GetSpellData(_E).level, myHero:GetSpellData(_R).level
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			local ReturnDamage = {}
			ReturnDamage.Q = myHero:CalcDamage(enemy, 20 * QLevel + MyAD)
			ReturnDamage.E = myHero:CalcMagicDamage(enemy, (50 + 20 * ELevel) * (1 + EStacks * 0.25) + 0.6 * MyAP)
			ReturnDamage.R = myHero:CalcDamage(enemy, 100 + 100 * RLevel + 1.5 * MyBAD)
			DamageResults[enemy.networkID] = ReturnDamage
		else
			DamageResults[enemy.networkID] = nil
		end
	end
end

function damageText()
	for _, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			if updateTextTimers[enemy.networkID] == nil then
				updateTextTimers[enemy.networkID] = 30
			elseif updateTextTimers[enemy.networkID] > 1 then
				updateTextTimers[enemy.networkID] = updateTextTimers[enemy.networkID] - 1
			elseif updateTextTimers[enemy.networkID] == 1 then			
				local Damage = DamageResults[enemy.networkID]
				if Damage.E > enemy.health and not DashedUnits[enemy.networkID] then
					PrintFloatText(enemy, 0, "E!")
				elseif Damage.Q > enemy.health then
					PrintFloatText(enemy, 0, "Q!")
				elseif Damage.Q + Damage.E > enemy.health then
					PrintFloatText(enemy, 0, "Slaughter!")
				elseif Damage.Q + Damage.E + Damage.R > enemy.health then
					PrintFloatText(enemy, 0, "Nuke!")
				end
				updateTextTimers[enemy.networkID] = 30
			end
		end
	end
end

--[[ Other Functions ]]--

function UpdateTurrets()
	for i, Turret in pairs(Turrets) do
		if Turret.object.dead or Turret.object.health == 0 then
			Turrets[i] = nil
		end
	end
end

function CheckForSAC()
	if _G.AutoCarry then
		print("WARNING: iYasuo has its own orbwalking.")
		CheckForSAC = function() end
	end
end

--[[ Notes:

- Ult logic
	- MEC
- Rework PewPew()
	- Add MEC-Q
- Rework Poke()
	- Add E-Q-E harass
- Rework AutoKS()
- Add AutoE minions
- Add Laneclear
	- E-Q
	- Safe E
	- Tiamat / Hydra
- Add Jungleclear
	- E-Q
	- Tiamat / Hydra
- Add Items
	- PewPew
	- Poke
	- DamageCalculations
	- Statikk Shiv buff
- Get AA spellnames

]]--