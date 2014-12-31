<?php exit() ?>--by x7x 89.70.161.4
--[[

		Ahri - Sexiest champion in the game :D
		VERSION INDEV ALPHA :P

]]
-- color: #00BFFF
-- RGB(0,191,255)
if myHero.charName ~= "Ahri" then return end
-- Ahri R CD == 1

local nameGOE = "Ahri - 9 Tailed Fox"
local versionGOE = 0.95

require "Prodiction"
require "FastCollision"
local Prodiction = ProdictManager.GetInstance()
local QProdict = nil
local EProdict = nil
local enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local allyMinions = minionManager(MINION_ALLY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1000+550,DAMAGE_MAGICAL,false)
local Enemies = {}
local LastAATarget = nil
local NextAATick = 0
local EndOfWindupTick = 0
local AutoAttackRange = 550
local ECollision
local CanQ = false
local CanW = false
local CanE = false
local CanR = false
local CanIgnite = false
local CanDFG = false
local JumpStacks = 0
local QReturnTick = 0
local LastQPos = nil
local waittxt = {}
local EndOfR = 0
local AhriOrb = nil

function GetUltStacks()
	if CanR and JumpStacks == 0 then
		return 3
	elseif JumpStacks == 2 then
		return 2
	elseif JumpStacks == 1 then
		return 1
	else
		return 0
	end
end

function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then JumpStacks = buff.stack end
end
function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then
		JumpStacks = buff.stack
		EndOfR = GetTickCount()+10000
	end
end
function OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then JumpStacks = 0 end
end

function OnLoad()
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
	AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) OnUpdateBuff(unit, buff) end)
	Menu = scriptConfig(nameGOE.." v"..versionGOE, "aahri")
	ts.name = "Ahri"
	Menu:addTS(ts)
	QProdict = Prodiction:AddProdictionObject(_Q, 850, 1700, 0.24, 50, myHero)
	EProdict = Prodiction:AddProdictionObject(_E, 900, 1550, 0.25, 60, myHero)
	Menu:addSubMenu("Combo settings [SBTW]", "ComboMenu")
	Menu.ComboMenu:addParam("ComboKey","Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.ComboMenu:addParam("useAA","Orbwalking", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, false)
	--Menu.ComboMenu:addParam("useRks","- only when enemy is killable", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useRpos","- to get best position for Q return", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useDFG","Use Deathfire Grasp in combo", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
	Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
	ECollision = FastCol(EProdict)
	local ordertxt = 1
	for i=1, heroManager.iCount do
		waittxt[i] = ordertxt
		ordertxt = ordertxt+1
		local EnemyHero = heroManager:getHero(i)
		if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
	end
	PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." loaded!</font>")
end

function OnUnload()
	PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." unloaded!</font>")
end

function OnCreateObj(obj)
	if obj ~= nil and obj.valid and obj.name == "Ahri_Orb_mis.troy123" or obj ~= nil and obj.valid and obj.name == "Ahri_Orb_mis_02.troy" then AhriOrb = obj end
end

function OnTick()
	--PrintChat("rStck: "..GetUltStacks())
	ts:update()
	--if myHero:GetSpellData(_R).currentCd ~= 0 then PrintChat(""..myHero:GetSpellData(_R).currentCd) end
	if AhriOrb ~= nil and not AhriOrb.valid then AhriOrb = nil end
	CanQ = (myHero:CanUseSpell(_Q) == READY)
	CanW = (myHero:CanUseSpell(_W) == READY)
	CanE = (myHero:CanUseSpell(_E) == READY)
	CanR = (myHero:CanUseSpell(_R) == READY)
	CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
	CanDFG = GetInventoryItemIsCastable(3128)
	--if IsEnemyHero(ts.target) then fTarget = ts.target else fTarget = nil end--if GetTarget() ~= nil then fTarget = GetTarget() elseif ts.target ~= nil then fTarget = ts.target else fTarget = nil end
	if ts.target ~= nil and Menu.ComboMenu.ComboKey then
		if Menu.ComboMenu.useAA then OrbwalkTarget(ts.target) end
		if CanDFG and Menu.ComboMenu.useDFG then CastItem(3128, ts.target) end
		local pos2 = EProdict:GetPrediction(ts.target)
		if not ECollision:GetMinionCollision(pos2) then
			if CanE then CastE(ts.target) end
			if CanQ then CastQ(ts.target) end
			if CanW then CastW(ts.target) end
		else
			if CanQ then CastQ(ts.target) end
			if CanW then CastW(ts.target) end
		end
		if Menu.ComboMenu.useR and GetUltStacks() > 0 then
			if Menu.ComboMenu.useRpos then
				if not CanQ and AhriOrb ~= nil and AhriOrb.valid and not ts.target.dead then
					--local Position1 = Point(ts.target.x, ts.target.z)-(Point(ts.target.x, ts.target.z)-LastQPos):normalized()*150
					local Position1 = Point(AhriOrb.x, AhriOrb.z)-(Point(AhriOrb.x, AhriOrb.z)-Point(ts.target.x, ts.target.z)):normalized()*250
					if Position1 ~= nil and GetDistance(Position1) < 600 then
						if GetUltStacks() > 0 then
							CastSpell(_R, Position1.x, Position1.y)
							LastQPos = nil
						end
					end
				end
			end
		end
		if Menu.ComboMenu.useR and GetUltStacks() > 0 and GetUltStacks() < 3 then
			local FiringTime = GetUltStacks()*1000+100
			local TimeToEnd = EndOfR-GetTickCount()
			if TimeToEnd < FiringTime then
				local Position1 = Point(ts.target.x, ts.target.z)-(Point(ts.target.x, ts.target.z)-Point(myHero.x, myHero.z)):normalized()*450
				if GetDistance(Position1) > GetDistance(ts.target) then
					CastSpell(_R, Position1.x ,Position1.y)
				end
			end
		end
	elseif Menu.ComboMenu.ComboKey then
		OrbwalkTarget()
	end
