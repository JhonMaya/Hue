<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Ahri" then return end
local tECollision = nil
local nameGOE = "Ahri - Flying Balls"
local versionGOE = 0.95
local Prodict = ProdictManager.GetInstance()
local QProdict = nil
local EProdict = nil
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1250,DAMAGE_MAGICAL,false)
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
	ts.name = "Ahri TS"
	Menu:addTS(ts)
	QProdict = Prodict:AddProdictionObject(_Q, 880, 1700, 0.24, 50)
	EProdict = Prodict:AddProdictionObject(_E, 975, 1550, 0.25, 60)
	tECollision = Collision(900, 1550, 0.24, 60)
	Menu:addSubMenu("Combo settings [SBTW]", "ComboMenu")
	Menu.ComboMenu:addParam("ComboKey","Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.ComboMenu:addParam("useAA","Orbwalking", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("smartQWER","Use smart QWER combo", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, false)
	Menu.ComboMenu:addParam("useRkill","- only when enemy is killable", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useRpos","- to get best position for Q return", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useRleft","- use rest of jumps at end", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboMenu:addParam("useDFG","Use Deathfire Grasp in combo", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
	Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
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
	ts:update()
	if AhriOrb ~= nil and not AhriOrb.valid then AhriOrb = nil end
	CanQ = (myHero:CanUseSpell(_Q) == READY)
	CanW = (myHero:CanUseSpell(_W) == READY)
	CanE = (myHero:CanUseSpell(_E) == READY)
	CanR = (myHero:CanUseSpell(_R) == READY)
	CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
	CanDFG = GetInventoryItemIsCastable(3128)
	if Menu.ComboMenu.ComboKey then
		if ts.target ~= nil then
			if GetDistance(ts.target) < 900 then
				if Menu.ComboMenu.useAA then OrbwalkTarget(ts.target) end
				if Menu.ComboMenu.smartQWER then
					local TotalDMG = 0
					local DFGdmg = 0
					local Qdmg = 0
					local Wdmg = 0
					local Edmg = 0
					local Rdmg = 0
					if CanDFG then DFGdmg = getDmg("DFG", ts.target, myHero) end
					if CanQ then Qdmg = getDmg("Q", ts.target, myHero) end
					if CanW then Wdmg = getDmg("W", ts.target, myHero)*2 end
					if CanE then Edmg = getDmg("E", ts.target, myHero) end
					if CanR then Rdmg = getDmg("R", ts.target, myHero)*GetUltStacks() end
					if CanDFG then
						TotalDMG = (Qdmg+Wdmg+Edmg+Rdmg+DFGdmg)*1.2
					else
						TotalDMG = Qdmg+Wdmg+Edmg+Rdmg
					end
					local tt = ts.target
					if tt.health < Wdmg and CanW and GetDistance(tt) < 800 then
						CastW(tt)
					elseif tt.health < Qdmg and CanQ and GetDistance(tt) < 850 then
						CastQ(tt)
					elseif tt.health < Qdmg+Wdmg and CanQ and CanW and GetDistance(tt) < 800 then
						CastQ(tt)
						CastW(tt)
					elseif tt.health < Qdmg+Wdmg+Edmg and CanQ and CanW and CanE and GetDistance(tt) < 800 then
						CastQ(tt)
						CastW(tt)
						CastE(tt)
					elseif tt.health < Qdmg+Wdmg+Edmg+Rdmg and CanQ and CanW and CanE and CanR and CanDFG then
						CastQ(tt)
						CastW(tt)
						CastE(tt)
						local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
						local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
						CastSpell(_R, CastTo.x, CastTo.y)
					else
						if CanDFG then CastItem(3128, tt) end
						if CanQ then CastQ(tt) end
						if CanW then CastW(tt) end
						if CanE then CastE(tt) end
						local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
						local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
						CastSpell(_R, CastTo.x, CastTo.y)
					end	
				else
					if CanDFG and Menu.ComboMenu.useDFG then CastItem(3128, ts.target) end
					if CanE then CastE(ts.target) end
					if CanQ then CastQ(ts.target) end
					if CanW then CastW(ts.target) end
					if Menu.ComboMenu.useRkill and getDmg("R", ts.target, myHero)*GetUltStacks() then
						if CanR and Menu.ComboMenu.useR then
							local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
							local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
							CastSpell(_R, CastTo.x, CastTo.y)
						end
					else
						if CanR and Menu.ComboMenu.useR then
							local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
							local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
							CastSpell(_R, CastTo.x, CastTo.y)
						end
					end
				end
				if Menu.ComboMenu.useR and GetUltStacks() > 0 then
					if Menu.ComboMenu.useRpos then
						if not CanQ and AhriOrb ~= nil and AhriOrb.valid and not ts.target.dead and GetDistance(AhriOrb) > GetDistance(ts.target) then
							local Position1 = Point(ts.target.x, ts.target.z)-(Point(AhriOrb.x, AhriOrb.z)-Point(ts.target.x, ts.target.z)):normalized()*250
							if Position1 ~= nil and GetDistance(Position1) < 500 then
								if GetUltStacks() > 0 then
									CastSpell(_R, Position1.x, Position1.y)
								end
							end
						end
					end
					if Menu.ComboMenu.useRks then
						if ts.target.health > getDmg("R", ts.target, myHero)*GetUltStacks() then
							local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
							local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
							CastSpell(_R, CastTo.x, CastTo.y)
						end
					end
				end
			elseif GetDistance(ts.target) > 900 and GetDistance(ts.target) < 1250 then
				if Menu.ComboMenu.useRkill and getDmg("R", ts.target, myHero)*GetUltStacks() then
					if CanR and Menu.ComboMenu.useR then CastSpell(_R, ts.target.x, ts.target.z) end
				else
					if CanR and Menu.ComboMenu.useR then CastSpell(_R, ts.target.x, ts.target.z) end
				end
				if Menu.ComboMenu.useAA then OrbwalkTarget() end
			else
				PrintChat("Error #13 - Report it to Anonymous, write about situation!")
			end
		else
			if Menu.ComboMenu.useAA then OrbwalkTarget() end
		end
	end
	if ts.target ~= nil then
		if Menu.ComboMenu.useR and Menu.ComboMenu.useRleft and GetUltStacks() > 0 and GetUltStacks() < 3 then
			local FiringTime = GetUltStacks()*1000+100
			local TimeToEnd = EndOfR-GetTickCount()
			if TimeToEnd < FiringTime then
				local Position1 = Point(ts.target.x, ts.target.z)-(Point(ts.target.x, ts.target.z)-Point(myHero.x, myHero.z)):normalized()*450
				if Position1 ~= nil then
					CastSpell(_R, Position1.x ,Position1.y)
				end
			end
		end
	end
end

function OrbwalkTarget(Target)
	if Target == nil then 
		MoveTo(mousePos.x, mousePos.z)
	else
		if CanShoot() and GetDistance(Target) < 550 then
			Attack(Target)
		elseif GetDistance(Target) > 550 then
			MoveTo(mousePos.x, mousePos.z)
		else
			if CanMove() then MoveTo(mousePos.x, mousePos.z) end
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
	if target ~= nil and target.valid and not target.dead and GetDistance(target) <= 550 then
		myHero:Attack(target)
		--Packet('S_MOVE', {type = 2, x=target.x, y=target.z, targetNetworkId=target.networkID}):send()
	end
end
function CastQ(Target)
	if Menu.ComboMenu.useQ and Target ~= nil and Target.valid and not Target.dead then
		local pos = QProdict:GetPrediction(Target)
		if pos ~= nil and GetDistance(pos) < 850 then
			CastSpell(_Q, pos.x, pos.z)
			--Packet('S_CAST', {fromX = myHero.x, fromY = myHero.z, toX = pos.x, toY = pos.z, spellId = SPELL_1}):send()
		else
			--PrintChat("Error #12 - Report it to Anonymous, write about situation!")
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
		local pos = EProdict:GetPrediction(Target, myHero)
		--if not ECollision:GetMinionCollision(pos) and pos ~= nil and GetDistance(pos) <= 900 then
		if not tECollision:GetMinionCollision(myHero, pos) and pos ~= nil and GetDistance(pos) <= 900 then
			CastSpell(_E, pos.x, pos.z)
			--Packet('S_CAST', {fromX = myHero.x, fromY = myHero.z, toX = pos.x, toY = pos.z, spellId = SPELL_3}):send()
		else
			if pos == nil then PrintChat("Error #11 - Report it to Anonymous, write about situation!") end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and (spell.name:lower():find("attack")) then
		NextAATick = (spell.animationTime*1000)+GetTickCount() - GetLatency()/2
		EndOfWindupTick = (spell.windUpTime*1000)+GetTickCount() - GetLatency()/2
	end
end
function OnDraw()
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