end

function OrbwalkTarget(Target)
	if Target == nil or Target.dead or not Target.valid and CanMove() then 
		MoveTo(mousePos.x, mousePos.z)
	else
		if CanShoot() and GetDistance(Target) <= AutoAttackRange then
			Attack(Target)
		elseif CanMove() then
			MoveTo(mousePos.x, mousePos.z)
		end
	end
end

function CanShoot()
	if GetTickCount() >= NextAATick then return true else return false end
end
function CanMove()
	if GetTickCount() >= EndOfWindupTick then return true else return false end
end
--Packet('S_CAST', {fromX = mousePos.x, fromY = mousePos.z, targetNetworkId = target.networkID, spellId = SPELL_3}):send()
function MoveTo(toX, toZ)
	Packet('S_MOVE', {type = 2, x=toX, y=toZ}):send()
end
function Attack(target)
	if target ~= nil and target.valid and not target.dead and GetDistance(target) <= AutoAttackRange then
		myHero:Attack(target)
		--Packet('S_MOVE', {type = 2, x=target.x, y=target.z, targetNetworkId=target.networkID}):send()
	end
end
function CastQ(Target)
	if Menu.ComboMenu.useQ and Target ~= nil and Target.valid and not Target.dead then
		local pos = QProdict:GetPrediction(Target)
		if pos ~= nil and GetDistance(pos) <= 850 then
			CastSpell(_Q, pos.x, pos.z)
			--Packet('S_CAST', {fromX = myHero.x, fromY = myHero.z, toX = pos.x, toY = pos.z, spellId = SPELL_1}):send()
		end
	end
end
function CastW(Target)
	if Menu.ComboMenu.useW and Target ~= nil and Target.valid and not Target.dead then
		if GetDistance(Target) < 800 then
			CastSpell(_W)
		end
	end
end
function CastE(Target)
	if Menu.ComboMenu.useE and Target ~= nil and Target.valid and not Target.dead then
		local pos = EProdict:GetPrediction(Target)
		if not ECollision:GetMinionCollision(pos) and pos ~= nil and GetDistance(pos) <= 900 then
			CastSpell(_E, pos.x, pos.z)
			--Packet('S_CAST', {fromX = myHero.x, fromY = myHero.z, toX = pos.x, toY = pos.z, spellId = SPELL_3}):send()
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and (spell.name:lower():find("attack")) then
		LastAATick = GetTickCount() - GetLatency()/2
		NextAATick = (spell.animationTime*1000)+GetTickCount() - GetLatency()/2
		EndOfWindupTick = (spell.windUpTime*1000)+GetTickCount() - GetLatency()/2
	end
	if unit.isMe and spell.name == "AhriOrbofDeception" then
		--Point(myHero.x, myHero.z)-(Point(myHero.x, myHero.z)-Point(x, y)):normalized()*JumpAbilityRange
		LastQPos = Point(myHero.x, myHero.z)-(Point(myHero.x, myHero.z)-Point(spell.endPos.x, spell.endPos.z)):normalized()*850
		QReturnTick = GetTickCount()+(1700/850*1000)
	end
end
function OnDraw()
	--if TestPos ~= nil then DrawCircle(TestPos.x, myHero.y, TestPos.y, 150, RGB(255,0,255)) end
	--if AhriOrb ~= nil and AhriOrb.valid then DrawCircle(AhriOrb.x, AhriOrb.y, AhriOrb.z, 150, RGB(0,255,0)) end
				--[[if not CanQ and AhriOrb ~= nil and AhriOrb.valid and ts.target ~= nil and not ts.target.dead then
					--local Position1 = Point(ts.target.x, ts.target.z)-(Point(ts.target.x, ts.target.z)-LastQPos):normalized()*150
					local Position1 = Point(AhriOrb.x, AhriOrb.z)-(Point(AhriOrb.x, AhriOrb.z)-Point(ts.target.x, ts.target.z)):normalized()*250
					if GetDistance(Position1) > GetDistance(ts.target) then
					DrawCircle(Position1.x, myHero.y, Position1.y, 150, RGB(0,255,0))
					end
				end]]
	if Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 880, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawWrange then DrawCircle(myHero.x, myHero.y, myHero.z, 800, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 975, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawRrange then DrawCircle(myHero.x, myHero.y, myHero.z, 450*GetUltStacks(), RGB(255,0,255)) end
	if Menu.DrawHelperMenu.drawHPtext then
		for n, DrawTarget in pairs(Enemies) do
			if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
				local DFGdmg = 0
				local TotalDMG = 0
				local Qdmg = getDmg("Q", DrawTarget, myHero)
				local Wdmg = getDmg("W", DrawTarget, myHero)*3
				local Edmg = getDmg("E", DrawTarget, myHero)
				local Rdmg = getDmg("R", DrawTarget, myHero)*3
				if CanDFG then
					TotalDMG = (Qdmg+Wdmg+Edmg+Rdmg)*1.2
					TotalDMG = TotalDMG + getDmg("DFG", DrawTarget, myHero)
				else
					TotalDMG = Qdmg+Wdmg+Edmg+Rdmg
				end
				if Qdmg+Wdmg+Edmg > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "MURDER HIM")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				elseif TotalDMG > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				else
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Not killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				end
			end
		end	
	end
end

function IsEnemyHero(unit)
	local isHero = false
	for i, Enemy in pairs(Enemies) do
		if Enemy.networkID == unit.networkID then isHero = true end
	end
	return isHero
end