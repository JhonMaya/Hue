<?php exit() ?>--by Sida 94.197.121.198
require "SpellDatabase"
--[[
		Sida's Auto Reborn
]]

local AutoCarryGlobal = {}
AutoCarryGlobal.Data = {}
_G.AutoCarry = AutoCarryGlobal.Data

SACstate = 1
local DoneInit = false
function OnTick()

if SACstate == 3 then 
	return
elseif not DoneInit then
	if not Init() then
		return
	end
end

	if Keys.LastHit then 
		Minions:LastHit() 
	end

	if Keys.AutoCarry then 
		Items:UseAll(Crosshair.Attack_Crosshair.target) 
		Skills:CastAll(Crosshair:GetTarget()) 
		Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
	end

	if Keys.LaneClear then 
		Skills:CastAll(Crosshair:GetTarget()) 
		if LaneClearMenu.MinionPriority and Orbwalker:CanOrbwalkTarget(Minions.KillableMinion) and not ConfigurationMenu.SupportMode then
			Orbwalker:Orbwalk(Minions.KillableMinion)
		elseif Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
			Items:UseAll(Crosshair.Attack_Crosshair.target)
			Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
		elseif Orbwalker:CanOrbwalkTarget(Jungle:GetAttackableMonster()) then
			Orbwalker:Orbwalk(Jungle:GetAttackableMonster())
		else
			Minions:LaneClear()
		end
	end

	if Keys.MixedMode then 		
		Skills:CastAll(Crosshair:GetTarget()) 
		if MixedModeMenu.MinionPriority and Orbwalker:CanOrbwalkTarget(Minions.KillableMinion) and not ConfigurationMenu.SupportMode then
			Orbwalker:Orbwalk(Minions.KillableMinion)
		elseif Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
			Items:UseAll(Crosshair.Attack_Crosshair.target)
			Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
		else
			Minions:LastHit()
		end
	end
end

if AdvancedCallback then
	AdvancedCallback:bind('OnLoseVision', function(unit) end)
	AdvancedCallback:bind('OnGainVision', function(unit) end)
	AdvancedCallback:bind('OnDash', function(unit) end)
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) end)
	AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) end)
end


MODE_AUTOCARRY = 0
MODE_MIXEDMODE = 1
MODE_LASTHIT = 2
MODE_LANECLEAR = 3
AutoCarry.MODE_AUTOCARRY = 0
AutoCarry.MODE_MIXEDMODE = 1
AutoCarry.MODE_LASTHIT = 2
AutoCarry.MODE_LANECLEAR = 3

--[[
		Orbwalker Class
						]]
class '_Orbwalker' Orbwalker = nil

STAGE_SHOOT = 0
STAGE_MOVE = 1
STAGE_SHOOTING = 2
AutoCarry.STAGE_SHOOT = 0
AutoCarry.STAGE_MOVE = 1
AutoCarry.STAGE_SHOOTING = 2
RegisteredOnAttacked = {}

function _Orbwalker:__init()
	self.LastAttack = 0
	self.LastWindUp = 0
	self.LastAttackCooldown = 0
	self.AttackCompletesAt = 0
	self.AfterAttackTime = 0
	self.AttackBufferMax = 400

	self.LastAttackSpeed = myHero.attackSpeed
	self.LowestAttackSpeed = myHero.attackSpeed

	AddTickCallback(function() self:_OnTick() end)
	AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)
	AddAnimationCallback(function(Unit, Animation) self:_OnAnimation(Unit, Animation) end)
	
end

--[[ Callbacks ]]
function _Orbwalker:_OnTick()
	if self:CanMove() and not self:CanShoot() then
	end

	if self:CanMove() and GetTickCount() + GetLatency() / 2 < self.LastAttack + self.LastWindUp + self.AttackBufferMax then
		self.AfterAttackTime = self.LastAttack + self.LastWindUp + self.AttackBufferMax
		if not self.DoneOnAttacked then
			self:_OnAttacked()
		end
	end

end

function _Orbwalker:_OnProcessSpell(Unit, Spell)
	if Unit.isMe then
		if Data:IsAttack(Spell) then
			self.LastAttack = GetTickCount() - GetLatency() / 2
			self.LastWindUp = Spell.windUpTime * 1000
			self.LastAttackCooldown = Spell.animationTime * 1000
			self.AttackCompletesAt = self.LastAttack + self.LastWindUp
			self.LastAttackSpeed = myHero.attackSpeed
			self.DoneOnAttacked = false
		elseif Data:IsResetSpell(Spell) then
			self:ResetAttackTimer()
		end
	end
end

function _Orbwalker:_OnAnimation(Unit, Animation)
	if GetTickCount() < Orbwalker.AttackCompletesAt and Unit.isMe and (Animation == "Run" or Animation == "Idle") then
		Orbwalker:ResetAttackTimer()
	end
end

function _Orbwalker:ResetAttackTimer()
	self.LastAttack = GetTickCount() - GetLatency() / 2 - self.LastAttackCooldown
end

function _Orbwalker:Orbwalk(Target)
	if self:CanOrbwalkTarget(Target) then
		if self:CanShoot() then
			MyHero:Attack(Target)
		elseif self:CanMove()  then
			MyHero:Move()
		end
	else
		MyHero:Move()
	end
end

function _Orbwalker:OrbwalkIgnoreChecks(target)
	if target and self:CanShoot() then
		MyHero:Attack(target)
	elseif not self:CanShoot() then
		MyHero:Move()
	end
end

function _Orbwalker:CanMove()
	return (GetTickCount() + (GetLatency() / 2) + self:GetAnimationOverdrive() > self.LastAttack + self.LastWindUp + 20)
end

function _Orbwalker:CanShoot()
	return (GetTickCount() + (GetLatency() / 2) + self:GetAnimationOverdrive() > self.LastAttack + (self.LastAttackCooldown * self:GetAttackSlowModifier())) 
end

function _Orbwalker:GetHitboxRadius(Unit)
	if Unit ~= nil then 
		return GetDistance(Unit.minBBox, Unit.maxBBox) / 2
	end
end

function _Orbwalker:CanOrbwalkTarget(Target)
	if ValidTarget(Target) then
		if Target.type == myHero.type then
			if GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) - self:GetScalingRange(Target) < MyHero.TrueRange then
				return true
			end
		else
			if GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) < MyHero.TrueRange then
				return true
			end
		end
	end
	return false
end

function _Orbwalker:CanOrbwalkTargetFromPosition(Target, Position)
	if ValidTarget(Target) then
		if Target.type == myHero.type then
			if GetDistance(Target, Position) - Data:GetGameplayCollisionRadius(Target.charName) - self:GetScalingRange(Target) < MyHero.TrueRange then
				return true
			end
		else
			if GetDistance(Target, Position) - Data:GetGameplayCollisionRadius(Target.charName) < MyHero.TrueRange then
				return true
			end
		end
	end
	return false
end

function _Orbwalker:IsAfterAttack()
	return GetTickCount() + (GetLatency() / 2) < self.AfterAttackTime
end

function _Orbwalker:GetScalingRange(Target)
	if Target.type == myHero.type and Target.team ~= myHero.team then
		local scale = Data:GetOriginalHitBox(Target)
		return (scale and (GetDistance(Target.minBBox, Target.maxBBox) - Data:GetOriginalHitBox(Target)) / 2 or 0)
	end
	return 0
end

function _Orbwalker:GetAnimationOverdrive()
	local Amount = 0
	-- if OverdriveMenu.Enabled then
	-- 	Amount = OverdriveMenu.AnimationOverdrive - (myHero.attackSpeed * 10)
	-- end
	return Amount > 0 and Amount or 0
end

function _Orbwalker:GetAttackSlowModifier()
	return self.LastAttackSpeed / myHero.attackSpeed
end

function _Orbwalker:GetNextAttackTime()
	return self.LastAttack + (self.LastAttackCooldown * self:GetAttackSlowModifier())
end

function _Orbwalker:IsShooting()
	return self:CanMove()
end

function _Orbwalker:AttackOnCooldown()
	return GetTickCount() < self:GetNextAttackTime()
end

function _Orbwalker:AttackReady()
	return self:CanShoot()
end

function RegisterOnAttacked(func)
	table.insert(self.RegisteredOnAttacked, func)
end

function _Orbwalker:_OnAttacked()
	for _, func in pairs(RegisteredOnAttacked) do
		func()
	end
	self.DoneOnAttacked = true
end

--[[					
		_MyHero Class
						]]

class '_MyHero' MyHero = nil

function _MyHero:__init()
	self.Range = myHero.range
	self.HitBox = GetDistance(myHero.minBBox)
	self.GameplayCollisionRadius = Data:GetGameplayCollisionRadius(myHero.charName)
	self.TrueRange = self.Range + self.GameplayCollisionRadius
	self.IsMelee = myHero.range < 300
	self.MoveDistance = 480
	self.LastHitDamageBuffer = -15 --TODO
	self.ChampionAdditionalLastHitDamage = 0
	self.ItemAdditionalLastHitDamage = 0
	self.MasteryAdditionalLastHitDamage = 0
	self.Team = myHero.team == 100 and "Blue" or "Red"
	self.ProjectileSpeed = Data:GetProjectileSpeed(myHero.charName)
	self.LastMoved = 0
	self.MoveDelay = 50
	self.CanMove = true
	self.CanAttack = true
	self.InStandZone = false
	self.HasStopped = false
	self.HasSpoils = false
	self.SpoilStacks = 0
	self.LastSpoil = 0
	self.IsAttacking = false
	self.Spoils = {}
	MyHero = self

	for i = 0, objManager.maxObjects do
		local Object = objManager:getObject(i)
		if Object and GetDistance(Object) < 80 and  Object.name:find("GLOBAL_Item_FoM_Charge") then
			if Object.name:find("GLOBAL_Item_FoM_Charge01") then
				self.LastSpoilCreated = 1
				self.SpoilStacks = 1
			elseif Object.name:find("GLOBAL_Item_FoM_Charge02") then
				self.LastSpoilCreated = 2
				self.SpoilStacks = 2
			elseif Object.name:find("GLOBAL_Item_FoM_Charge03") then
				self.LastSpoilCreated = 3
				self.SpoilStacks = 3
			elseif Object.name:find("GLOBAL_Item_FoM_Charge04") then
				self.LastSpoilCreated = 4
				self.SpoilStacks = 4
			end
			table.insert(self.Spoils, Object)
		end
	end

	AddTickCallback(function() self:_OnTick() end)
	AddCreateObjCallback(function(Object) self:_OnCreateObj(Object) end)
	AddDeleteObjCallback(function(Object) self:_OnDeleteObj(Object) end)
end

function _MyHero:_OnTick()
	self.TrueRange = myHero.range + self.GameplayCollisionRadius + self:GetScalingRange()
	if myHero.range ~= self.Range then 
		if myHero.range and myHero.range > 0 and myHero.range < 1500 then
			self.Range = myHero.range
			self.IsMelee = myHero.range < 300
		end
	end
	if GetDistance(mousePos) < ExtrasMenu["StandZone"..myHero.charName] then
		self.InStandZone = true
	else
		self.InStandZone = false
	end

	self.HasSpoils = self.SpoilStacks > 0
	self:CheckStopMovement()
end

function _MyHero:_OnCreateObj(Object)
	if GetDistance(Object) < 80 and Object.name:find("GLOBAL_Item_FoM_Charge") then
		if Object.name:find("GLOBAL_Item_FoM_Charge01") then
			self.LastSpoilCreated = 1
			self.SpoilStacks = 1
		elseif Object.name:find("GLOBAL_Item_FoM_Charge02") then
			self.LastSpoilCreated = 2
		elseif Object.name:find("GLOBAL_Item_FoM_Charge03") then
			self.LastSpoilCreated = 3
		elseif Object.name:find("GLOBAL_Item_FoM_Charge04") then
			self.LastSpoilCreated = 4
			self.SpoilStacks = 4
		end
		table.insert(self.Spoils, Object)
	end
end

function _MyHero:_OnDeleteObj(Object)
	for i, Spoil in pairs(self.Spoils) do
		if Object and Object == Spoil then
			if Object.name:find("GLOBAL_Item_FoM_Charge01") then
				if self.LastSpoilCreated == 1 then
					self.SpoilStacks = 0
				else
					self.SpoilStacks = 2
				end
			elseif Object.name:find("GLOBAL_Item_FoM_Charge02") then
				if self.LastSpoilCreated == 1 then
					self.SpoilStacks = 1
				else
					self.SpoilStacks = 3
				end
			elseif Object.name:find("GLOBAL_Item_FoM_Charge03") then
				if self.LastSpoilCreated == 4 then
					self.SpoilStacks = 4
				else
					self.SpoilStacks = 2
				end
			elseif Object.name:find("GLOBAL_Item_FoM_Charge04") then
				self.SpoilStacks = 3
			end
			table.remove(self.Spoils, i)
		end
		
	end
end

function _MyHero:GetScalingRange()
	local scale = Data:GetOriginalHitBox(myHero)
	return (scale and (GetDistance(myHero.minBBox, myHero.maxBBox) - Data:GetOriginalHitBox(myHero)) / 2 or 0)
end

function _MyHero:SetProjectileSpeed(Speed)
	self.ProjectileSpeed = Speed
end

function _MyHero:GetTimeToHitTarget(Target)
	local proj = MyHero.ProjectileSpeed
	proj = proj - 2
	local lastWind = Orbwalker.LastWindUp --* (1 + ((proj * -1)*0.6))

	local AttackTime = (GetDistance(Target) / self.ProjectileSpeed) + (Helper.Latency / 2) + lastWind --Orbwalker.LastWindUp
	
	local NextAttack = Orbwalker:GetNextAttackTime()
	if NextAttack <= GetTickCount() then
		NextAttack = GetTickCount()
	end

	return AttackTime + NextAttack
end

function _MyHero:GetTotalAttackDamageAgainstTarget(Target, LastHit)
	local MyDamage = myHero:CalcDamage(Target, myHero.totalDamage)
	if LastHit then
		MyDamage = MyDamage + self.ChampionAdditionalLastHitDamage -- TODO
		MyDamage = MyDamage + self.ItemAdditionalLastHitDamage --TODO
		--MyDamage = MyDamage + self:GetMasteryAdditionalLastHitDamage(MyDamage, Target)
		--MyDamage = MyDamage + self.LastHitDamageBuffer
	end
	return MyDamage
end

function _MyHero:GetMasteryAdditionalLastHitDamage(Damage, Target)
	local _Damage = Damage
	_Damage = _Damage + Items:GetBotrkBonusLastHitDamage(_Damage, Target)
	_Damage = ConfigMenu.ArcaneBlade and _Damage + (myHero.ap * 0.05) or _Damage
	_Damage = ConfigMenu.DoubleEdgedSword and _Damage * 1.02 or _Damage
	_Damage = ConfigMenu.Butcher and _Damage + 2 or _Damage
	_Damage = ConfigMenu.Havoc and _Damage * 1.03 or _Damage
	return _Damage - Damage - 1
end

function _MyHero:Move()
	if self:HeroCanMove() and not Helper:IsEvading() and Orbwalker:CanMove() then
		if Helper.Tick > self.LastMoved + self.MoveDelay then
			Streaming:OnMove()
			local Distance = self.MoveDistance + Helper.Latency / 10
			if self.IsMelee and Crosshair.Attack_Crosshair.target and Crosshair.Attack_Crosshair.target.type == myHero.type and 
				MeleeMenu.MeleeStickyRange > 0 and GetDistance(Crosshair.Attack_Crosshair.target) - Data:GetGameplayCollisionRadius(Crosshair.Attack_Crosshair.target) < MeleeMenu.MeleeStickyRange and 
				Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
					return
			elseif GetDistance(mousePos) < Distance and GetDistance(mousePos) > 100 then
				Distance = GetDistance(mousePos)
			end

			local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
			local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
			local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)

			myHero:MoveTo(MoveX, MoveZ)
			self.LastMoved = Helper.Tick
			self.HasStopped = false
		end
	end
end

function _MyHero:Attack(target)

	if not self:HeroCanAttack() then
		MyHero:Move()
		return
	end

	if self.CanAttack and not Helper:IsEvading() then
		if target.type ~= myHero.type then 
			MuramanaOff()
		end

		for _, func in pairs(Plugins.RegisteredPreAttack) do
			func(target)
		end

		myHero:Attack(target)
		Orbwalker.LastEnemyAttacked = target
	end
end

function _MyHero:MovementEnabled(canMove)
	self.CanMove = canMove
end

function _MyHero:AttacksEnabled(canAttack)
	self.CanAttack = canAttack
end

function _MyHero:HeroCanAttack()
	if not AutoCarryMenu.Attacks and Keys.AutoCarry then
		return false
	elseif not LastHitMenu.Attacks and Keys.LastHit then
		return false
	elseif not MixedModeMenu.Attacks and Keys.MixedMode then
		return false
	elseif not LaneClearMenu.Attacks and Keys.LaneClear then
		return false
	end
	return true
end

function _MyHero:HeroCanMove()
	if self.InStandZone or not self.CanMove then
		return false
	elseif not AutoCarryMenu.Movement and Keys.AutoCarry then
		return false
	elseif not LastHitMenu.Movement and Keys.LastHit then
		return false
	elseif not MixedModeMenu.Movement and Keys.MixedMode then
		return false
	elseif not LaneClearMenu.Movement and Keys.LaneClear then
		return false
	end
	return true
end

function _MyHero:CheckStopMovement()
	if not MyHero:HeroCanMove() and not self.HasStopped then
		myHero:HoldPosition()
		self.HasStopped = true
	end
end

--[[ 
		_Crosshair Class
	]]

class '_Crosshair' Crosshair = nil

--[[
		Initialise _Crosshair class

		damageType  	DAMAGE_PHYSICAL or DAMAGE_MAGIC
		attackRange 	Integer
		skillRange 		Integer
		targetFocused 	Boolean. Whether targets selected with left click should be focused.
		isCaster		Boolean. Whether spells should be prioritised over auto attacks.
]]

function _Crosshair:__init(damageType, attackRange, skillRange, targetFocused, isCaster)
	self.DamageType = damageType and damageType or DAMAGE_PHYSICAL
	self.AttackRange = attackRange
	self.SkillRange = skillRange
	self.TargetFocused = targetFocused
	self.IsCaster = isCaster
	self.Target = nil
	self.TargetMinion = nil
	self.Attack_Crosshair = TargetSelector(TARGET_LOW_HP_PRIORITY, 2000, DAMAGE_PHYSICAL, self.TargetFocused)
	self.Skills_Crosshair = TargetSelector(TARGET_LOW_HP_PRIORITY, skillRange, self.DamageType, self.TargetFocused)
	self.Attack_Crosshair:SetConditional(function(Hero) return self:Conditional(Hero) end)
	self.Attack_Crosshair:SetDamages(0, myHero.totalDamage, 0)
	self:ArrangePriorities()
	self.RangeScaling = true
	Crosshair = self

	self:UpdateCrosshairRange()
	self:LoadTargetSelector()

	AddTickCallback(function() self:_OnTick() end)
	AddUnloadCallback(function() self:_OnUnload() end)
end

function _Crosshair:_OnTick()
	self.Attack_Crosshair:update()
	if self.Attack_Crosshair.target then
		self.Target = self.Attack_Crosshair.target
	else
		self.Skills_Crosshair:update()
		self.Target = self.Skills_Crosshair.target
	end
	if ConfigurationMenu.Focused ~= self.TargetFocused then
		self.TargetFocused = ConfigurationMenu.Focused
		self.Attack_Crosshair.targetSelected = self.TargetFocused
		self.Skills_Crosshair.targetSelected = self.targetFocused
	end
	self.TargetMinion = Minions.Target
end

function _Crosshair:_OnUnload()
	self:SaveTargetSelector()
end

function _Crosshair:GetTarget()
	if ValidTarget(self.Attack_Crosshair.target) and not self.IsCaster then
		return self.Attack_Crosshair.target
	elseif ValidTarget(self.Skills_Crosshair.target) then
		return self.Skills_Crosshair.target
	end
end

function _Crosshair:HasOrbwalkTarget()
	return self and self.Target and self.Attack_Crosshair.Target and self.Target == self.Attack_Crosshair.target
end

function _Crosshair:ArrangePriorities()
	if #GetEnemyHeroes() < 5 then return end
	for _, Champion in pairs(Data.ChampionData) do
		TS_SetHeroPriority(Champion.Priority, Champion.Name)
	end
end

function _Crosshair:SetSkillCrosshairRange(Range)
	self.RangeScaling = false
	self.Skills_Crosshair.range = Range
end

function _Crosshair:UpdateCrosshairRange()
	for _, Skill in pairs(Skills.SkillsList) do
		if Skill:GetRange() > self.Skills_Crosshair.range then
			self.Skills_Crosshair.range = Skill:GetRange()
		end
	end
end

function _Crosshair:SaveTargetSelector()
	local save = GetSave("SidasAutoCarry")
	save.TargetSelectorMode = Crosshair.Attack_Crosshair.mode
	save:Save()
end

function _Crosshair:LoadTargetSelector()
	local save = GetSave("SidasAutoCarry")
	if save.TargetSelectorMode then
		Crosshair.Attack_Crosshair.mode = save.TargetSelectorMode
		Crosshair.Skills_Crosshair.mode = save.TargetSelectorMode
	end
end

function _Crosshair:Conditional(Hero)
	return Hero.team ~= myHero.team and Orbwalker:CanOrbwalkTarget(Hero) and not Data:EnemyIsImmune(Hero)
end

--[[
		_Minions Class
]]

class '_Minions' Minions = nil

function _Minions:__init()
	self.ScanRange = 3000
	self.MinionHealthBuffer = 1.5
	self.EnemyMinions = minionManager(MINION_ENEMY, 1000, myHero, MINION_SORT_HEALTH_ASC)
    self.AllyMinions = minionManager(MINION_ALLY, self.ScanRange, myHero, MINION_SORT_HEALTH_ASC)
    self.IncomingDamage = {}
    self.TowerAttacks = {}
    self.LastHitEnabled = true
    self.NextUpdate = GetTickCount() + 128
    self.AverageUpdate = 128
    self.FirstUpdate = GetTickCount()
    self.KillableMinion = nil
    self.TowerMinion = nil
    Minions = self

    AddTickCallback(function() self:_OnTick() end)
    AddProcessSpellCallback(function(Unit, Spell)self:_OnProcessSpell(Unit, Spell) end)
    AddRecvPacketCallback(function(Packet) self:_OnRecvPacket(Packet) end)
    AddDeleteObjCallback(function(Object) self:_OnDeleteObj(Object) end)
end

function OnDraw()
	if Minions.AlmostKillable then
		Helper:DrawCircleObject(Minions.AlmostKillable, 200, Helper.Colour.Green, 1)
	end
end

local updates = {}

function _Minions:_OnTick()
	self.EnemyMinions:update()
	self.AllyMinions:update()

	if not Orbwalker:CanOrbwalkTarget(self.KillableMinion) then
		local _Menu = MenuManager:GetActiveMenu()
		if _Menu and FarmMenu.Freeze and (_Menu.name ~= "sidasaclaneclear") then
			Killable, AlmostKillable = self:GetKillableMinionFreeze()
		else
			Killable, AlmostKillable = self:GetKillableMinion()
		end

		self.TowerMinion = self:GetTowerMinion()
		
		if Orbwalker:CanOrbwalkTarget(Killable) then
			self.KillableMinion = Killable
		elseif Orbwalker:CanOrbwalkTarget(AlmostKillable) then
			self.AlmostKillable = AlmostKillable
		else
			self.KillableMinion = nil
			self.AlmostKillable = nil
		end
	end
end

function _Minions:_OnProcessSpell(Unit, Spell)
	if Unit and Spell.target and Unit.team == myHero.team and not Unit.isMe and GetDistance(Unit) <= self.ScanRange then
		local Attack = _Attack(Unit, Spell)
		if Attack.Type == ATTACK_TYPE_TOWER then
			self.TowerAttacks[Unit.networkID] = Attack
		else
			self.IncomingDamage[Unit.networkID] = Attack
		end
	end
end

function _Minions:_OnDeleteObj(Obj)
	if self.KillableMinion and Obj == self.KillableMinion and not Orbwalker:CanMove() and Orb == Orbwalker.LastEnemyAttacked then
		myHero:HoldPosition()
	end
end

function _Minions:_OnRecvPacket(Packet)
	if Packet.header == 0xC4 then
		self.LastUpdate = GetTickCount()
		if #updates > 200 then
			table.remove(updates,1)
		end
		table.insert(updates, GetTickCount())
	end

	self.AverageUpdate = self:GetAverageUpdateTick()
	self.NextUpdate = GetTickCount() + self.AverageUpdate
end

function _Minions:GetAverageUpdateTick()
	local _Total = 0
	if #updates < 2 then return 128 end

	for i = #updates, 1, -1 do
		if updates[i-1] then
			_Total = _Total + (updates[i] - updates[i-1])
		end
	end
	return _Total / #updates
	--return 128
end

function _Minions:GetDamageScaling(Minion)
	local Scale = 0 --= DebugMenu.Scale
	-- local Base = DebugMenu.Base
	local Delay = ((Orbwalker.LastWindUp - 250) / 1000) * 1.5
	local Proj = ((10 - (MyHero.ProjectileSpeed * 5))) / 10
	--local AD = (300 - myHero.totalDamage) / 1000

	Delay = (Delay < 0 and 0 or Delay)
	Proj = (Proj < 0 and 0 or Proj)
	--AD = (AD < 0 and 0 or AD)

	Scale = (Delay + Proj) / 10
--	DebugMenu.Scale = Scale

	--print(Scale)

	local AttackCount = self:GetNumberOfAttackingMinions(Minion) - 3
	--AttackCount = AttackCount <= 4 and 1 or AttackCount

	return 0.7 + (Scale * AttackCount)
end

function _Minions:GetAttackDamageUpdate(Attack)
	if Attack.LandsAt < self.NextUpdate then
		return self.NextUpdate
	else
		for i = 1, 1000 do
			if Attack.LandsAt < self.NextUpdate + (self.AverageUpdate * i) then
				return self.NextUpdate + (self.AverageUpdate * i)
			end
		end
	end
	return self.NextUpdate
end

function _Minions:GetTowerMinion()
	for _, Attack in pairs(self.TowerAttacks) do
		local Minion = Attack.Target
		local Health = Minion.health
		local Damage = Attack.Damage

		if ValidTarget(Minion) then
			local MyDamage = MyHero:GetTotalAttackDamageAgainstTarget(Minion, true) * self:GetDamageScaling(Minion)

			local damage, total = self:DoMinionCalc(Minion, nil, true)
			if damage and type(damage) == "number" then
				Health = Health - damage -- remove minion damage
			end

			--local TimeToHit = MyHero:GetTimeToHitTarget(Attack.Target)

			--1 tower hit is enough for kill
			if Health - Damage > 0 and Health - Damage < MyDamage then
				return
			end

			--1 tower hit and 1 AA is enough for kill
			if Health - Damage > 0 and Health - Damage < MyDamage * 2 then
				return Minion
			end


			--2 tower hits is enough for kill
			if Health - (Damage * 2) > 0 and Health - (Damage * 2) < MyDamage then
				return
			end	

			if Health - (Damage * 2) - MyDamage * 2 > 0 then
				--return Minion
			end

		end

	end
end


function _Minions:LaneClear()
	if self.LastHitEnabled then
		if ConfigurationMenu.SupportMode then
			Orbwalker:Orbwalk(self:FindExecutableMinion())
		else
			if Orbwalker:CanOrbwalkTarget(self.KillableMinion) then
				Orbwalker:Orbwalk(self.KillableMinion)
			elseif self.AlmostKillable then
				MyHero:Move()
			elseif Structures:CanOrbwalkStructure() then
				Orbwalker:OrbwalkIgnoreChecks(Structures:GetTargetStructure())
			else
				local lowestMinion = self:GetLowestHealthMinion()
				if (lowestMinion and self:GetNumberOfAttackingMinions(lowestMinion) < 1) then
					Orbwalker:Orbwalk(lowestMinion)
				else
					Orbwalker:Orbwalk(self:GetSecondLowestHealthMinion())
				end
			end
		end
	else
		MyHero:Move()
	end
end

function _Minions:LastHit()
	if self.LastHitEnabled then
		if ConfigurationMenu.SupportMode then
			Orbwalker:Orbwalk(self:FindExecutableMinion())
		elseif Orbwalker:CanOrbwalkTarget(self.KillableMinion) then
			Orbwalker:Orbwalk(self.KillableMinion)
		else
			Orbwalker:Orbwalk(self.TowerMinion)
		end
	else
		MyHero:Move()
	end
end

function _Minions:GetNumberOfAttackingMinions(Target)
	local Count = 0
	for _, Attack in pairs(self.IncomingDamage) do
		if Attack.Target.networkID == Target.networkID then
			Count = Count + 1
		end
	end
	return Count
end

function _Minions:AttackIsValid(Attack)
	if not ValidTarget(Attack.Source, 2000, false) then
		if Attack.Type == ATTACK_TYPE_MELEE_MINION then
			return false
		elseif (Attack.Type == ATTACK_TYPE_CASTER_MINION or Attack.Type == ATTACK_TYPE_CANNON_MINION) and GetTickCount() < Attack.FiresAt then
			return false
		end
	end
	
	if not ValidTarget(Attack.Target) or GetTickCount() > Attack.UpdatesAt then
		return false
	end
	
	if GetDistance(Attack.Source, Attack.Origin) > 3 then
		if GetTickCount() < Attack.FiresAt then
			return false
		end
	end
					
	return true			
end

function _Minions:GetPredictedDamage(Attack)
	if not self:AttackIsValid(Attack) then
		return 0, 0
	else
		local TimeToHit = 0
		if MyHero.IsMelee then
			TimeToHit = Orbwalker.LastWindUp + Orbwalker:GetNextAttackTime()
		else
			TimeToHit = MyHero:GetTimeToHitTarget(Attack.Target)
		end
		
		if TimeToHit > Attack.UpdatesAt then
			return Attack.Damage, Attack.Damage
		else
			return 0, Attack.Damage
		end
	end
end

function _Minions:GetKillableMinion(Freeze)
	Freeze = false
	for _, Cannon in pairs(self:GetEnemyCannons()) do
		local damage, total = self:DoMinionCalc(Cannon, Freeze)
		if damage or total then
			return damage, total
		end
	end

	for _, EnemyMinion in ipairs(self.EnemyMinions.objects) do
		local damage, total = self:DoMinionCalc(EnemyMinion, Freeze)
		if damage or total then
			return damage, total
		end
	end
end

function _Minions:DoMinionCalc(EnemyMinion, Freeze, IsTower)
	local Damage, TotalDamage, MyDamage = 0, 0, 0

	if Freeze then
		MyDamage = MyHero:GetTotalAttackDamageAgainstTarget(EnemyMinion, true)
		MyDamage = MyDamage < 50 and MyDamage or 50
	else
		MyDamage = MyHero:GetTotalAttackDamageAgainstTarget(EnemyMinion, true)
	end

	if IsTower then
		MyDamage = MyDamage * 1.2
	end

	if Orbwalker:CanOrbwalkTarget(EnemyMinion) or (MyHero.IsMelee and GetDistance(EnemyMinion) < MyHero.TrueRange + 75) then
		if EnemyMinion.health < MyDamage or self:CanExecuteMinion(EnemyMinion) then
			return EnemyMinion, nil
		elseif self:GetNumberOfAttackingMinions(EnemyMinion) <= 3 and not IsTower then
			return
		else
			MyDamage = MyDamage * self:GetDamageScaling(EnemyMinion)
			for _, Attack in pairs(self.IncomingDamage) do
				if Attack.Target.networkID == EnemyMinion.networkID then
					local _Damage, _TotalDamage = self:GetPredictedDamage(Attack)
					Damage = Damage + _Damage
					TotalDamage = TotalDamage + _TotalDamage
				end
			end

			if EnemyMinion.health - Damage < MyDamage then
				return EnemyMinion, nil
			end
			if not Freeze then
				for _, func in pairs(Plugins.RegisteredBonusLastHitDamage) do
					if EnemyMinion.health - Damage < MyDamage + func(EnemyMinion, MyDamage) then
						return EnemyMinion, nil
					end
				end
			end
			if EnemyMinion.health - TotalDamage < MyDamage then
				return nil, EnemyMinion
			end
		end
	end
end

-- probably broke
-- function _Minions:GetPredictedDamageOnTarget(Target, LandsAt)
-- 	local EnemyMinion = Target
-- 	local Damage, TotalDamage, MyDamage = 0, 0, 0

-- 	for _, Attack in pairs(self.IncomingDamage) do
-- 		if Attack.Type == ATTACK_TYPE_TOWER then
-- 			--
-- 		else
-- 			local _Damage, _TotalDamage = 0, 0
-- 			if Attack.Target.networkID == EnemyMinion.networkID then
-- 				if not ValidTarget(Attack.Source) then
-- 					if Attack.Type == ATTACK_TYPE_MELEE_MINION then
-- 						self.IncomingDamage[Attack.Source.networkID] = nil
-- 					elseif (Attack.Type == ATTACK_TYPE_CASTER_MINION or Attack.Type == ATTACK_TYPE_CANNON_MINION) and GetTickCount() < Attack.FiresAt then
-- 						self.IncomingDamage[Attack.Source.networkID] = nil
-- 					end
-- 				end
-- 				if not ValidTarget(Attack.Target) or Helper.Tick > Attack.LandsAt then
-- 					self.IncomingDamage[Attack.Source.networkID] = ni
-- 				end
-- 				if GetDistance(Attack.Source, Attack.Origin) > 3 and ((Attack.Type == ATTACK_TYPE_MELEE_MINON and GetTickCount() < self.FiresAt) or ((Attack.Type == ATTACK_TYPE_CASTER_MINION or Attack.Type == ATTACK_TYPE_CANNON_MINION) and GetTickCount() < Attack.LandsAt)) then
-- 					self.IncomingDamage[Attack.Source.networkID] = nil
-- 				end
				
-- 				else
-- 					if LandsAt and Attack.LandsAt < LandsAt then
-- 						_Damage = Attack.Damage
-- 					end
-- 					_TotalDamage = Attack.Damage
-- 				end
-- 				Damage = Damage + _Damage
-- 				TotalDamage = TotalDamage + _TotalDamage
-- 			end
-- 		end
-- 	end

-- 	return Damage, TotalDamage
-- end

function _Minions:FindExecutableMinion(Minion)
	for _, Minion in pairs(self.EnemyMinions.objects) do
		if Orbwalker:CanOrbwalkTarget(Minion) and self:CanExecuteMinion(Minion) then
			return Minion
		end
	end
end

function _Minions:CanExecuteMinion(Minion)
	return MyHero.HasSpoils and Minion.health < 200 + myHero.damage and Helper:CountAlliesInRange(1000) > 0 
end

function _Minions:GetKillableMinionFreeze()
	return self:GetKillableMinion(true)
end

function _Minions:GetLowestHealthMinion()
	for i =1, #self.EnemyMinions.objects, 1 do
		local Minion = self.EnemyMinions.objects[i]
		if Orbwalker:CanOrbwalkTarget(Minion) then
			return Minion
		end
	end
end

function _Minions:GetSecondLowestHealthMinion()
	local found = nil
	for i =1, #self.EnemyMinions.objects, 1 do
		local Minion = self.EnemyMinions.objects[i]
		if Orbwalker:CanOrbwalkTarget(Minion) and found then
			return Minion
		elseif Orbwalker:CanOrbwalkTarget(Minion) then
			found = Minion
		end
	end
	return found
end

function _Minions:GetEnemyCannons()
	local Cannons = {}
	for _, Minion in pairs(self.EnemyMinions.objects) do
		if Data:IsCannonMinion(Minion) then
			table.insert(Cannons, Minion)
		end
	end
	return Cannons
end

function _Minions:SetMixedModeLastHitPriority(Enabled)
	MixedModeMenu.MinionPriority = Enabled
end

function _Minions:SetLaneClearLastHitPriority(Enabled)
	LaneClearMenu.MinionPriority = Enabled
end

function _Minions:ToggleLastHit(Enabled)
	self.LastHitEnabled = Enabled
end

function _Minions:UpdateAttackIntervals()
	for _, Attack in pairs(self.IncomingDamage) do
		Attack.UpdatesAt = VIP_USER and self:GetAttackDamageUpdate(self) or Attack.LandsAt
	end
end

--[[
		_Attack Class
]]

ATTACK_TYPE_MELEE_MINON = 0
ATTACK_TYPE_CASTER_MINION = 1
ATTACK_TYPE_CANNON_MINION = 2
ATTACK_TYPE_PLAYER = 3
ATTACK_TYPE_TOWER = 4


class '_Attack'

function _Attack:__init(Source, Spell)
	self.Source = Source
	self.Target = Spell.target
	self.Spell = Spell
	self.Type = self:GetType(Source)
	self.Started = GetTickCount()
	self.Delay = Spell.windUpTime * 1000
	self.Speed = self:GetProjectileSpeed(Source, Spell)
	self.LandsAt = ((self.Speed == 0 and self.Delay or self.Delay + GetDistance(Source, self.Target) / self.Speed) + GetTickCount())
	self.UpdatesAt = VIP_USER and Minions:GetAttackDamageUpdate(self) or self.LandsAt
	self.FiresAt = GetTickCount() + self.Delay
	self.Origin = {x = Source.x, z = Source.z}
	self.IsTowerShot = false
	self.Damage = self:CalcDamageOfAttack(self.Source, self.Target, self.Spell, 0)

	if self.Type == ATTACK_TYPE_TOWER then
		--self.Damage = self.Damage * 1.5
	end
end

function _Attack:GetProjectileSpeed(Unit, Spell)
	local MissileSpeed = SpellDatabase[Spell.name] ~= nil and (SpellDatabase[Spell.name]['SpellData:MissileSpeed'] or SpellDatabase[Spell.name]['SpellData:MissileMaxSpeed']) or 0
    return (Unit.isMelee and 0) or MissileSpeed / 1000
end

function _Attack:GetType(Source)
	if Source.charName:find("Wizard") then
		return ATTACK_TYPE_CASTER_MINION
	elseif Source.charName:find("Basic") then
		return ATTACK_TYPE_MELEE_MINON
	elseif Source.charName:find("MechCannon") then
		return ATTACK_TYPE_CANNON_MINION
	elseif Source.type == myHero.type then
		return ATTACK_TYPE_PLAYER
	elseif Source.type == "obj_AI_Turret" then
		return ATTACK_TYPE_TOWER
	else
		return -1
	end
end

function _Attack:CalcDamageOfAttack(source, target, spell, additionalDamage)
	local additionalDamage = 0
    -- read initial armor and damage values
    local armorPenPercent = source.armorPenPercent
    local armorPen = source.armorPen
    local totalDamage = source.totalDamage + (additionalDamage or 0)
    local damageMultiplier = spell.name:find("CritAttack") and 2 or 1

    -- minions give wrong values for armorPen and armorPenPercent
    if source.type == "obj_AI_Minion" then
        armorPenPercent = 1
    elseif source.type == "obj_AI_Turret" then
        armorPenPercent = 0.7
    end

    -- turrets ignore armor penetration and critical attacks
    if target.type == "obj_AI_Turret" then
        armorPenPercent = 1
        armorPen = 0
        damageMultiplier = 1
    end

    -- calculate initial damage multiplier for negative and positive armor

    local targetArmor = (target.armor * armorPenPercent) - armorPen
    if targetArmor < 0 then -- minions can't go below 0 armor.
        --damageMultiplier = (2 - 100 / (100 - targetArmor)) * damageMultiplier
        damageMultiplier = 1 * damageMultiplier
    else
        damageMultiplier = 100 / (100 + targetArmor) * damageMultiplier
    end

    -- use ability power or ad based damage on turrets
    if source.type == "obj_AI_Hero" and target.type == "obj_AI_Turret" then
        totalDamage = math.max(source.totalDamage, source.damage + 0.4 * source.ap)
    end

    -- minions deal less damage to enemy heros
    if source.type == "obj_AI_Minion" and target.type == "obj_AI_Hero" and source.team ~= TEAM_NEUTRAL then
        damageMultiplier = 0.60 * damageMultiplier
    end

    -- heros deal less damage to turrets
    if source.type == "obj_AI_Hero" and target.type == "obj_AI_Turret" then
        damageMultiplier = 0.95 * damageMultiplier
    end

    -- minions deal less damage to turrets
    if source.type == "obj_AI_Minion" and target.type == "obj_AI_Turret" then
        damageMultiplier = 0.475 * damageMultiplier
    end

    -- siege minions and superminions take less damage from turrets
    if source.type == "obj_AI_Turret" and (target.charName == "Red_Minion_MechCannon" or target.charName == "Blue_Minion_MechCannon") then
        damageMultiplier = 0.8 * damageMultiplier
    end

    -- caster minions and basic minions take more damage from turrets
    if source.type == "obj_AI_Turret" and (target.charName == "Red_Minion_Wizard" or target.charName == "Blue_Minion_Wizard" or target.charName == "Red_Minion_Basic" or target.charName == "Blue_Minion_Basic") then
        damageMultiplier = (1 / 0.875) * damageMultiplier
    end

    -- turrets deal more damage to all units by default
    if source.type == "obj_AI_Turret" then
        damageMultiplier = 1.05 * damageMultiplier
    end

    -- turret damage scales for champions
    -- if source.type == "obj_AI_Turret" and target.type == "obj_AI_Hero" then
    --     if turretStacks[source.networkID] and turretStacks[source.networkID].lastTarget == target.networkID and (GetTickCount() - turretStacks[source.networkID].lastAttack) < 4000 then
    --         damageMultiplier = (1 + (0.25 * math.min(turretStacks[source.networkID].attackCount, 5))) * damageMultiplier

    --         turretStacks[source.networkID].attackCount = turretStacks[source.networkID].attackCount + 1
    --         turretStacks[source.networkID].lastAttack = GetTickCount()
    --     elseif turretStacks[source.networkID] and turretStacks[source.networkID].lastTargetType == "obj_AI_Hero" and (GetTickCount() - turretStacks[source.networkID].lastAttack) < 4000 then
    --         turretStacks[source.networkID].attackCount = math.min(turretStacks[source.networkID].attackCount, 3)

    --         damageMultiplier = (1 + (0.25 * turretStacks[source.networkID].attackCount)) * damageMultiplier

    --         turretStacks[source.networkID].lastTarget = target
    --         turretStacks[source.networkID].attackCount = turretStacks[source.networkID].attackCount + 1
    --         turretStacks[source.networkID].lastAttack = GetTickCount()
    --     else
    --         turretStacks[source.networkID] = {
    --             lastTarget = target.networkID,
    --             lastTargetType = target.type,
    --             lastAttack = GetTickCount(),
    --             attackCount = 1
    --         }
    --     end
    -- end

    -- calculate damage dealt
    return damageMultiplier * totalDamage
end

--[[
		_Jungle Class
]]

class '_Jungle' Jungle = nil

function _Jungle:__init()
	self.JungleMonsters = {}
	Jungle = self
	for i = 0, objManager.maxObjects do
	local object = objManager:getObject(i)
		if Data:IsJungleMinion(object) then
			table.insert(self.JungleMonsters, object)
		end
	end

	AddCreateObjCallback(function(Object) self:_OnCreateObj(Object) end)
	AddDeleteObjCallback(function(Object) self:_OnDeleteObj(Object) end)
end

function _Jungle:_OnCreateObj(Object)
	if Data:IsJungleMinion(Object) then
		table.insert(self.JungleMonsters, Object)
	end
end

function _Jungle:_OnDeleteObj(Object)
	if Data:IsJungleMinion(Object) then
		for i, Obj in pairs(self.JungleMonsters) do
			if obj == Object then
				table.remove(self.JungleMonsters, i)
			end
		end
	end
end

function _Jungle:GetJungleMonsters()
	return self.JungleMonsters
end

function _Jungle:GetAttackableMonster()
	local HighestPriorityMonster =  nil
	local Priority = 0
	for _, Monster in pairs(self.JungleMonsters) do
		if Orbwalker:CanOrbwalkTarget(Monster) then
			local CurrentPriority = Data:GetJunglePriority(Monster.name)
			if Monster.health < MyHero:GetTotalAttackDamageAgainstTarget(Monster) then
				return Monster
			elseif not HighestPriorityMonster then
				HighestPriorityMonster = Monster
				Priority = CurrentPriority
			else
				if CurrentPriority < Priority then
					HighestPriorityMonster = Monster
					Priority = CurrentPriority
				end
			end
		end
	end
	return HighestPriorityMonster
end

class '_Structures' Structures = nil

function _Structures:__init()
	Structures = self
	self.TowerCollisionRange = 88.4
	self.InhibCollisionRange = 205
	self.NexusCollisionRange = 300
	self.TowerRange = 950
	self.EnemyTowers = {}
	self.AllyTowers = {}

	for i = 1, objManager.maxObjects do
		local Object = objManager:getObject(i)
		if Object and Object.type == "obj_AI_Turret" then
			if Object.team == myHero.team then
				table.insert(self.AllyTowers, Object)
			else
				table.insert(self.EnemyTowers, Object)
			end
		end
	end

	AddDeleteObjCallback(function(obj) self:_OnDeleteObj(obj) end)
end

function _Structures:_OnDeleteObj(Object)
	for i, Tower in pairs(self.AllyTowers) do
		if Object == Tower then
			table.remove(self.AllyTowers, i)
			return
		end
	end
	for i, Tower in pairs(self.EnemyTowers) do
		if Object == Tower then
			table.remove(self.EnemyTowers, i)
			return
		end
	end
end

function _Structures:TowerTargetted()
	return GetTarget() and GetTarget().type == "obj_AI_Turret" and GetTarget().team ~= myHero.team
end

function _Structures:InhibTargetted()
	return GetTarget() and GetTarget().type == "obj_BarracksDampener" and GetTarget().team ~= myHero.team
end

function _Structures:NexusTargetted()
	return GetTarget() and GetTarget().type == "obj_HQ" and GetTarget().team ~= myHero.team
end

function _Structures:CanOrbwalkStructure()
	return self:CanOrbwalkTower() or self:CanOrbwalkInhib() or self:CanOrbwalkNexus()
end

function _Structures:GetTargetStructure()
	return GetTarget()
end

function _Structures:CanOrbwalkTower()
	return self:TowerTargetted() and GetDistance(GetTarget()) - self.TowerCollisionRange < MyHero.TrueRange
end

function _Structures:CanOrbwalkInhib()
	return self:InhibTargetted() and GetDistance(GetTarget()) - self.InhibCollisionRange < MyHero.TrueRange
end

function _Structures:CanOrbwalkNexus()
	return self:NexusTargetted() and GetDistance(GetTarget()) - self.NexusCollisionRange < MyHero.TrueRange
end

function _Structures:PositionInEnemyTowerRange(Pos)
	for _, Tower in pairs(self.EnemyTowers) do
		if GetDistance(Tower, Pos) <= self.TowerRange then
			return true
		end
	end
	return false
end

function _Structures:PositionInAllyTowerRange(Pos)
	for _, Tower in pairs(self.AllyTowers) do
		if GetDistance(Tower, Pos) <= self.TowerRange then
			return true
		end
	end
	return false
end

function _Structures:GetClosestEnemyTower(Pos)
	local ClosestTower, Distance = nil, 0
	for i, Tower in pairs(self.EnemyTowers) do
		if not Tower or not Pos then return end
		if not ClosestTower then
			ClosestTower, Distance = Tower, GetDistance(Pos, Tower)
		elseif GetDistance(Pos, Tower) < Distance then
			ClosestTower, Distance = Tower, GetDistance(Pos, Tower)
		end
	end
	return ClosestTower
end

function _Structures:GetClosestAllyTower(Pos)
	local ClosestTower, Distance = nil, 0
	for _, Tower in pairs(self.AllyTowers) do
		if not ClosestTower then
			ClosestTower, Distance = Tower, GetDistance(Pos, Tower)
		elseif GetDistance(Pos, Tower) < Distance then
			ClosestTower, Distance = Tower, GetDistance(Pos, Tower)
		end
	end
	return ClosestTower
end

--[[
		_Skills Class
]]

class '_Skills' Skills = nil

function _Skills:__init()
 	self.SkillsList = {}
 	Skills = self
 	if VIP_USER then require "Collision" end
 	AddTickCallback(function() self:_OnTick() end)
end

function _Skills:_OnTick()
	for _, Skill in pairs(self.SkillsList) do
		if Keys.AutoCarry and AutoCarryMenu[Skill.RawName.."AutoCarry"] or
			Keys.MixedMode and MixedModeMenu[Skill.RawName.."MixedMode"] or
			Keys.LaneClear and LaneClearMenu[Skill.RawName.."LaneClear"] then
			Skill.Active = true
		else
			Skill.Active = false
		end
	end
	self:SortSkillOrder()
end

function _Skills:CastAll(Target)
	for _, Skill in ipairs(self.SkillsList) do
		if Skill.Enabled then
			Skill:Cast(Target)
		end
	end
end

function _Skills:GetSkill(Key)
	for _, Skill in pairs(self.SkillsList) do
		if Skill.Key == Key then
			return Skill
		end
	end
end

function _Skills:GetSkillMenu(Key)
	if MenuManager.SkillMenu[Key] then
		return MenuManager.SkillMenu[Key]
	else
		return {CastPrio = 0}
	end
end

function _Skills:SortSkillOrder()
	table.sort(self.SkillsList, function(a, b) return self:GetSkillMenu(a.Key).CastPrio < self:GetSkillMenu(b.Key).CastPrio end)
end

function _Skills:HasSkillReady()
	for _, Skill in pairs(self.SkillsList) do
		if Skill.Ready then
			return true
		end
	end
end

function _Skills:NewSkill(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision)
	return _Skill(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision, true)
end

function _Skills:DisableAll()
	for _, Skill in pairs(self.SkillsList) do
		Skill.Enabled = false
	end
end

--[[
		_Skill Class
]]

class '_Skill'

SPELL_TARGETED = 1
SPELL_LINEAR = 2
SPELL_CIRCLE = 3
SPELL_CONE = 4
SPELL_LINEAR_COL = 5
SPELL_SELF = 6
SPELL_SELF_AT_MOUSE = 7
AutoCarry.SPELL_TARGETED = 1
AutoCarry.SPELL_LINEAR = 2
AutoCarry.SPELL_CIRCLE = 3
AutoCarry.SPELL_CONE = 4
AutoCarry.SPELL_LINEAR_COL = 5
AutoCarry.SPELL_SELF = 6
AutoCarry.SPELL_SELF_AT_MOUSE = 7

-- --[[
-- 		Initialise _Skill class

-- 		enabled  			Boolean - set true for auto carry to automatically cast it, false for manual control in plugin
-- 		key 				Spell key, e.g _Q
-- 		range 				Spell range
-- 		displayName 		The name to display in menus
-- 		type 				SPELL_TARGETED, SPELL_LINEAR, SPELL_CIRCLE, SPELL_CONE, SPELL_LINEAR_COL, SPELL_SELF, SPELL_SELF_AT_MOUSE
-- 		minMana 			Minimum percentage mana before cast is allowed
-- 		afterAttack 		Boolean - set true to only cast right after an auto attack
-- 		reqAttackTarget 	Boolean - set true to only cast if a target is in attack range
-- 		speed 				Speed of the projectile for skillshots
-- 		delay 				Delay of the spell for skillshots
-- 		width 				Width of the projectile for skillshots
-- 		collision 			Boolean - set true to check minion collision before casting

-- ]]
function _Skill:__init(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision, custom)
	self.Key = key
	self.Range = range
	self.DisplayName = displayName
	self.RawName = self.DisplayName:gsub("[^A-Za-z0-9]", "")
	self.Type = type
	self.MinMana = minMana or 0
	self.AfterAttack = afterAttack or false
	self.ReqAttackTarget = reqAttackTarget or false
	self.Speed = speed or 0
	self.Delay = delay or 0
	self.Width = width or 0
	self.Collision = collision
	self.IsCustom = custom
	self.Active = true
	self.Enabled = enabled or false
	self.Ready = false
	if VIP_USER then
		self.KloPredObject = ProdictManager.GetInstance()
		if self.Type ~= SPELL_CONE then
			self.KloPredCallback = function(unit, pos, castSpell) 
				if GetDistanceSqr(pos, castSpell.Source) < castSpell.RangeSqr and  myHero:CanUseSpell(castSpell.Name) == READY then
					if (self.Collision and not self:GetCollision(pos)) or not self.Collision then
			        	CastSpell(castSpell.Name, pos.x, pos.z)
			        end
			    end 
			end
		else
			self.KloPredCallback = function(unit, pos, castSpell) 
				local conePos = GetCone(self.Delay, self.Width)
				if conePos then
					CastSpell(castSpell.Name, conePos.x, conePos.z)
				end
			end
		end
		
		self.KloPred = self.KloPredObject:AddProdictionObject(self.Key, self.Range, self.Speed * 1000, self.Delay / 1000, self.Width)
	end

	AddTickCallback(function() self:_OnTick() end)

	table.insert(Skills.SkillsList, self)
end

function _Skill:_OnTick()
	if self.Enabled then
		if Skills:GetSkillMenu(self.Key).ManualCast and Crosshair:GetTarget() then
			self:ForceCast(Crosshair:GetTarget())
		end
	end
	self.Ready = myHero:CanUseSpell(self.Key) == READY
	if self.Enabled and not self.IsCustom then
		self.reqAttackTarget = Skills:GetSkillMenu(self.Key).RequiresTarget
	end
end

function _Skill:Cast(Target, ForceCast)
	if not ForceCast then
		if (not self.Active and self.Enabled) or (not self.Enabled and not self.IsCustom) then
			return
		elseif self.AfterAttack and not Orbwalker:IsAfterAttack() then
			return
		elseif not self:ValidSkillTarget(Target) then
			return
		elseif (self.ReqAttackTarget and not Orbwalker:CanOrbwalkTarget(Target)) then
			return
		end
	end
	if not self:IsReady() then 
		return 
	end


	if self.Type == SPELL_SELF then
		CastSpell(self.Key)
	elseif self.Type == SPELL_SELF_AT_MOUSE then
		CastSpell(self.Key, mousePos.x, mousePos.z)
	elseif self.Type == SPELL_TARGETED then
		if ValidTarget(Target, self.Range) then
			CastSpell(self.Key, Target)
		end
	elseif self.Type == SPELL_CONE then
		if ValidTarget(Target, self.Range) then
			if not VIP_USER then
				local predPos = self:GetPrediction(Target)
				if predPos then
					CastSpell(self.Key, predPos.x, predPos.z)
				end
			else
				self.KloPred:GetPredictionCallBack(Target, self.KloPredCallback)
			end
		end
	elseif self.Type == SPELL_LINEAR or self.Type == SPELL_CIRCLE then
		if ValidTarget(Target) then
			if not VIP_USER then
				local predPos = self:GetPrediction(Target)
				if predPos then
					CastSpell(self.Key, predPos.x, predPos.z)
				end
			else
				self.KloPred:GetPredictionCallBack(Target, self.KloPredCallback)
			end
		end
	elseif self.Type == SPELL_LINEAR_COL then
		if ValidTarget(Target) then		
			if self.Collision then 
				if not VIP_USER then
					local predPos = self:GetPrediction(Target)
					if predPos then
						local collision = self:GetCollision(predPos)
						if not collision or ForceCast then
							CastSpell(self.Key, predPos.x, predPos.z)
						end
					end
				else
					self.KloPred:GetPredictionCallBack(Target, self.KloPredCallback)
				end
			end
		end
	end
end

function _Skill:ForceCast(Target)
	self:Cast(Target, true)
end

function _Skill:GetPrediction(Target)
	if VIP_USER then
		pred = TargetPredictionVIP(self.Range, self.Speed*1000, self.Delay/1000, self.Width)
		if pred and pred:GetHitChance(Target) > ConfigMenu.HitChance/100 then
			return pred:GetPrediction(Target)
		end
	elseif not VIP_USER then
		pred = TargetPrediction(self.Range, self.Speed, self.Delay, self.Width)
		return pred:GetPrediction(Target)
	end
end

function _Skill:GetCollision(pos)
	if VIP_USER and self.Collision then
		local col = Collision(self.Range, self.Speed*1000, self.Delay/1000, self.Width)
		return col:GetMinionCollision(myHero, pos)
	elseif self.Collision then
		for _, Minion in pairs(Minions.EnemyMinions.objects) do
			if ValidTarget(Minion) and myHero.x ~= Minion.x then
				local myX = myHero.x
				local myZ = myHero.z
				local tarX = pos.x
				local tarZ = pos.z
				local deltaX = myX - tarX
				local deltaZ = myZ - tarZ
				local m = deltaZ/deltaX
				local c = myX - m*myX
				local minionX = Minion.x
				local minionZ = Minion.z
				local distanc = (math.abs(minionZ - m*minionX - c))/(math.sqrt(m*m+1))
				if distanc < self.Width and ((tarX - myX)*(tarX - myX) + (tarZ - myZ)*(tarZ - myZ)) > ((tarX - minionX)*(tarX - minionX) + (tarZ - minionZ)*(tarZ - minionZ)) then
					return true
				end
			end
	   end
	   return false
	end
end

function _Skill:GetHitChance(pred)
	if VIP_USER then
		return pred:GetHitChance(target) > ConfigMenu.HitChance/100 
	end
end

function _Skill:ValidSkillTarget(Target) 
	if not self.IsCustom then
		local _Menu = Skills:GetSkillMenu(self.Key)
		if not Target or myHero.mana / myHero.maxMana * 100 < _Menu.MinMana or Target.health / Target.maxHealth * 100 < _Menu.MinHealth or Target.health / Target.maxHealth * 100 > _Menu.MaxHealth then
			return false
		end
		return true
	end
	return true
end

function _Skill:GetRange()
	return self.reqAttackTarget and MyHero.TrueRange or self.Range
end

function _Skill:IsReady()
	return myHero:CanUseSpell(self.Key) == READY
end

--[[
		_Items Class
]]

class '_Items' Items = nil

function _Items:__init()
	self.ItemList = {}
	Items = self

	AddTickCallback(function() self:_OnTick() end)
end

function _Items:_OnTick()
	for _, Item in pairs(self.ItemList) do
		if Keys.AutoCarry and AutoCarryMenu[Item.RawName.."AutoCarry"] or
			Keys.MixedMode and MixedModeMenu[Item.RawName.."MixedMode"] or
			Keys.LaneClear and LaneClearMenu[Item.RawName.."LaneClear"] then
			Item.Active = true
		else
			Item.Active = false
		end
	end
end

function _Items:UseAll(Target)
	if Target and Target.type == myHero.type then
		for _, Item in pairs(self.ItemList) do
			Item:Use(Target)
		end
	end
end

function _Items:UseItem(ID, Target)
	for _, Item in pairs(self.ItemList) do
		if Item.ID == ID then
			Item:Use(Target)
		end
	end
end

function _Items:GetItem(ID)
	for _, Item in pairs(self.ItemList) do
		if Item.ID == ID then
			return Item
		end
	end
end

function _Items:GetBotrkBonusLastHitDamage(StartingDamage, Target)
	local _BonusDamage = 0
	if GetInventoryHaveItem(3153) then
		if ValidTarget(Target) then
			_BonusDamage = Target.health / 20
			if _BonusDamage >= 60 then
				_BonusDamage = 60
			end
		end
	end
	return _BonusDamage
end

--[[
		_Item Class
]]

class '_Item'

--TODO: Add Muramana
function _Item:__init(_Name, _ID, _RequiresTarget, _Range, _Override)
	self.Name = _Name
	self.RawName = self.Name:gsub("[^A-Za-z0-9]", "")
	self.ID = _ID
	self.RequiresTarget = _RequiresTarget
	self.Range = _Range
	self.Slot = nil
	self.Override = _Override
	self.Active = true
	self.Enabled = true

	table.insert(Items.ItemList, self)
end

function _Item:Use(Target)
	if self.Override then 
		return self.Override() 
	end
	if self.RequiresTarget and not Target then 
		return 
	end
	if not self.Active or not self.Enabled then
		return
	end

	self.Slot = GetInventorySlotItem(self.ID)

	if self.Slot then	
		if self.ID == 3153 then -- BRK
			local _Menu = MenuManager:GetActiveMenu()
			if _Menu and _Menu.botrkSave then
				if  myHero.health <= myHero.maxHealth * 0.65 then
					CastSpell(self.Slot, Target)
				end
			else
				CastSpell(self.Slot, Target)
			end
		elseif self.ID == 3042 then -- Muramana
			if not MuramanaIsActive() then
				MuramanaOn()
			end
		elseif self.ID == 3069 then -- Talisman of Ascension
			if Helper:CountAlliesInRange(600) > 0 then
				CastSpell(self.Slot)
			end
		elseif not self.RequiresTarget and Orbwalker:CanOrbwalkTarget(Target) then
			CastSpell(self.Slot)
		elseif self.RequiresTarget and ValidTarget(Target) and GetDistance(Target) <= self.Range then
			CastSpell(self.Slot, Target)
		end
	end
end

--[[
		_Helper Class
]]

class '_Helper' Helper = nil

function _Helper:__init()
	self.Tick = 0
	self.Latency = 0
	self.Colour = {Green = 0x00FF00}
	self.EnemyTable = {}
	Helper = self
	self.EnemyTable = GetEnemyHeroes()
	self.AllyTable = GetAllyHeroes()
	self.AllHeroes = {}
	self:GetAllHeroes()
	self.DebugStrings = {}
	AddTickCallback(function() self:_OnTick() end)
	AddDrawCallback(function() self:_OnDraw() end)
end

function _Helper:_OnTick()
	self.Tick = GetTickCount()
	self.Latency = GetLatency()
end

function _Helper:StringContains(string, contains)
	return string:lower():find(contains)
end

function _Helper:DrawCircleObject(Object, Range, Colour, Thickness)
	Thickness = Thickness and Thickness or 0
	for i = 0, Thickness do
		if DrawingMenu.LowFPS then
			self:DrawCircle2(Object.x, Object.y, Object.z, Range + i, Colour)
		else
			DrawCircle(Object.x, Object.y, Object.z, Range + i, Colour)
		end
	end
end

-- Low fps circles by barasia, vadash and viseversa
function _Helper:DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
		quality = math.max(8,self:round(180/math.deg((math.asin((chordlength/(2*radius)))))))
		quality = 2 * math.pi / quality
		radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end

function _Helper:round(num) 
	if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function _Helper:DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        self:DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
    end
end

function _Helper:GetHitBoxDistance(Target)
	return GetDistance(Target) - GetDistance(Target, Target.minBBox)
end

function _Helper:TrimString(s)
	return s:find'^%s*$' and '' or s:match'^%s*(.*%S)'
end

function _Helper:GetClasses()
	return AutoCarry.Skills, AutoCarry.Keys, AutoCarry.Items, AutoCarry.Data, AutoCarry.Jungle, AutoCarry.Helper, AutoCarry.MyHero, AutoCarry.Minions, AutoCarry.Crosshair, AutoCarry.Orbwalker
end

function _Helper:ArgbFromMenu(menu)
	return ARGB(menu[1], menu[2], menu[3], menu[4])
end

function _Helper:DecToHex(Dec)
	local B, K, Hex, I, D = 16, "0123456789ABCDEF", "", 0
	while Dec > 0 do
		I = I + 1
		Dec, D = math.floor(Dec / B), math.fmod(Dec, B) + 1
		Hex = string.sub(K, D, D)..Hex
	end
	return Hex
end

function _Helper:HexFromMenu(menu)
	local argb = {}
	argb["a"] = menu[1]
	argb["r"] = menu[2]
	argb["g"] = menu[3]
	argb["b"] = menu[4]
	return tonumber(self:DecToHex(argb["a"]) .. self:DecToHex(argb["r"]) .. self:DecToHex(argb["g"]) .. self:DecToHex(argb["b"]), 16);
end

function _Helper:IsEvading()
	return _G.evade
end

function _Helper:GetAllHeroes()
	for i = 1, heroManager.iCount do
        local hero = heroManager:GetHero(i)
        table.insert(self.AllHeroes, hero)
    end
end

function _Helper:Debug(str)
	table.insert(self.DebugStrings, str)
end

function _Helper:_OnDraw()
	local Height = 200
	for _, Str in pairs(self.DebugStrings) do
		DrawText(tostring(Str), 15, 100, Height, 0xFFFFFF00)
		Height = Height + 20
	end
	self.DebugStrings = {}
end

function _Helper:CountAlliesInRange(Range)
	local _Count = 0
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally ~= myHero and GetDistance(Ally) <= Range then
			_Count = _Count + 1
		end
	end
	return _Count
end

--[[
		Keys Class
]]

class '_Keys' Keys = nil

function _Keys:__init()
	self.KEYS_KEY = 0
	self.KEYS_MENUKEY = 1
	self.AutoCarry = false
	self.MixedMode = false
	self.LastHit = false
	self.LaneClear = false
	self.AutoCarryKeys = {}
	self.MixedModeKeys = {}
	self.LastHitKeys = {}
	self.LaneClearKeys = {}
	Keys = self

	AddTickCallback(function() self:_OnTick() end)
end

function _Keys:_OnTick()
	self.AutoCarry = self:IsKeyEnabled(self.AutoCarryKeys)
	self.MixedMode = self:IsKeyEnabled(self.MixedModeKeys)
	self.LastHit = self:IsKeyEnabled(self.LastHitKeys)
	self.LaneClear = self:IsKeyEnabled(self.LaneClearKeys)
end

function _Keys:IsKeyEnabled(List)
	for _, Key in pairs(List) do
		if Key.Type == self.KEYS_KEY then
			if IsKeyDown(Key.Key) then
				return true
			end
		elseif Key.Type == self.KEYS_MENUKEY then
			if Key.Menu[Key.Param] then
				return true
			end
		end
	end
	return false
end

function _Keys:RegisterMenuKey(Menu, Param, Mode)
	local MenuKey = _MenuKey(Menu, Param)
	self:Insert(MenuKey, Mode)
end

function _Keys:RegisterKey(key, Mode)
	local Key = _Key(key)
	self:Insert(Key, Mode)
end

function _Keys:UnregisterKey(_Key, Mode)
	for i, Key in pairs(self:GetKeyList(Mode)) do
		if Key.Key == _Key then
			table.remove(self:GetKeyList(Mode), i)
		end
	end
end

function _Keys:Insert(Key, Mode)
	if Mode == MODE_AUTOCARRY then
		table.insert(self.AutoCarryKeys, Key)
	elseif Mode == MODE_MIXEDMODE then
		table.insert(self.MixedModeKeys, Key)
	elseif Mode == MODE_LASTHIT then
		table.insert(self.LastHitKeys, Key)
	elseif Mode == MODE_LANECLEAR then
		table.insert(self.LaneClearKeys, Key)
	end
end

function _Keys:GetKeyList(Mode)
	if Mode == MODE_AUTOCARRY then
		return self.AutoCarryKeys
	elseif Mode == MODE_MIXEDMODE then
		return self.MixedModeKeys
	elseif Mode == MODE_LASTHIT then
		return self.LastHitKeys
	elseif Mode == MODE_LANECLEAR then
		return self.LaneClearKeys
	end
end


--[[
		Key Class
]]

class '_Key'

function _Key:__init(key)
	self.Key = key
	self.Type = Keys.KEYS_KEY
end

--[[
		MenuKey Class
]]

class '_MenuKey'

function _MenuKey:__init(menu, param)
	self.Menu = menu
	self.Param = param
	self.Type = Keys.KEYS_MENUKEY
end

--[[
		_SwingTimer Class
]]

class '_SwingTimer' SwingTimer = nil

function _SwingTimer:__init()
	self.bar_green = GetSprite("SidasAutoCarry\\bar_green.dds")
	self.bar_red = GetSprite("SidasAutoCarry\\bar_red.dds")
	self.Width = 300
	self.X = WINDOW_W/2 - (self.Width/2)
	self.Y = WINDOW_H/2
	self.Height = self.bar_green.height	
	self.ResizeButtonWidth = 30
	self.Moving = false
	self.Resizing = false
	self.Save = GetSave("SidasAutoCarry").SwingTimer
	SwingTimer = self

	if self.Save then
		self.X = self.Save.X
		self.Y = self.Save.Y
		self.Width = self.Save.Width
	end
	AddMsgCallback(function(Msg, Key) self:_OnWndMsg(Msg, Key) end)
	AddDrawCallback(function() self:_OnDraw() end)
	AddTickCallback(function() self:_OnTick() end)
end

function _SwingTimer:_OnDraw()
	if not ExtrasMenu.ShowSwingTimer then return end
	local Difference = (Helper.Tick - Orbwalker.LastAttack) / (Orbwalker:GetNextAttackTime() - Orbwalker.LastAttack)
	if Difference > 1 or Difference < 0 then 
		self:_DrawBar(self.X, self.Y, self.Width, 1)
	else
		self:_DrawBar(self.X, self.Y, self.Width, Difference)
	end
	if IsKeyDown(16) then
		DrawRectangleOutline(self.X + self.Width + 5, self.Y, self.ResizeButtonWidth, self.Height, ARGB(0xFF,0xFF,0xFF,0xFF), 3)
	end
end

function _SwingTimer:_OnWndMsg(Msg, Key)
	if Msg == WM_LBUTTONDOWN and IsKeyDown(16) then
		if CursorIsUnder(self.X, self.Y, self.Width, self.Height) then
			self.Moving = true
		elseif CursorIsUnder(self.X + self.Width + 5, self.Y, self.ResizeButtonWidth, self.Height) then
			self.Resizing = true
		end
	elseif Msg == WM_LBUTTONUP and (self.Moving or self.Resizing) then
		self.Moving = false
		self.Resizing = false
		GetSave("SidasAutoCarry").SwingTimer = {X = self.X, Y = self.Y, Height = self.Height, Width = self.Width}
	end
end

function _SwingTimer:_OnTick()
	if self.Moving then
		self.X = GetCursorPos().x - self.Width / 2
		self.Y = GetCursorPos().y
	elseif self.Resizing then
		self.Width = GetCursorPos().x - self.X - self.ResizeButtonWidth / 2
	end
end

function _SwingTimer:_DrawBar(x, y, barLen, percentage)
	DrawRectangleOutline(x-1, y-1, barLen+3, self.Height+2, ARGB(0x00,0xFF,0xFF,0xFF), 1)
    self.bar_green:DrawEx(Rect(0, 0, barLen*percentage, self.Height), D3DXVECTOR3(0,0,0), D3DXVECTOR3(x,y,0), 0xFF)
    self.bar_red:DrawEx(Rect(barLen*percentage, 0, barLen, self.Height), D3DXVECTOR3(0,0,0), D3DXVECTOR3(x+barLen*percentage,y,0), 0xFF)
    DrawTextA(math.floor(percentage*100).."%", 12, x+(barLen/2), y-1, ARGB(255,255,255,255), "center")
end

--[[
		_Streaming Class
]]

class '_Streaming'

function _Streaming:__init()
	self.Save = GetSave("SidasAutoCarry")

	AddTickCallback(function()self:_OnTick() end)
	AddMsgCallback(function(msg, key)self:_OnWndMsg(msg, key) end)

	if self.Save.StreamingMode then
		self:EnableStreaming()
	else
		self:DisableStreaming()
	end
end

function _Streaming:_OnTick()
	if self.StreamingMenu then
		self.StreamingMenu._param[4].text = self.StreamingMenu.Colour == 0 and "Green" or "Red"
	end
	if self.StreamEnabled then
		self:EnableStreaming()
	end
end

function _Streaming:_OnWndMsg(msg, key)
	if msg == KEY_DOWN and key == 118 then
		if not self.StreamEnabled then
			self:EnableStreaming()
		else
			self:DisableStreaming()
		end
	end
end

function _Streaming:EnableStreaming()
	self.Save.StreamingMode = true
	self.StreamEnabled = true
	if not self.ChatTimeout then
		self.ChatTimeout = GetTickCount() + 3000
	elseif GetTickCount() < self.ChatTimeout then
		self:DisableOverlay()
		for i = 0, 15 do
			PrintChat("")
		end
	else
		_G.PrintChat = function() end
	end
end

function _Streaming:DisableStreaming()
	self.Save.StreamingMode = false
	self.StreamEnabled = false
	if self.ChatTimeout then
		EnableOverlay()
		self.ChatTimeout = nil
	end
end

function _Streaming:OnMove()
	-- if not self.MenuCreated then return end
	-- if self.StreamingMenu.MinRand > self.StreamingMenu.MaxRand then
	-- 	print("Reborn: Min cannot be higher than Max in Streaming Menu")
	-- elseif self.StreamingMenu.ShowClick and (not self.NextClick or Helper.Tick > self.NextClick) then
	-- 	if self.StreamingMenu.Colour == 0 then
	-- 		ShowGreenClick(mousePos)
	-- 	else
	-- 		ShowRedClick(mousePos)
	-- 	end
	-- 	self.nextClick = Helper.Tick + math.random(self.StreamingMenu.MinRand, self.StreamingMenu.MaxRand)
	-- end
end

function _Streaming:CreateMenu()
	self.StreamingMenu = scriptConfig("Sida's Auto Carry: Streaming", "sidasacstreaming")
	self.StreamingMenu:addParam("ShowClick", "Show Fake Click Marker", SCRIPT_PARAM_ONOFF, false)
	self.StreamingMenu:addParam("MinRand", "Minimum Time Between Clicks", SCRIPT_PARAM_SLICE, 150, 0, 1000, 0)
	self.StreamingMenu:addParam("MaxRand", "Maximum Time Between Clicks", SCRIPT_PARAM_SLICE, 650, 0, 1000, 0)
	self.StreamingMenu:addParam("Colour", "Green", SCRIPT_PARAM_SLICE, 0, 0, 1, 0)
	self.StreamingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	self.StreamingMenu:addParam("sep", "Toggle Streaming Mode with F7", SCRIPT_PARAM_INFO, "")
	self.MenuCreated = true
end

function _Streaming:DisableOverlay()
    _G.DrawText, 
    _G.PrintFloatText, 
    _G.DrawLine, 
    _G.DrawArrow, 
    _G.DrawCircle, 
    _G.DrawRectangle, 
    _G.DrawLines, 
    _G.DrawLines2 = function() end, 
    function() end, 
    function() end, 
    function() end, 
    function() end, 
    function() end, 
    function() end
end

Streaming = _Streaming()
--[[ 
		_Summoner Class
]]

class '_Summoners'

function _Summoners:__init()
	self.Barrier = (myHero:GetSpellData(SUMMONER_1).name:find("SummonerBarrier") and {Slot = SUMMONER_1} or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerBarrier") and {Slot = SUMMONER_2} or nil))
	self.Ignite = (myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and {Slot = SUMMONER_1, Range = 500} or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and {Slot = SUMMONER_2, Range = 500} or nil))

	AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)
	AddTickCallback(function() self:_OnTick() end)
end

function _Summoners:_OnTick()
	if self.Ignite and SummonersMenu.Ignite then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if GetDistance(Enemy) <= 500 and Enemy.health < getDmg("IGNITE", Enemy, myHero) then
				CastSpell(self.Ignite.Slot, Enemy)
			end
		end
	end
end

function _Summoners:_OnProcessSpell(object,spell)
	if not self.Barrier or not SummonersMenu.Barrier then return end
	if object.team ~= myHero.team and not myHero.dead and not (object.name:find("Minion_") or object.name:find("Odin")) then
		local sbarrierREADY = self.Barrier.Slot ~= nil and myHero:CanUseSpell(self.Barrier.Slot) == READY
		local shealREADY = sheal ~= nil and myHero:CanUseSpell(sheal) == READY
		local lisslot = GetInventorySlotItem(3190)
		local seslot = GetInventorySlotItem(3040)
		local lisREADY = lisslot ~= nil and myHero:CanUseSpell(lisslot) == READY
		local seREADY = seslot ~= nil and myHero:CanUseSpell(seslot) == READY
		local HitFirst = false
		local shieldtarget,SLastDistance,SLastDmgPercent = nil,nil,nil
		local healtarget,HLastDistance,HLastDmgPercent = nil,nil,nil
		local ulttarget,ULastDistance,ULastDmgPercent = nil,nil,nil
		BShield,SShield,Shield,CC = false,false,false,false
		shottype,radius,maxdistance = 0,0,0
		if object.type == "obj_AI_Hero" then
			spelltype, casttype = getSpellType(object, spell.name)
			if casttype == 4 or casttype == 5 then return end
			if spelltype == "BAttack" or spelltype == "CAttack" or spell.name:find("SummonerDot") then
				Shield = true
			elseif spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then
				HitFirst = skillShield[object.charName][spelltype]["HitFirst"]
				BShield = skillShield[object.charName][spelltype]["BShield"]
				SShield = skillShield[object.charName][spelltype]["SShield"]
				Shield = skillShield[object.charName][spelltype]["Shield"]
				CC = skillShield[object.charName][spelltype]["CC"]
				shottype = skillData[object.charName][spelltype]["type"]
				radius = skillData[object.charName][spelltype]["radius"]
				maxdistance = skillData[object.charName][spelltype]["maxdistance"]
			end
		else
			Shield = true
		end
		for i=1, heroManager.iCount do
			local allytarget = heroManager:GetHero(i)
			if allytarget.team == myHero.team and not allytarget.dead and allytarget.health > 0 then
				hitchampion = false
				local allyHitBox = self:getHitBox(allytarget)
				if shottype == 0 then hitchampion = spell.target and spell.target.networkID == allytarget.networkID
				elseif shottype == 1 then hitchampion = checkhitlinepass(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 2 then hitchampion = checkhitlinepoint(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 3 then hitchampion = checkhitaoe(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 4 then hitchampion = checkhitcone(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 5 then hitchampion = checkhitwall(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 6 then hitchampion = checkhitlinepass(object, spell.endPos, radius, maxdistance, allytarget, allyHitBox) or checkhitlinepass(object, Vector(object)*2-spell.endPos, radius, maxdistance, allytarget, allyHitBox)
				elseif shottype == 7 then hitchampion = checkhitcone(spell.endPos, object, radius, maxdistance, allytarget, allyHitBox)
				end
				if hitchampion then
					if sbarrierREADY and allytarget.isMe and Shield then
						local barrierflag, dmgpercent = self:shieldCheck(object,spell,allytarget,"barrier")
						if barrierflag then
							CastSpell(self.Barrier.Slot)
						end
					end
				end
			end
		end
		if shieldtarget ~= nil then
			if typeshield==1 or typeshield==5 then CastSpell(spellslot,shieldtarget)
			elseif typeshield==2 or typeshield==4 then CastSpell(spellslot,shieldtarget.x,shieldtarget.z)
			elseif typeshield==3 or typeshield==6 then CastSpell(spellslot) end
		end
		if healtarget ~= nil then
			if typeheal==1 then CastSpell(healslot,healtarget)
			elseif typeheal==2 or typeheal==3 then CastSpell(healslot) end
		end
		if ulttarget ~= nil then
			if typeult==1 or typeult==3 then CastSpell(ultslot,ulttarget)
			elseif typeult==2 or typeult==4 then CastSpell(ultslot) end		
		end
	end	
end

function _Summoners:shieldCheck(object,spell,target,typeused)
	local configused
	if typeused == "shields" then configused = ASConfig
	elseif typeused == "heals" then configused = AHConfig
	elseif typeused == "ult" then configused = AUConfig
	elseif typeused == "barrier" then configused = ASBConfig 
	elseif typeused == "sheals" then configused = ASHConfig
	elseif typeused == "items" then configused = ASIConfig end
	local shieldflag = false
	local adamage = object:CalcDamage(target,object.totalDamage)
	local InfinityEdge,onhitdmg,onhittdmg,onhitspelldmg,onhitspelltdmg,muramanadmg,skilldamage,skillTypeDmg = 0,0,0,0,0,0,0,0

	if object.type ~= "obj_AI_Hero" then
		if spell.name:find("BasicAttack") then skilldamage = adamage
		elseif spell.name:find("CritAttack") then skilldamage = adamage*2 end
	else
		if GetInventoryHaveItem(3186,object) then onhitdmg = getDmg("KITAES",target,object) end
		if GetInventoryHaveItem(3114,object) then onhitdmg = onhitdmg+getDmg("MALADY",target,object) end
		if GetInventoryHaveItem(3091,object) then onhitdmg = onhitdmg+getDmg("WITSEND",target,object) end
		if GetInventoryHaveItem(3057,object) then onhitdmg = onhitdmg+getDmg("SHEEN",target,object) end
		if GetInventoryHaveItem(3078,object) then onhitdmg = onhitdmg+getDmg("TRINITY",target,object) end
		if GetInventoryHaveItem(3100,object) then onhitdmg = onhitdmg+getDmg("LICHBANE",target,object) end
		if GetInventoryHaveItem(3025,object) then onhitdmg = onhitdmg+getDmg("ICEBORN",target,object) end
		if GetInventoryHaveItem(3087,object) then onhitdmg = onhitdmg+getDmg("STATIKK",target,object) end
		if GetInventoryHaveItem(3153,object) then onhitdmg = onhitdmg+getDmg("RUINEDKING",target,object) end
		if GetInventoryHaveItem(3209,object) then onhittdmg = getDmg("SPIRITLIZARD",target,object) end
		if GetInventoryHaveItem(3184,object) then onhittdmg = onhittdmg+80 end
		if GetInventoryHaveItem(3042,object) then muramanadmg = getDmg("MURAMANA",target,object) end
		if spelltype == "BAttack" then
			skilldamage = (adamage+onhitdmg+muramanadmg)*1.07+onhittdmg
		elseif spelltype == "CAttack" then
			if GetInventoryHaveItem(3031,object) then InfinityEdge = .5 end
			skilldamage = (adamage*(2.1+InfinityEdge)+onhitdmg+muramanadmg)*1.07+onhittdmg --fix Lethality
		elseif spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then
			if GetInventoryHaveItem(3151,object) then onhitspelldmg = getDmg("LIANDRYS",target,object) end
			if GetInventoryHaveItem(3188,object) then onhitspelldmg = getDmg("BLACKFIRE",target,object) end
			if GetInventoryHaveItem(3209,object) then onhitspelltdmg = getDmg("SPIRITLIZARD",target,object) end
			muramanadmg = skillShield[object.charName][spelltype]["Muramana"] and muramanadmg or 0
			if casttype == 1 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,1,spell.level)
			elseif casttype == 2 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,2,spell.level)
			elseif casttype == 3 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,3,spell.level)
			end
			if skillTypeDmg == 2 then
				skilldamage = (skilldamage+adamage+onhitspelldmg+onhitdmg+muramanadmg)*1.07+onhittdmg+onhitspelltdmg
			else
				if skilldamage > 0 then skilldamage = (skilldamage+onhitspelldmg+muramanadmg)*1.07+onhitspelltdmg end
			end
		elseif spell.name:find("SummonerDot") then
			skilldamage = getDmg("IGNITE",target,object)
		end
	end
	local dmgpercent = skilldamage*100/target.health
	local dmgneeded = dmgpercent >= 95
	local hpneeded = 100 >= (target.health-skilldamage)*100/target.maxHealth
	
	if dmgneeded and hpneeded then
		shieldflag = true
	elseif typeused == "shields" and ((CC == 2 and configused.shieldcc) or (CC == 1 and configused.shieldslow)) then
		shieldflag = true
	end
	return shieldflag, dmgpercent
end
function _Summoners:getHitBox(hero)
    local hitboxTable = { ['HeimerTGreen'] = 50.0, ['Darius'] = 80.0, ['ZyraGraspingPlant'] = 20.0, ['HeimerTRed'] = 50.0, ['ZyraThornPlant'] = 20.0, ['Nasus'] = 80.0, ['HeimerTBlue'] = 50.0, ['SightWard'] = 1, ['HeimerTYellow'] = 50.0, ['Kennen'] = 55.0, ['VisionWard'] = 1, ['ShacoBox'] = 10, ['HA_AP_Poro'] = 0, ['TempMovableChar'] = 48.0, ['TeemoMushroom'] = 50.0, ['OlafAxe'] = 50.0, ['OdinCenterRelic'] = 48.0, ['Blue_Minion_Healer'] = 48.0, ['AncientGolem'] = 100.0, ['AnnieTibbers'] = 80.0, ['OdinMinionGraveyardPortal'] = 1.0, ['OriannaBall'] = 48.0, ['LizardElder'] = 65.0, ['YoungLizard'] = 50.0, ['OdinMinionSpawnPortal'] = 1.0, ['MaokaiSproutling'] = 48.0, ['FizzShark'] = 0, ['Sejuani'] = 80.0, ['Sion'] = 80.0, ['OdinQuestIndicator'] = 1.0, ['Zac'] = 80.0, ['Red_Minion_Wizard'] = 48.0, ['DrMundo'] = 80.0, ['Blue_Minion_Wizard'] = 48.0, ['ShyvanaDragon'] = 80.0, ['HA_AP_OrderShrineTurret'] = 88.4, ['Heimerdinger'] = 55.0, ['Rumble'] = 80.0, ['Ziggs'] = 55.0, ['HA_AP_OrderTurret3'] = 88.4, ['HA_AP_OrderTurret2'] = 88.4, ['TT_Relic'] = 0, ['Veigar'] = 55.0, ['HA_AP_HealthRelic'] = 0, ['Teemo'] = 55.0, ['Amumu'] = 55.0, ['HA_AP_ChaosTurretShrine'] = 88.4, ['HA_AP_ChaosTurret'] = 88.4, ['HA_AP_ChaosTurretRubble'] = 88.4, ['Poppy'] = 55.0, ['Tristana'] = 55.0, ['HA_AP_PoroSpawner'] = 50.0, ['TT_NGolem'] = 80.0, ['HA_AP_ChaosTurretTutorial'] = 88.4, ['Volibear'] = 80.0, ['HA_AP_OrderTurretTutorial'] = 88.4, ['TT_NGolem2'] = 80.0, ['HA_AP_ChaosTurret3'] = 88.4, ['HA_AP_ChaosTurret2'] = 88.4, ['Shyvana'] = 50.0, ['HA_AP_OrderTurret'] = 88.4, ['Nautilus'] = 80.0, ['ARAMOrderTurretNexus'] = 88.4, ['TT_ChaosTurret2'] = 88.4, ['TT_ChaosTurret3'] = 88.4, ['TT_ChaosTurret1'] = 88.4, ['ChaosTurretGiant'] = 88.4, ['ARAMOrderTurretFront'] = 88.4, ['ChaosTurretWorm'] = 88.4, ['OdinChaosTurretShrine'] = 88.4, ['ChaosTurretNormal'] = 88.4, ['OrderTurretNormal2'] = 88.4, ['OdinOrderTurretShrine'] = 88.4, ['OrderTurretDragon'] = 88.4, ['OrderTurretNormal'] = 88.4, ['ARAMChaosTurretFront'] = 88.4, ['ARAMOrderTurretInhib'] = 88.4, ['ChaosTurretWorm2'] = 88.4, ['TT_OrderTurret1'] = 88.4, ['TT_OrderTurret2'] = 88.4, ['ARAMChaosTurretInhib'] = 88.4, ['TT_OrderTurret3'] = 88.4, ['ARAMChaosTurretNexus'] = 88.4, ['OrderTurretAngel'] = 88.4, ['Mordekaiser'] = 80.0, ['TT_Buffplat_R'] = 0, ['Lizard'] = 50.0, ['GolemOdin'] = 80.0, ['Renekton'] = 80.0, ['Maokai'] = 80.0, ['LuluLadybug'] = 50.0, ['Alistar'] = 80.0, ['Urgot'] = 80.0, ['LuluCupcake'] = 50.0, ['Gragas'] = 80.0, ['Skarner'] = 80.0, ['Yorick'] = 80.0, ['MalzaharVoidling'] = 10.0, ['LuluPig'] = 50.0, ['Blitzcrank'] = 80.0, ['Chogath'] = 80.0, ['Vi'] = 50, ['FizzBait'] = 0, ['Malphite'] = 80.0, ['EliseSpiderling'] = 1.0, ['Dragon'] = 100.0, ['LuluSquill'] = 50.0, ['Worm'] = 100.0, ['redDragon'] = 100.0, ['LuluKitty'] = 50.0, ['Galio'] = 80.0, ['Annie'] = 55.0, ['EliseSpider'] = 50.0, ['SyndraSphere'] = 48.0, ['LuluDragon'] = 50.0, ['Hecarim'] = 80.0, ['TT_Spiderboss'] = 200.0, ['Thresh'] = 55.0, ['ARAMChaosTurretShrine'] = 88.4, ['ARAMOrderTurretShrine'] = 88.4, ['Blue_Minion_MechMelee'] = 65.0, ['TT_NWolf'] = 65.0, ['Tutorial_Red_Minion_Wizard'] = 48.0, ['YorickRavenousGhoul'] = 1.0, ['SmallGolem'] = 80.0, ['OdinRedSuperminion'] = 55.0, ['Wraith'] = 50.0, ['Red_Minion_MechCannon'] = 65.0, ['Red_Minion_Melee'] = 48.0, ['OdinBlueSuperminion'] = 55.0, ['TT_NWolf2'] = 50.0, ['Tutorial_Red_Minion_Basic'] = 48.0, ['YorickSpectralGhoul'] = 1.0, ['Wolf'] = 50.0, ['Blue_Minion_MechCannon'] = 65.0, ['Golem'] = 80.0, ['Blue_Minion_Basic'] = 48.0, ['Blue_Minion_Melee'] = 48.0, ['Odin_Blue_Minion_caster'] = 48.0, ['TT_NWraith2'] = 50.0, ['Tutorial_Blue_Minion_Wizard'] = 48.0, ['GiantWolf'] = 65.0, ['Odin_Red_Minion_Caster'] = 48.0, ['Red_Minion_MechMelee'] = 65.0, ['LesserWraith'] = 50.0, ['Red_Minion_Basic'] = 48.0, ['Tutorial_Blue_Minion_Basic'] = 48.0, ['GhostWard'] = 1, ['TT_NWraith'] = 50.0, ['Red_Minion_MechRange'] = 65.0, ['YorickDecayedGhoul'] = 1.0, ['TT_Buffplat_L'] = 0, ['TT_ChaosTurret4'] = 88.4, ['TT_Buffplat_Chain'] = 0, ['TT_OrderTurret4'] = 88.4, ['OrderTurretShrine'] = 88.4, ['ChaosTurretShrine'] = 88.4, ['WriggleLantern'] = 1, ['ChaosTurretTutorial'] = 88.4, ['TwistedLizardElder'] = 65.0, ['RabidWolf'] = 65.0, ['OrderTurretTutorial'] = 88.4, ['OdinShieldRelic'] = 0, ['TwistedGolem'] = 80.0, ['TwistedSmallWolf'] = 50.0, ['TwistedGiantWolf'] = 65.0, ['TwistedTinyWraith'] = 50.0, ['TwistedBlueWraith'] = 50.0, ['TwistedYoungLizard'] = 50.0, ['Summoner_Rider_Order'] = 65.0, ['Summoner_Rider_Chaos'] = 65.0, ['Ghast'] = 60.0, ['blueDragon'] = 100.0, }
    return (hitboxTable[hero.charName] ~= nil and hitboxTable[hero.charName] ~= 0) and hitboxTable[hero.charName] or 65
end


--[[
		_MenuManager Class
]]

class '_MenuManager' MenuManager = nil

function _MenuManager:__init()
	self.AutoCarry = false
	self.MixedMode = false
	self.LastHit = false
	self.LaneClear = false
	self.SkillMenu = {}

	AddTickCallback(function() self:OnTick() end)
	AddMsgCallback(function(msg, key) self:OnWndMsg(msg, key) end)
	AddUnloadCallback(function() self:_OnUnload() end)
	AddBugsplatCallback(function() self:_OnBugsplat() end)
	AddExitCallback(function() self:_OnExit() end)
	MenuManager = self


	--[[ Setup Menu]]
	ModesMenu = scriptConfig("Sida's Auto Carry: Setup", "sidasacsetup")
	ModesMenu:addSubMenu("Auto Carry Mode", "sidasacautocarrysub")
	ModesMenu:addSubMenu("Last Hit Mode", "sidasaclasthitsub")
	ModesMenu:addSubMenu("Mixed Mode", "sidasacmixedmodesub")
	ModesMenu:addSubMenu("Lane Clear Mode", "sidasaclaneclearsub")
	AutoCarryMenu = ModesMenu.sidasacautocarrysub
	LastHitMenu = ModesMenu.sidasaclasthitsub
	MixedModeMenu = ModesMenu.sidasacmixedmodesub
	LaneClearMenu = ModesMenu.sidasaclaneclearsub

	SkillsMenu = scriptConfig("Sida's Auto Carry: Skills", "sidasacskils")
	for _, Skill in pairs(Skills.SkillsList) do
		SkillsMenu:addSubMenu("Skill "..Skill.DisplayName, Skill.RawName.."sub")
		self.SkillMenu[Skill.Key] = SkillsMenu[Skill.RawName.."sub"]
	end

	ConfigurationMenu = scriptConfig("Sida's Auto Carry: Configuration", "sidasacconfigsub")
	ConfigurationMenu:addSubMenu("Drawing", "sidasacdrawingsub")
	ConfigurationMenu:addSubMenu("Summoner Spells", "sidasacsummonersub")
	ConfigurationMenu:addSubMenu("Extras", "sidasacextrassub")
	ConfigurationMenu:addSubMenu("Melee Config", "sidasacmeleesub")
	ConfigurationMenu:addSubMenu("Farming", "sidasacfarmingsub")
	ConfigurationMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	ConfigurationMenu:addParam("Focused", "Focus Selected Target", SCRIPT_PARAM_ONOFF, false)
	ConfigurationMenu:addParam("SupportMode", "Support Mode", SCRIPT_PARAM_ONOFF, false)
	DrawingMenu = ConfigurationMenu.sidasacdrawingsub
	SummonersMenu = ConfigurationMenu.sidasacsummonersub
	ExtrasMenu = ConfigurationMenu.sidasacextrassub
	MeleeMenu = ConfigurationMenu.sidasacmeleesub
	FarmMenu = ConfigurationMenu.sidasacfarmingsub


	--[[ Auto Carry Menu ]]

	--AutoCarryMenu = scriptConfig("Sida's Auto Carry: Auto Carry", "sidasacautocarry")
	AutoCarryMenu:addParam("title", "              Sida's Auto Carry: Reborn", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
	AutoCarryMenu:addParam("Active", "Auto Carry", AutoCarryMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, 219)
	AutoCarryMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("sep", "-- Skills --", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:permaShow("title")
	AutoCarryMenu:permaShow("Active")
	AutoCarryMenu:addTS(Crosshair.Attack_Crosshair)
	Keys:RegisterMenuKey(AutoCarryMenu, "Active", MODE_AUTOCARRY)

	if #Skills.SkillsList > 0 then
		for _, Skill in pairs(Skills.SkillsList) do
			AutoCarryMenu:addParam(Skill.RawName.."AutoCarry", "Use "..Skill.DisplayName, SCRIPT_PARAM_ONOFF, self:LoadSkill(AutoCarryMenu, Skill.RawName, "AutoCarry"))
		end
	else
		AutoCarryMenu:addParam("sep", "No supported skills for "..myHero.charName, SCRIPT_PARAM_INFO, "")
	end

	AutoCarryMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("sep", "-- Items --", SCRIPT_PARAM_INFO, "")
	for _, Item in pairs(Items.ItemList) do
		AutoCarryMenu:addParam(Item.RawName.."AutoCarry", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
	end
	AutoCarryMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
	AutoCarryMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
	AutoCarryMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
	AutoCarryMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)


	--[[ Last Hit Menu ]]

	--LastHitMenu = scriptConfig("Sida's Auto Carry: Last Hit", "sidasaclasthit")
	LastHitMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
	LastHitMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
	LastHitMenu:addParam("Active", "Last Hit", LastHitMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
	LastHitMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	LastHitMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
	LastHitMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
	LastHitMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)
	LastHitMenu:permaShow("Active")
	Keys:RegisterMenuKey(LastHitMenu, "Active", MODE_LASTHIT)


	--[[ Mixed Mode Menu ]]

	--MixedModeMenu = scriptConfig("Sida's Auto Carry: Mixed Mode", "sidasacmixedmode")
	MixedModeMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
	MixedModeMenu:addParam("Active", "Mixed Mode", MixedModeMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("D"))
	MixedModeMenu:addParam("MinionPriority", "Prioritise Last Hit Over Harass", SCRIPT_PARAM_ONOFF, true)
	MixedModeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:addParam("sep", "-- Skills (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:permaShow("Active")
	Keys:RegisterMenuKey(MixedModeMenu, "Active", MODE_MIXEDMODE)

	if #Skills.SkillsList > 0 then
		for _, Skill in pairs(Skills.SkillsList) do
			MixedModeMenu:addParam(Skill.RawName.."MixedMode", "Use "..Skill.DisplayName, SCRIPT_PARAM_ONOFF, self:LoadSkill(MixedModeMenu, Skill.RawName, "MixedMode"))
		end
	else
		MixedModeMenu:addParam("sep", "No supported skills for "..myHero.charName, SCRIPT_PARAM_INFO, "")
	end

	MixedModeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:addParam("sep", "-- Items (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
	for _, Item in pairs(Items.ItemList) do
		MixedModeMenu:addParam(Item.RawName.."MixedMode", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
	end
	MixedModeMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
	MixedModeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
	MixedModeMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
	MixedModeMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)
	

	--[[ Lane Clear Menu ]]

	--LaneClearMenu = scriptConfig("Sida's Auto Carry: Lane Clear", "sidasaclaneclear")
	LaneClearMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
	LaneClearMenu:addParam("Active", "Lane Clear", LaneClearMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	LaneClearMenu:addParam("MinionPriority", "Prioritise Last Hit Over Harass", SCRIPT_PARAM_ONOFF, true)
	LaneClearMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:addParam("sep", "-- Skills (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:permaShow("Active")
	Keys:RegisterMenuKey(LaneClearMenu, "Active", MODE_LANECLEAR)

	if #Skills.SkillsList > 0 then
		for _, Skill in pairs(Skills.SkillsList) do
			LaneClearMenu:addParam(Skill.RawName.."LaneClear", "Use "..Skill.DisplayName, SCRIPT_PARAM_ONOFF, self:LoadSkill(LaneClearMenu, Skill.RawName, "LaneClear"))	
		end
	else
		LaneClearMenu:addParam("sep", "No supported skills for "..myHero.charName, SCRIPT_PARAM_INFO, "")
	end

	LaneClearMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:addParam("sep", "-- Items (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
	for _, Item in pairs(Items.ItemList) do
		LaneClearMenu:addParam(Item.RawName.."LaneClear", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
	end
	LaneClearMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
	LaneClearMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
	LaneClearMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
	LaneClearMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)

	--[[ Skills Menus ]]
	for _, Skill in pairs(Skills.SkillsList) do
		--self.SkillMenu[Skill.Key] = scriptConfig("Sida's Auto Carry: Skill "..Skill.DisplayName, Skill.RawName)
		self.SkillMenu[Skill.Key]:addParam("sep", "-- Against Champions --", SCRIPT_PARAM_INFO, "")
		self.SkillMenu[Skill.Key]:addParam("CastPrio", "Cast Priority (0 Is Cast First)", SCRIPT_PARAM_SLICE, 0, 0, 3, 0)
		self.SkillMenu[Skill.Key]:addParam("MinMana", "Mana Must Be Above %", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
		self.SkillMenu[Skill.Key]:addParam("MinHealth", "Enemy Health Must Be Above %", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
		self.SkillMenu[Skill.Key]:addParam("MaxHealth", "Enemy Health Must Be Below %", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
		self.SkillMenu[Skill.Key]:addParam("RequiresTarget", "Target Must Be In Attack Range", SCRIPT_PARAM_ONOFF, Skill.ReqAttackTarget)
		self.SkillMenu[Skill.Key]:addParam("ManualCast", "Manual Cast", SCRIPT_PARAM_ONKEYDOWN, false, 119)
		-- self.SkillMenu[Skill.Key]:addParam("sep", "", SCRIPT_PARAM_INFO, "")
		-- self.SkillMenu[Skill.Key]:addParam("sep", "-- Against Minions --", SCRIPT_PARAM_INFO, "")
		-- self.SkillMenu[Skill.Key]:addParam("LastHit", "Last Hit With This Skill", SCRIPT_PARAM_ONOFF, false)
		-- self.SkillMenu[Skill.Key]:addParam("MinManaMinions", "Minimum Mana %", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
	end
	
	--[[Configuration Menu ]]
	--ConfigMenu:addParam("multiOptions", "Multi-opts in sub-menu", SCRIPT_PARAM_LIST, 1, { "Default", "Its", "Disco", "Baby" })

	--[[ Extras Menu ]]
	--SummonersMenu = scriptConfig("Sida's Auto Carry: Summoner Spells", "sidasacextras")
	SummonersMenu:addParam("Barrier", "Auto Barrier", SCRIPT_PARAM_ONOFF, true)
	SummonersMenu:addParam("Ignite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)

	-- ConfigMenu:addParam("Boost", "Turbo Boost: Attack", SCRIPT_PARAM_SLICE, 0, 0, 1000, 0)
	-- ConfigMenu:addParam("AttackBoost", "Turbo Boost: Movement", SCRIPT_PARAM_SLICE, 0, 0, 1000, 0)

	--[[ Drawing Menu ]]
	--DrawingMenu = scriptConfig("Sida's Auto Carry: Drawing", "sidasacdrawing")
	DrawingMenu:addParam("RangeCircle", "Champion Range Circle", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("RangeCircleColour", "Colour", SCRIPT_PARAM_COLOR, {255, 0, 189, 22})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("CastRangeCircle", "Spell Range Circle", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("CastRangeCircleColour", "Colour", SCRIPT_PARAM_COLOR, {183, 0, 26, 173})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("TargetCircle", "Circle Around Target", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("TargetCircleColour", "Colour", SCRIPT_PARAM_COLOR, {255, 0, 112, 95})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("MinionCircle", "Minion Marker With Prediction", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("MinionCircleColour", "Colour", SCRIPT_PARAM_COLOR, {183, 0, 26, 173})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("ArrowToTarget", "Line To Target", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("ArrowToTargetColour", "Colour", SCRIPT_PARAM_COLOR, {100, 255, 100, 100})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("StandZone", "Stand & Shoot Range", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("StandZoneColour", "Stand Zone Colour", SCRIPT_PARAM_COLOR, {183, 0, 26, 173})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("MeleeSticky", "Stick To Target Range (Melee Only)", SCRIPT_PARAM_ONOFF, true)
	DrawingMenu:addParam("MeleeStickyColour", "Stick To Target Colour", SCRIPT_PARAM_COLOR, {183, 0, 26, 173})
	DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	DrawingMenu:addParam("LowFPS", "Use Low FPS Circles", SCRIPT_PARAM_ONOFF, false)

	--[[ Extras Menu ]]
	--ExtrasMenu = scriptConfig("Sida's Auto Carry: Extras", "sidasacextras")
	ExtrasMenu:addParam("sep", "-- May Cause FPS Drops--", SCRIPT_PARAM_INFO, "")
	ExtrasMenu:addParam("ShowSwingTimer", "Show Swing Timer", SCRIPT_PARAM_ONOFF, true)
	ExtrasMenu:addParam("StandZone"..myHero.charName, "Stand Still And Shoot Range", SCRIPT_PARAM_SLICE, self:LoadStandRange(), 0, MyHero.TrueRange, 0)

	FarmMenu:addParam("Butcher", "Butcher Mastery", SCRIPT_PARAM_ONOFF, false)
	FarmMenu:addParam("ArcaneBlade", "Arcane Blade Mastery", SCRIPT_PARAM_ONOFF, false)
	FarmMenu:addParam("Havoc", "Havoc Mastery", SCRIPT_PARAM_ONOFF, true)
	FarmMenu:addParam("DoubleEdgedSword", "Double-Edged Sword Mastery", SCRIPT_PARAM_ONOFF, true)
	FarmMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	FarmMenu:addParam("Freeze", "Lane Freeze Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 112)
	FarmMenu:addParam("FarmOffset", "Last Hit Adjustment", SCRIPT_PARAM_SLICE, 0, -50, 50, 0)

	MeleeMenu:addParam("MeleeStickyRange", "Stick To Target Range (Melee Only)", SCRIPT_PARAM_SLICE, 0, 0, 300, 0)

	--[[ Overdrive Menu ]]
	-- OverdriveMenu = scriptConfig("Sida's Auto Carry: Overdrive", "sidasacoverdrive")
	-- OverdriveMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	-- OverdriveMenu:addParam("Enabled", "Enable Overdrive", SCRIPT_PARAM_ONOFF, false)
	-- OverdriveMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
	-- OverdriveMenu:addParam("AnimationOverdrive", "Amount", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
	-- OverdriveMenu:addParam("AnimationOverdriveHelp", "Click for help >>", SCRIPT_PARAM_ONOFF, false)

	self:DisableAllModes()
	-- self.Boost = ConfigMenu.Boost
	-- self.AttackBoost = ConfigMenu.AttackBoost
end

function _MenuManager:OnTick()
	-- if self.Boost ~= ConfigMenu.Boost then
	-- 	print("Hyper Charge: Attack - When you start cancelling attacks, you've gone too high!")
	-- 	self.Boost = ConfigMenu.Boost
	-- end

	-- if self.AttackBoost ~= ConfigMenu.AttackBoost then
	-- 	print("Hyper Charge: Movement - When you stand still before each attack, you've gone too high!")
	-- 	self.AttackBoost = ConfigMenu.AttackBoost
	-- end

	if AutoCarryMenu.Active ~= self.AutoCarry and not self.AutoCarry then
		self:SetToggles(true, false, false, false)
	elseif MixedModeMenu.Active ~= self.MixedMode and not self.MixedMode then
		self:SetToggles(false, true, false, false)
	elseif LastHitMenu.Active ~= self.LastHit and not self.LastHit then
		self:SetToggles(false, false, true, false)
	elseif LaneClearMenu.Active ~= self.LaneClear and not self.LaneClear then
		self:SetToggles(false, false, false, true)
	end

	if FarmMenu.Freeze then
		MixedModeMenu._param[3].text = "Mixed Mode         (Lane Freeze)"
		LastHitMenu._param[3].text = "Last Hit                 (Lane Freeze)"
	else
		MixedModeMenu._param[3].text = "Mixed Mode"
		LastHitMenu._param[3].text = "Last Hit"
	end
end

function _MenuManager:OnWndMsg(msg, key)
	if msg == WM_RBUTTONDOWN then
		local _Menu = self:GetActiveMenu()
		if _Menu and _Menu.Toggle then
			self:DisableAllModes()
		end
	end
end

function _MenuManager:_OnUnload()
	self:SaveSkills()
	self:SaveStandRange()
end

function _MenuManager:_OnBugsplat()
	self:SaveSkills()
	self:SaveStandRange()
end

function _MenuManager:_OnExit()
	self:SaveSkills()
	self:SaveStandRange()
end

function _MenuManager:SetToggles(ac, mm, lh, lc)
	AutoCarryMenu.Active, self.AutoCarry = ac, ac
	MixedModeMenu.Active, self.MixedMode = mm, mm
	LastHitMenu.Active, self.LastHit = lh, lh
	LaneClearMenu.Active, self.LaneClear = lc, lc
end

function _MenuManager:DisableAllModes()
	AutoCarryMenu.Active = false
	MixedModeMenu.Active = false
	LastHitMenu.Active = false
	LaneClearMenu.Active = false
end

function _MenuManager:GetActiveMenu()
	if AutoCarryMenu.Active then
		return AutoCarryMenu
	elseif MixedModeMenu.Active then
		return MixedModeMenu
	elseif LastHitMenu.Active then
		return LastHitMenu
	elseif LaneClearMenu.Active then
		return LaneClearMenu
	end
end

function _MenuManager:SaveSkills()
	local _skills = {}

	for _, Skill in pairs(Skills.SkillsList) do
		if AutoCarryMenu[Skill.RawName.."AutoCarry"] ~= nil then
			table.insert(_skills , {mode = "AutoCarry", name=Skill.RawName, value=AutoCarryMenu[Skill.RawName.."AutoCarry"]})
		end
		if MixedModeMenu[Skill.RawName.."MixedMode"] ~= nil then
			table.insert(_skills , {mode = "MixedMode", name=Skill.RawName, value=MixedModeMenu[Skill.RawName.."MixedMode"]})
		end
		if LaneClearMenu[Skill.RawName.."LaneClear"] ~= nil then
			table.insert(_skills , {mode = "LaneClear", name=Skill.RawName, value=LaneClearMenu[Skill.RawName.."LaneClear"]})
		end
	end

	local save = GetSave("SidasAutoCarry")
	save[myHero.charName] = _skills
	save:Save()
end

function _MenuManager:LoadSkill(Menu, Name, Mode)
	local save = GetSave("SidasAutoCarry")[myHero.charName]
	if not save then return false end
	for _, Skill in pairs(save) do
		if Skill.name == Name and Skill.mode == Mode then
			return Skill.value
		end
	end
	return false
end

function _MenuManager:SaveStandRange()
	local save = GetSave("SidasAutoCarry")

	save.HoldZone = ExtrasMenu["StandZone"..myHero.charName]
	save:Save()
end

function _MenuManager:LoadStandRange()
	local save = GetSave("SidasAutoCarry")

	if save.HoldZone then
		return save.HoldZone
	else
		return 0
	end
end

--[[
		_Plugins Class
]]

class '_Plugins' Plugins = nil

function _Plugins:__init()
	self.Plugins = {}
	self.RegisteredBonusLastHitDamage = {}
	self.RegisteredPreAttack = {}
	Plugins = self
end

function _Plugins:RegisterPlugin(plugin, name)
	if plugin.OnTick then
		AddTickCallback(function() plugin:OnTick() end)
	end
	if plugin.OnDraw then
		AddDrawCallback(function() plugin:OnDraw() end)
	end
	if plugin.OnCreateObj then
		AddCreateObjCallback(function(obj) plugin:OnCreateObj(obj) end)
	end
	if plugin.OnDeleteObj then
		AddDeleteObjCallback(function(obj) plugin:OnDeleteObj(obj) end)
	end
	if plugin.OnLoad then
		plugin:OnLoad()
	end
	if plugin.OnUnload then
		AddUnloadCallback(function() plugin.OnUnload() end)
	end
	if plugin.OnWndMsg then
		AddMsgCallback(function(msg, key) plugin:OnWndMsg(msg, key) end)
	end
	if plugin.OnProcessSpell then
		AddProcessSpellCallback(function(unit, spell) plugin:OnProcessSpell(unit, spell) end)
	end
	if plugin.OnSendChat then
		AddChatCallback(function(text) plugin:OnSendChat(text) end)
	end
	if plugin.OnBugsplat then
		AddBugsplatCallback(function() plugin:OnBugsplat() end) 
	end
	if plugin.OnAnimation then
		AddAnimationCallback(function(unit, anim) plugin:OnAnimation(unit, anim) end)
	end
	if plugin.OnSendPacket then
		AddSendPacketCallback(function(packet) plugin:OnSendPacket(packet) end)
	end
	if plugin.OnRecvPacket then
		AddRecvPacketCallback(function(packet) plugin:OnRecvPacket(packet) end)
	end
	if name then
		self.Plugins[name] = scriptConfig("Sida's Auto Carry Plugin: "..name, "sidasacautocarryplugin"..name)
		return self.Plugins[name]
	end
end

function _Plugins:RegisterBonusLastHitDamage(func)
	table.insert(self.RegisteredBonusLastHitDamage, func)
end

function _Plugins:RegisterPreAttack(func)
	table.insert(self.RegisteredPreAttack, func)
end

function _Plugins:RegisterOnAttacked(func)
	RegisterOnAttacked(func)
end

function _Plugins:GetProdiction(Key, Range, Speed, Delay, Width, Source, Callback)
	return ProdictManager.GetInstance():AddProdictionObject(Key, Range, Speed * 1000, Delay / 1000, Width, myHero, Callback)
end

--[[
		Drawing Class
]]

class '_Drawing'

function _Drawing:__init()
	AddDrawCallback(function() self:_OnDraw() end)
end

function _Drawing:_OnDraw()
	if DrawingMenu.RangeCircle then
		Helper:DrawCircleObject(myHero, MyHero.TrueRange, Helper:ArgbFromMenu(DrawingMenu.RangeCircleColour))
	end

	if DrawingMenu.CastRangeCircle and Crosshair.Skills_Crosshair.range ~= MyHero.TrueRange then
		Helper:DrawCircleObject(myHero, Crosshair.Skills_Crosshair.range, Helper:ArgbFromMenu(DrawingMenu.CastRangeCircleColour))
	end

	if DrawingMenu.TargetCircle and Crosshair.Target then
		Helper:DrawCircleObject(Crosshair.Target, 100, Helper:ArgbFromMenu(DrawingMenu.TargetCircleColour), 6)
	end

	if DrawingMenu.MinionCircle and Minions.KillableMinion then
		Helper:DrawCircleObject(Minions.KillableMinion, 80, Helper:ArgbFromMenu(DrawingMenu.MinionCircleColour), 6)
	end

	if DrawingMenu.ArrowToTarget and Crosshair.Target then
		self:DrawArrows(myHero, Crosshair.Target)
	end

	if DrawingMenu.StandZone and ExtrasMenu["StandZone"..myHero.charName] > 0 then
		Helper:DrawCircleObject(myHero, ExtrasMenu["StandZone"..myHero.charName], Helper:ArgbFromMenu(DrawingMenu.StandZoneColour))
	end

	if DrawingMenu.MeleeSticky and MeleeMenu.MeleeStickyRange > 0 and myHero.range < 300 then
		Helper:DrawCircleObject(myHero, MeleeMenu.MeleeStickyRange, Helper:ArgbFromMenu(DrawingMenu.MeleeStickyColour))
	end
end

function _Drawing:DrawArrows(Start, End)
	if Start and End then
        DrawArrows(D3DXVECTOR3(Start.x, Start.y, Start.z), D3DXVECTOR3(End.x, End.y, End.z), 60, Helper:HexFromMenu(DrawingMenu.ArrowToTargetColour), 100)
    end
end

--[[ 
		_AutoUpdate Class 
]]

class '_AutoUpdate' AutoUpdate = nil

function _AutoUpdate:__init()
	AutoUpdate = self
	self.CurrentVersion = 40
	self.Path = BOL_PATH.."Scripts\\Common\\RebornBetaVersion.beta"

	AddLoadCallback(function() self:_OnLoad() end)
end

function _AutoUpdate:_OnLoad()
	WriteFile("Revision="..self.CurrentVersion..";", self.Path)
end

_AutoUpdate()

--[[
		_Data Class
]]


class '_Data' Data = nil

 function _Data:__init()
	self.ResetSpells = {}
	self.SpellAttacks = {}
	self.NoneAttacks = {}
	self.ChampionData = {}
	self.MinionData = {}
	self.JungleData = {}
	self.ItemData = {}
	self.Skills = {}
	self.EnemyHitBoxes = {}
	self.ImmuneEnemies = {}
	Data = self

	self:__GenerateNoneAttacks()
	self:__GenerateSpellAttacks()
	self:__GenerateResetSpells()
	self:_GenerateMinionData()
	self:_GenerateJungleData()
	self:_GenerateItemData()
	self:__GenerateChampionData()
	self:__GenerateSkillData()

	AdvancedCallback:bind("OnGainBuff", function(Unit, Buff) self:OnGainBuff(Unit, Buff) end)
	AdvancedCallback:bind("OnLoseBuff", function(Unit, Buff) self:OnLoseBuff(Unit, Buff) end)

	if GetGameTimer() < self:GetHitBoxLastSavedTime() then
		self:GenerateHitBoxData()
	else
		self:LoadHitBoxData()
	end
 end

 function _Data:OnGainBuff(Unit, Buff)
 	if Unit.team ~= myHero.team and (Buff.name == "UndyingRage" or Buff.name == "JudicatorIntervention") then
 		self.ImmuneEnemies[Unit.charName] = true
 	end
 end

 function _Data:OnLoseBuff(Unit, Buff)
 	if Unit.team ~= myHero.team and (Buff.name == "UndyingRage" or Buff.name == "JudicatorIntervention") then
 		self.ImmuneEnemies[Unit.charName] = nil
 	end
 end

function _Data:__GenerateResetSpells()
	self:AddResetSpell("Powerfist")
	self:AddResetSpell("DariusNoxianTacticsONH")
	self:AddResetSpell("Takedown")
	self:AddResetSpell("Ricochet")
	self:AddResetSpell("BlindingDart")
	self:AddResetSpell("VayneTumble")
	self:AddResetSpell("JaxEmpowerTwo")
	self:AddResetSpell("MordekaiserMaceOfSpades")
	self:AddResetSpell("SiphoningStrikeNew")
	self:AddResetSpell("RengarQ")
	self:AddResetSpell("MonkeyKingDoubleAttack")
	self:AddResetSpell("YorickSpectral")
	self:AddResetSpell("ViE")
	self:AddResetSpell("GarenSlash3")
	self:AddResetSpell("HecarimRamp")
	self:AddResetSpell("XenZhaoComboTarget")
	self:AddResetSpell("LeonaShieldOfDaybreak")
	self:AddResetSpell("ShyvanaDoubleAttack")
	self:AddResetSpell("shyvanadoubleattackdragon")
	self:AddResetSpell("TalonNoxianDiplomacy")
	self:AddResetSpell("TrundleTrollSmash")
	self:AddResetSpell("VolibearQ")
	self:AddResetSpell("PoppyDevastatingBlow")
	self:AddResetSpell("SivirW")
	self:AddResetSpell("Ricochet")
end

function _Data:__GenerateSpellAttacks()
	self:AddSpellAttack("frostarrow")
	self:AddSpellAttack("CaitlynHeadshotMissile")
	self:AddSpellAttack("QuinnWEnhanced")
	self:AddSpellAttack("TrundleQ")
	self:AddSpellAttack("XenZhaoThrust")
	self:AddSpellAttack("XenZhaoThrust2")
	self:AddSpellAttack("XenZhaoThrust3")
	self:AddSpellAttack("GarenSlash2")
	self:AddSpellAttack("RenektonExecute")
	self:AddSpellAttack("RenektonSuperExecute")
	self:AddSpellAttack("KennenMegaProc")
	self:AddSpellAttack("redcardpreattack")
	self:AddSpellAttack("bluecardpreattack")
	self:AddSpellAttack("goldcardpreattack")
	self:AddSpellAttack("MasterYiDoubleStrike")
end

function _Data:__GenerateNoneAttacks()
	self:AddNoneAttack("shyvanadoubleattackdragon")
	self:AddNoneAttack("ShyvanaDoubleAttack")
	self:AddNoneAttack("MonkeyKingDoubleAttack")
end

function _Data:_GenerateMinionData()
	self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Basic", 400, 0)
	self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Caster", 484, 0.68)
	self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Wizard", 484, 0.68)
	self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_MechCannon", 365, 1.18)
	self:AddMinionData("obj_AI_Turret", 150, 1.14)
end

function _Data:_GenerateJungleData()
	self:AddJungleMonster("Worm12.1.1", 		1)		-- Baron
	self:AddJungleMonster("Dragon6.1.1", 		1)		-- Dragon
	self:AddJungleMonster("AncientGolem1.1.1", 	1)		-- Blue Buff
	self:AddJungleMonster("AncientGolem7.1.1", 	1)		-- Blue Buff
	self:AddJungleMonster("YoungLizard1.1.2", 	2)		-- Blue Buff Add
	self:AddJungleMonster("YoungLizard7.1.3", 	2)		-- Blue Buff Add
	self:AddJungleMonster("YoungLizard1.1.3", 	2)		-- Blue Buff Add
	self:AddJungleMonster("YoungLizard7.1.2", 	2)		-- Blue Buff Add
	self:AddJungleMonster("LizardElder4.1.1", 	1)		-- Red Buff
	self:AddJungleMonster("LizardElder10.1.1", 	1)		-- Red Buff
	self:AddJungleMonster("YoungLizard4.1.2", 	2)		-- Red Buff Add
	self:AddJungleMonster("YoungLizard4.1.3", 	2)		-- Red Buff Add
	self:AddJungleMonster("YoungLizard10.1.2", 	2)		-- Red Buff Add
	self:AddJungleMonster("YoungLizard10.1.3", 	2)		-- Red Buff Add
	self:AddJungleMonster("GiantWolf2.1.3", 	1)		-- Big Wolf
	self:AddJungleMonster("GiantWolf2.1.1", 	1)		-- Big Wolf
	self:AddJungleMonster("GiantWolf8.1.3", 	1)		-- Big Wolf
	self:AddJungleMonster("wolf2.1.1", 			2)		-- Small Wolf
	self:AddJungleMonster("wolf2.1.2", 			2)		-- Small Wolf
	self:AddJungleMonster("wolf8.1.1", 			2)		-- Small Wolf
	self:AddJungleMonster("wolf8.1.2", 			2)		-- Small Wolf
	self:AddJungleMonster("Wolf2.1.3", 			2)		-- Small Wolf
	self:AddJungleMonster("Wolf2.1.2", 			2)		-- Small Wolf
	self:AddJungleMonster("Wraith3.1.3", 		1)		-- Big Wraith
	self:AddJungleMonster("Wraith9.1.3", 		1)		-- Big Wraith
	self:AddJungleMonster("LesserWraith3.1.1", 	2)		-- Small Wraith
	self:AddJungleMonster("LesserWraith3.1.2", 	2)		-- Small Wraith
	self:AddJungleMonster("LesserWraith3.1.4", 	2)		-- Small Wraith
	self:AddJungleMonster("LesserWraith9.1.1", 	2)		-- Small Wraith
	self:AddJungleMonster("LesserWraith9.1.2", 	2)		-- Small Wraith
	self:AddJungleMonster("LesserWraith9.1.4", 	2)		-- Small Wraith
	self:AddJungleMonster("Golem5.1.2", 		1)		-- Big Golem
	self:AddJungleMonster("Golem11.1.2", 		1)		-- Big Golem
	self:AddJungleMonster("SmallGolem5.1.1", 	2)		-- Small Golem
	self:AddJungleMonster("SmallGolem11.1.1", 	2)		-- Small Golem
	self:AddJungleMonster("GreatWraith13.1.1", 	2)		-- Great Wraith
	self:AddJungleMonster("GreatWraith14.1.1", 	2)		-- Great Wraith
end

function _Data:_GenerateItemData()
	self:AddItemData("Blade of the Ruined King", 	3153, true, 500)
	self:AddItemData("Bilgewater Cutlass", 			3144, true, 500)
	self:AddItemData("Deathfire Grasp", 			3128, true, 750)
	self:AddItemData("Hextech Gunblade", 			3146, true, 400)
	self:AddItemData("Blackfire Torch", 			3188, true, 750)
	self:AddItemData("Frost Queens Claim", 			3098, true, 750)
	self:AddItemData("Talisman of Ascension", 		3098, false)
	self:AddItemData("Ravenous Hydra", 				3074, false)
	self:AddItemData("Sword of the Divine", 		3131, false)
	self:AddItemData("Tiamat", 						3077, false)
	self:AddItemData("Entropy", 					3184, false)
	self:AddItemData("Youmuu's Ghostblade", 		3142, false)
	self:AddItemData("Muramana", 					3042, false)
	self:AddItemData("Randuins Omen", 				3143, false)
end

ROLE_AD_CARRY = 1
ROLE_AP = 2
ROLE_SUPPORT = 3
ROLE_BRUISER = 4
ROLE_TANK = 5

function _Data:__GenerateChampionData() 
						-- Champion, Projectile Speed,	GameplayCollisionRadius 	Anti-bug delay 			Role
	self:AddChampionData("Aatrox",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Ahri",            1.6,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Akali",           0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Alistar",         0,   				80,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Amumu",           0,   				55,						0,      		ROLE_TANK)
	self:AddChampionData("Anivia",          1.05,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Annie",           1,   				55,						0,      		ROLE_AP)
	self:AddChampionData("Ashe",            2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Blitzcrank",      0,   				80,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Brand",           1.975,  			65,						0,      		ROLE_AP)
	self:AddChampionData("Caitlyn",         2.5,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Cassiopeia",      1.22,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Chogath",         0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Corki",           2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Darius",			0,					80,						0,				ROLE_BRUISER)
	self:AddChampionData("Diana",           0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("DrMundo",         0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Draven",          1.4,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Elise",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Evelynn",         0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Ezreal",          2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("FiddleSticks",    1.75,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Fiora",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Fizz",            0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Galio",           0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Gangplank",		0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Garen",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Gragas",          0,   				80,						0,      		ROLE_AP)
	self:AddChampionData("Graves",          3,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Hecarim",         0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Heimerdinger",    1.4,   				55,						0,      		ROLE_AP)
	self:AddChampionData("Irelia",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Janna",           1.2,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("JarvanIV",		0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Jax",				0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Jayce",           2.2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Jinx",           	2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Karma",           1.2,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Karthus",         1.25,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Kassadin",        0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Katarina",        0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Kayle",           1.8,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Kennen",          1.35,   			55,						0,      		ROLE_AP)
	self:AddChampionData("Khazix",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("KogMaw",          1.8,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Leblanc",         1.7,   				65,						0,      		ROLE_AP)
	self:AddChampionData("LeeSin",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Leona",           0,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Lissandra",       0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Lucian",          2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Lulu",            2.5,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Lux",            	1.55,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Malphite",        0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Malzahar",        1.5,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Maokai",          0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("MasterYi",        0,   				65,						0,      		ROLE_AP)
	self:AddChampionData("MissFortune",     2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("MonkeyKing",		0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Mordekaiser",     0,   				80,						0,      		ROLE_AP)
	self:AddChampionData("Morgana",         1.6,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Nami",            0,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Nasus",           0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Nautilus",		0,					80,						0,				ROLE_BRUISER)
	self:AddChampionData("Nidalee",         1.7,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Nocturne",		0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Nunu",            0,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Olaf",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Orianna",         1.4,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Pantheon",        0,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Poppy",			0,					55,						0,				ROLE_BRUISER)
	self:AddChampionData("Quinn",           1.85,   			65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Rammus",          0,   				65,						0,      		ROLE_TANK)
	self:AddChampionData("Renekton",		0,					80,						0,				ROLE_BRUISER)
	self:AddChampionData("Rengar",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Riven",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Rumble",          0,   				80,						0,      		ROLE_AP)
	self:AddChampionData("Ryze",            2.4,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Sejuani",         0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Shaco",           0,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Shen",            0,   				65,						0,      		ROLE_TANK)
	self:AddChampionData("Shyvana",			0,					50,						0,				ROLE_BRUISER)
	self:AddChampionData("Singed",          0,   				65,						0,      		ROLE_TANK)
	self:AddChampionData("Sion",            0,   				80,						0,      		ROLE_AP)
	self:AddChampionData("Sivir",           1.4,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Skarner",         0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Sona",            1.6,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Soraka",          1,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Swain",           1.6,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Syndra",          1.2,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Talon",           0,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Taric",           0,   				65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Teemo",           1.3,   				55,						0,      		ROLE_AP)
	self:AddChampionData("Thresh",          0,   				55,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Tristana",        2.25,   			55,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Trundle",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Tryndamere",		0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("TwistedFate",     1.5,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Twitch",          2.5,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Udyr",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Urgot",           1.3,   				80,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Varus",           2,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Vayne",           2,   				65,						0,     			ROLE_AD_CARRY)
	self:AddChampionData("Veigar",          1.05,   			55,						0,      		ROLE_AP)
	self:AddChampionData("Vi",				0,					50,						0,				ROLE_BRUISER)
	self:AddChampionData("Viktor",          2.25,   			65,						0,      		ROLE_AP)
	self:AddChampionData("Vladimir",        1.4,   				65,						0,      		ROLE_AP)
	self:AddChampionData("Volibear",        0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Warwick",         0,   				65,						0,      		ROLE_TANK)
	self:AddChampionData("Xerath",          1.2,   				65,						0,      		ROLE_AP)
	self:AddChampionData("XinZhao",			0,					65,						0,				ROLE_BRUISER)
	self:AddChampionData("Yasuo",          	0,   				65,						0,      		ROLE_BRUISER)
	self:AddChampionData("Yorick",          0,   				80,						0,      		ROLE_TANK)
	self:AddChampionData("Zac",             0,   				65,						0,      		ROLE_TANK)
	self:AddChampionData("Zed",             0,   				65,						0,      		ROLE_AD_CARRY)
	self:AddChampionData("Ziggs",           1.5,   				55,						0,      		ROLE_AP)
	self:AddChampionData("Zilean",          1.25,   			65,						0,      		ROLE_SUPPORT)
	self:AddChampionData("Zyra",            1.7,   				65,						0,      		ROLE_AP)
end							
							
function _Data:__GenerateSkillData()
				  --Name 			  Enabled    Key 	 Range 		Display Name 				Type 			MinMana  Reset 	 Require Attack Target 	   Speed 		Delay 	Width 	Collision
	self:AddSkillData("Ezreal",		 	true,	 _Q,	 1100,	 "Q (Mystic Shot)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		250,	70,	 	true)
	self:AddSkillData("Ezreal",		 	true,	 _W,	 1050,	 "W (Essence Flux)",		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.6,	 	250,	90,	 	false)
	self:AddSkillData("KogMaw",		 	true,	 _Q,	 625,	 "Q (Caustic Spittle)",	 	SPELL_TARGETED,	 		0,	 true,	 	true,	 				1.3,	 	260,	200,	false)
	self:AddSkillData("KogMaw",		 	true,	 _W,	 625,	 "W (Bio-Arcane Barrage)",	SPELL_SELF,	 			0,	 false,	 	false,	 				1.3,	 	260,	200,	false)
	self:AddSkillData("KogMaw",		 	true,	 _E,	 850,	 "E (Void Ooze)",	 		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.3,	 	260,	200,	false)
	self:AddSkillData("KogMaw",		 	true,	 _R,	 1700,	 "R (Living Artillery)",	SPELL_LINEAR,	 		0,	 false,	 	false,	 				math.huge,	1000,	200,	false)
	self:AddSkillData("Sivir",		 	true,	 _Q,	 1000,	 "Q (Boomerang Blade)",	 	SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.33,	 	250,	120,	false)
	self:AddSkillData("Sivir",		 	true,	 _W,	 900,	 "W (Ricochet)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				1,	 		0,	 	200,	false)
	self:AddSkillData("Graves",		 	true,	 _Q,	 750,	 "Q (Buck Shot)",	 		SPELL_CONE,	 			0,	 false,	 	false,	 				2,	 		250,	200,	false)
	self:AddSkillData("Graves",		 	true,	 _W,	 700,	 "W (Smoke Screen)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1400,	 	300,	500,	false)
	self:AddSkillData("Graves",		 	true,	 _E,	 580,	 "E (Quick Draw)",	 		SPELL_SELF_AT_MOUSE,	0,	 true,	 	true,	 				1450,	 	250,	200,	false)
	self:AddSkillData("Caitlyn",		true,	 _Q,	 1300,	 "Q (Piltover Peacemaker)",	SPELL_LINEAR,			0,	 false,	 	false,	 				2.1,	 	625,	100,	true)
	self:AddSkillData("Corki",		 	true,	 _Q,	 600,	 "Q (Phosphorus Bomb)",	 	SPELL_CIRCLE,			0,	 false,	 	false,	 				2,	 		200,	500,	false)
	self:AddSkillData("Corki",		 	true,	 _R,	 1225,	 "R (Missile Barrage)",	 	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		200,	50,	 	true)
	self:AddSkillData("Teemo",		 	true,	 _Q,	 580,	 "Q (Blinding Dart)",	 	SPELL_TARGETED,	 		0,	 false,	 	false,	 				2,	 		0,		200,	false)
	self:AddSkillData("TwistedFate",	true,	 _Q,	 1200,	 "Q (Wild Cards)",	 		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.45,		250,	200,	false)
	self:AddSkillData("Vayne",			true,	 _Q,	 750,	 "Q (Tumble)",	 			SPELL_SELF_AT_MOUSE,	0,	 true,	 	true,	 				1.45,		250,	200,	false)
	self:AddSkillData("Vayne",			true,	 _R,	 580,	 "R (Final Hour)",	 		SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,		250,	200,	false)
	self:AddSkillData("MissFortune",	true,	 _Q,	 650,	 "Q (Double Up)",	 		SPELL_TARGETED,	 		0,	 true,	 	true,	 				1.45,		250,	200,	false)
	self:AddSkillData("MissFortune",	true,	 _W,	 580,	 "W (Impure Shots)",	 	SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,		250,	200,	false)
	self:AddSkillData("MissFortune",	true,	 _E,	 800,	 "E (Make It Rain)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				math.huge,	500,	500,	false)
	self:AddSkillData("Tristana",		true,	 _Q,	 580,	 "Q (Rapid Fire)",	 		SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,	 	250,	200,	false)
	self:AddSkillData("Tristana",		true,	 _E,	 550,	 "E (Explosive Shot)",		SPELL_TARGETED,			0,	 false,	 	false,	 				1.45,	 	250,	200,	false)
	self:AddSkillData("Draven",			true,	 _E,	 950,	 "E (Stand Aside)",	 		SPELL_LINEAR,			0,	 false,	 	false,	 				1.37,	 	300,	130,	false)
	self:AddSkillData("Kennen",			true,	 _Q,	 1050,	 "Q (Thundering Shuriken)",	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.65,	 	180,	80,	 	true)
	self:AddSkillData("Ashe",			true,	 _W,	 1200,	 "W (Volley)",	 			SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		120,	85,	 	true)
	self:AddSkillData("Syndra",			true,	 _Q,	 800,	 "Q (Dark Sphere)",	 		SPELL_CIRCLE,	 		0,	 false,	 	false,	 				math.huge,	400,	100,	false)
	self:AddSkillData("Jayce",			true,	 _Q,	 1600,	 "Q (Shock Blast)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		350,	90,	 	true)
	self:AddSkillData("Nidalee",		true,	 _Q,	 1500,	 "Q (Javelin Toss)",	 	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.3,		125,	80,	 	true)
	self:AddSkillData("Varus",			true,	 _E,	 925,	 "E (Hail of Arrows)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1.75,		240,	235,	false)
	self:AddSkillData("Quinn",			true,	 _Q,	 1050,	 "Q (Blinding Assault)",	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.55,		220,	90,	 	true)
	self:AddSkillData("LeeSin",			true,	 _Q,	 975,	 "Q (Sonic Wave)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.5,		250,	70,	 	true)
	self:AddSkillData("Gangplank",		true,	 _Q,	 625,	 "Q (Parley)",	 			SPELL_TARGETED,	 		0,	 false,	 	false,	 				1.45,		250,	200,	false)
	self:AddSkillData("Twitch",		 	true,	 _W,	 950,	 "W (Venom Cask)",	 		SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1.4,		250,	275,	false)
	self:AddSkillData("Darius",		 	true,	 _W,	 300,	 "W (Crippling Strike)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Hecarim",		true,	 _Q,	 300,	 "Q (Rampage)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Warwick",		true,	 _Q,	 300,	 "Q (Hungering Strike)",	SPELL_TARGETED,	 		0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("MonkeyKing",		true,	 _Q,	 300,	 "Q (Crushing Blow)",		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Poppy",		 	true,	 _Q,	 300,	 "Q (Devastating Blow)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Talon",		 	true,	 _Q,	 300,	 "Q (Noxian Diplomacy)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Nautilus",		true,	 _W,	 300,	 "W (Titans Wrath)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Gangplank",		true,	 _Q,	 300,	 "Q (Parlay)",	 			SPELL_TARGETED,	 		0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Vi",		 		true,	 _E,	 300,	 "E (Excessive Force)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Rengar",		 	true,	 _Q,	 300,	 "Q (Savagery)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Trundle",		true,	 _Q,	 300,	 "Q (Chomp)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Leona",		 	true,	 _Q,	 300,	 "Q (Shield Of Daybreak)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Fiora",		 	true,	 _E,	 300,	 "E (Burst Of Speed)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Blitzcrank",		true,	 _E,	 300,	 "E (Power Fist)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Shyvana",		true,	 _Q,	 300,	 "Q (Twin Blade)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Renekton",		true,	 _W,	 300,	 "W (Ruthless Predator)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Jax",		 	true,	 _W,	 300,	 "W (Empower)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("XinZhao",		true,	 _Q,	 300,	 "Q (Three Talon Strike)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true)
	self:AddSkillData("Nunu",			true,	 _E,	 300,	 "E (Snowball)",	 		SPELL_TARGETED,			0,	 false,	 	false,	 				1.45,		250,	200,	false)
	self:AddSkillData("Khazix",			true,	 _Q,	 300,	 "Q (Taste Their Fear)",	SPELL_TARGETED,			0,	 true,	 	true,	 				1.45,		250,	200,	false)
	self:AddSkillData("Shen",			true,	 _Q,	 300,	 "Q (Vorpal Blade)",	 	SPELL_TARGETED,			0,	 false,	 	false,	 				1.45,		250,	200,	false)
end

function _Data:AddResetSpell(name)
	self.ResetSpells[name] = true
end

function _Data:AddSpellAttack(name)
	self.SpellAttacks[name] = true
end

function _Data:AddNoneAttack(name)
	self.NoneAttacks[name] = true
end

function _Data:AddChampionData(Champion, ProjSpeed, _GameplayCollisionRadius, Delay, _Priority)
	self.ChampionData[Champion] = {Name = Champion, ProjectileSpeed = ProjSpeed, GameplayCollisionRadius = _GameplayCollisionRadius, BugDelay = Delay and Delay or 0, Priority = _Priority }
end

function _Data:AddMinionData(Name, delay, ProjSpeed)
	self.MinionData[Name] = {Delay = delay, ProjectileSpeed = ProjSpeed}
end

function _Data:AddJungleMonster(Name, Priority)
	self.JungleData[Name] = Priority
end

function _Data:GetJunglePriority(Name)
	return self.JungleData[Name]
end

function _Data:AddItemData(Name, ID, RequiresTarget, Range)
	self.ItemData[ID] = _Item(Name, ID, RequiresTarget, Range)
end

function _Data:AddSkillData(Name, Enabled, Key, Range, DisplayName, Type, MinMana, AfterAttack, ReqAttackTarget, Speed, Delay, Width, Collision)
	if myHero.charName == Name then
		local skill = _Skill(Enabled, Key, Range, DisplayName, Type, MinMana, AfterAttack, ReqAttackTarget, Speed, Delay, Width, Collision)
		table.insert(self.Skills, skill)
	end
end

function _Data:GetProjectileSpeed(name)
	return self.ChampionData[name] and self.ChampionData[name].ProjectileSpeed or nil
end

function _Data:GetGameplayCollisionRadius(name)
	return self.ChampionData[name] and self.ChampionData[name].GameplayCollisionRadius or 65
end

function _Data:IsResetSpell(Spell)
	return self.ResetSpells[Spell.name]
end

function _Data:IsAttack(Spell)
	return (self.SpellAttacks[Spell.name] or Helper:StringContains(Spell.name, "attack")) and not self.NoneAttacks[Spell.name]
end

function _Data:IsJungleMinion(Object)
	return Object and Object.name and self.JungleData[Object.name] ~= nil
end

function _Data:IsCannonMinion(Minion)
	return Minion.charName:find("Cannon")
end

function _Data:GenerateHitBoxData()
	for i = 1, heroManager.iCount do
        local hero = heroManager:GetHero(i)
        self.EnemyHitBoxes[hero.charName] = GetDistance(hero.minBBox, hero.maxBBox)
    end
	GetSave("SidasAutoCarry").EnemyHitBoxes = {TimeSaved = GetGameTimer(), Data = self.EnemyHitBoxes}
end

function _Data:LoadHitBoxData()
	local HitBoxes = GetSave("SidasAutoCarry").EnemyHitBoxes
	if HitBoxes then
		self.EnemyHitBoxes = HitBoxes.Data
	else
		self:GenerateHitBoxData()
	end
end

function _Data:GetHitBoxLastSavedTime()
	local Time = GetSave("SidasAutoCarry").EnemyHitBoxes
	if Time then
		Time = Time.TimeSaved
	else
		Time = math.huge
	end
	return Time
end

function _Data:GetOriginalHitBox(Target)
	return self.EnemyHitBoxes[Target.charName]
end

function _Data:EnemyIsImmune(Enemy)
	if self.ImmuneEnemies[Enemy.charName] then
		if Enemy.charName == "Tryndamere" and Enemy.health < MyHero:GetTotalAttackDamageAgainstTarget(Enemy) then
			return true
		elseif Enemy.charName ~= "Tryndamere" then
			return true
		end
	end
end

--[[ Initialize Classes ]]
function Init()
	if VIP_USER then
		if FileExist(SCRIPT_PATH..'Common/Prodiction.lua') then
			require "Prodiction"
		else
			print("[Reborn]: You need the Prodiction library from the forums!")
			return false
		end
	end
	AutoCarry.Skills 		= _Skills()
	AutoCarry.Keys  		= _Keys()
	AutoCarry.Items 		= _Items()
	AutoCarry.Data 			= _Data()
	AutoCarry.Jungle 		= _Jungle()
	AutoCarry.Helper 		= _Helper()
	AutoCarry.MyHero 		= _MyHero()
	AutoCarry.Minions 		= _Minions()
	AutoCarry.Crosshair 	= _Crosshair(DAMAGE_PHYSICAL, MyHero.TrueRange, 0, false, false)
	AutoCarry.Orbwalker 	= _Orbwalker()
	AutoCarry.Plugins 		= _Plugins()
	Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = Helper:GetClasses()
	SwingTimer 	= _SwingTimer()
	_MenuManager()
	_Drawing()
	_Summoners()
	AutoCarry.Structures 	= _Structures()
	Streaming:CreateMenu()
	local _, files = ScanDirectory(BOL_PATH.."Scripts\\SidasAutoCarryPlugins")
	for _, file in pairs(files) do
		dofile(BOL_PATH.."Scripts\\SidasAutoCarryPlugins\\"..AutoCarry.Helper:TrimString(file))
	end

	if myHero.charName == "Vayne" then
		--_Vayne()
	end

	PrintChat("<font color='#CCCCCC'> >> Valid license found! <<</font>")
	PrintChat("<font color='#CCCCCC'> >> Sida's Auto Carry - Reborn, loaded! <<</font>")
	PrintChat("<font color='#CCCCCC'> >> Loaded as "..(VIP_USER and "VIP" or "Non-VIP").." user <<</font>")


	--[[
			Legacy Plugin Support
			Plugins should be updated, this may be removed after a few months.
	]]

	--AutoCarry.Orbwalker = AutoCarry.Crosshair.Attack_Crosshair
	AutoCarry.SkillsCrosshair = AutoCarry.Crosshair.Skills_Crosshair
	AutoCarry.CanMove = true
	AutoCarry.CanAttack = true
	AutoCarry.MainMenu = {}
	AutoCarry.PluginMenu = nil
	AutoCarry.EnemyTable = GetEnemyHeroes()
	AutoCarry.shotFired = false
	AutoCarry.OverrideCustomChampionSupport = false
	AutoCarry.CurrentlyShooting = false
	DoneInit = true


	class '_LegacyPlugin'

	function _LegacyPlugin:__init()
		AutoCarry.PluginMenu = scriptConfig("Sida's Auto Carry Plugin: "..myHero.charName, "sidasacplugin"..myHero.charName)
		require("SidasAutoCarryPlugin - "..myHero.charName)
		PrintChat(">> Sida's Auto Carry: Loaded "..myHero.charName.." plugin!")
		AddTickCallback(function() self:_OnTick() end)

		if PluginOnTick then
			AddTickCallback(function() PluginOnTick() end)
		end
		if PluginOnDraw then
			AddDrawCallback(function() PluginOnDraw() end)
		end
		if PluginOnCreateObj then
			AddCreateObjCallback(function(obj) PluginOnCreateObj(obj) end)
		end
		if PluginOnDeleteObj then
			AddDeleteObjCallback(function(obj) PluginOnDeleteObj(obj) end)
		end
		if PluginOnLoad then
			PluginOnLoad()
		end
		if PluginOnUnload then
			AddUnloadCallback(function() PluginOnUnload() end)
		end
		if PluginOnWndMsg then
			AddMsgCallback(function(msg, key) PluginOnWndMsg(msg, key) end)
		end
		if PluginOnProcessSpell then
			AddProcessSpellCallback(function(unit, spell) PluginOnProcessSpell(unit, spell) end)
		end
		if PluginOnSendChat then
			AddChatCallback(function(text) PluginOnSendChat(text) end)
		end
		if PluginOnBugsplat then
			AddBugsplatCallback(function() PluginOnBugsplat() end) 
		end
		if PluginOnAnimation then
			AddAnimationCallback(function(unit, anim) PluginOnAnimation(unit, anim) end)
		end
		if PluginOnSendPacket then
			AddSendPacketCallback(function(packet) PluginOnSendPacket(packet) end)
		end
		if PluginOnRecvPacket then
			AddRecvPacketCallback(function(packet) PluginOnRecvPacket(packet) end)
		end
		if PluginOnApplyParticle then
			AddParticleCallback(function(unit, particle) PluginOnApplyParticle(unit, particle) end)
		end
		if OnAttacked then
			RegisterOnAttacked(OnAttacked)
		end
		if PluginBonusLastHitDamage then
			Plugins:RegisterBonusLastHitDamage(PluginBonusLastHitDamage) 
		end

		if CustomAttackEnemy then
			Plugins:RegisterPreAttack(CustomAttackEnemy)
		end
	end

	function _LegacyPlugin:_OnTick()
		AutoCarry.MainMenu.AutoCarry = AutoCarryMenu.Active
		AutoCarry.MainMenu.LastHit = LastHitMenu.Active
		AutoCarry.MainMenu.MixedMode = MixedModeMenu.Active
		AutoCarry.MainMenu.LaneClear = LaneClearMenu.Active
		MyHero:MovementEnabled(AutoCarry.CanMove)
		MyHero:AttacksEnabled(AutoCarry.CanAttack)
		if #AutoCarry.EnemyTable < #Helper.EnemyTable then
			AutoCarry.EnemyTable = Helper.EnemyTable
		end
	end

	AutoCarry.GetAttackTarget = function(isCaster)
		return Crosshair:GetTarget()
	end

	AutoCarry.GetKillableMinion = function()
		return Minions.KillableMinion
	end

	AutoCarry.GetMinionTarget = function()
		return nil
	end

	AutoCarry.EnemyMinions = function()
		return Minions.EnemyMinions
	end

	AutoCarry.AllyMinions = function()
		return Minions.AllyMinions
	end

	AutoCarry.GetJungleMobs = function()
		return Jungle.JungleMonsters
	end

	AutoCarry.GetLastAttacked = function()
		return Orbwalker.LastEnemyAttacked
	end

	AutoCarry.GetNextAttackTime = function()
		return Orbwalker:GetNextAttackTime()
	end

	AutoCarry.CastSkillshot = function (skill, target)
            if VIP_USER then
                pred = TargetPredictionVIP(skill.range, skill.speed*1000, (skill.delay/1000 - (GetLatency()/2)/1000), skill.width)
            elseif not VIP_USER then
                pred = TargetPrediction(skill.range, skill.speed, skill.delay, skill.width)
            end
            local predPos = pred:GetPrediction(target)
            if predPos and GetDistance(predPos) <= skill.range then
                if VIP_USER and pred:GetHitChance(target) > ConfigMenu.HitChance/100 then --TODO
                    if not skill.minions or not AutoCarry.GetCollision(skill, myHero, predPos) then
                       CastSpell(skill.spellKey, predPos.x, predPos.z)
                    end
                elseif not VIP_USER then
                    if not skill.minions or not AutoCarry.GetCollision(skill, myHero, predPos) then
                            CastSpell(skill.spellKey, predPos.x, predPos.z)
                    end
                end
            end
        end

	AutoCarry.GetCollision = function (skill, source, destination)
		if VIP_USER then
			local col = Collision(skill.range, skill.speed*1000 , (skill.delay/1000 - (GetLatency()/2)/1000), skill.width)
			return col:GetMinionCollision(source, destination)
		else
			return willHitMinion(destination, skill.width)
		end
	end

	AutoCarry.GetPrediction = function(skill, target)
		if VIP_USER then
			pred = TargetPredictionVIP(skill.range, skill.speed*1000, skill.delay/1000, skill.width)
		elseif not VIP_USER then
			pred = TargetPrediction(skill.range, skill.speed, skill.delay, skill.width)
		end
		return pred:GetPrediction(target)
	end

	AutoCarry.IsValidHitChance = function(skill, target)
		return true
	end

	AutoCarry.GetProdiction = function(Key, Range, Speed, Delay, Width, Source, Callback)
		return AutoCarry.Plugins:GetProdiction(Key, Range, Speed, Delay, Width, Source, Callback)
	end

	function willHitMinion(predic, width)
		for _, minion in pairs(Minions.EnemyMinions.objects) do
			if minion ~= nil and minion.valid and string.find(minion.name,"Minion_") == 1 and minion.team ~= player.team and minion.dead == false then
				if predic ~= nil then
					ex = player.x
					ez = player.z
					tx = predic.x
					tz = predic.z
					dx = ex - tx
					dz = ez - tz
					if dx ~= 0 then
						m = dz/dx
						c = ez - m*ex
					end
					mx = minion.x
					mz = minion.z
					distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
					if distanc < width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
						return true
					end
				end
			end
		end
		return false
	end

	if FileExist(LIB_PATH .."SidasAutoCarryPlugin - "..myHero.charName..".lua") then
	    _LegacyPlugin()
	end
end


--[[
		Custom Champion Support
]]

-- class '_Vayne'

-- function _Vayne:__init()
	-- self.CondemnDrawPositions = {}
	-- self.WardInfo = nil

	-- AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)
	-- AddTickCallback(function() self:_OnTick() end)
	
	-- VayneMenu = scriptConfig("Sida's Auto Carry Reborn: Vayne", "sidasacvayne")
	-- VayneMenu:addSubMenu("Configuration", "sidasacvaynesub")
	-- VayneMenu:addSubMenu("Allowed Condemn Targets", "sidasacvayneallowed")
	
	-- VayneMenu:addParam("Enabled", "Hotkey", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("N"))
	-- VayneMenu:addParam("toggleMode", "Toggle Mode", SCRIPT_PARAM_ONOFF, false)
	
	-- VayneMenu.sidasacvaynesub:addParam("condemnclosers", "Auto-Condemn Gap Closers", SCRIPT_PARAM_ONOFF, true)
	-- VayneMenu.sidasacvaynesub:addParam("pushDistance", "Max enemy distance from wall to condemn", SCRIPT_PARAM_SLICE, 300, 0, 450, 0)
	-- VayneMenu.sidasacvaynesub:addParam("condemnSAC", "Only condemn Reborn target", SCRIPT_PARAM_ONOFF, true)
	-- VayneMenu.sidasacvaynesub:addParam("drawCircle", "Draw Condemn Position", SCRIPT_PARAM_ONOFF, true)
	
	-- for i, Enemy in pairs(GetEnemyHeroes()) do
		-- VayneMenu.sidasacvaynealowed:addParam("enabled"..i, enemy.charName, SCRIPT_PARAM_ONOFF, true)
	-- end
	
	-- print("Sida's Auto Carry Reborn: Vayne's Average Receptionist Loaded!")
	
-- end

-- function _Vayne:_OnTick()
	-- if myHero.dead then return end
	
	-- --[[ Insert Menu Text Toggles ]]
	
	-- if myHero:CanUseSpell(_E) == READY then
		-- self:CheckCondemn()
	-- end
-- end

-- function _Vayne:AddCondemnPos(Pos)
	-- table.insert(self.CondemnDrawPositions, Pos)
-- end

-- function _Vayne:DoWard()
	-- if self.WardInfo then
		-- if GetTickCount() > self.WardInfo.CastAt + 2000 then
			-- self.WardInfo = nil
			-- return
		-- else
			-- if IHaveWard then -- todo
				-- CastSpell(ITEM_4, self.WardInfo.CollisionPos)
			-- end
		-- end
	-- end
-- end

-- function _Vayne:_OnDraw()
	-- for i, Pos in pairs(self.CondemnDrawPositions) do
		-- DrawCircle(Pos.x, Pos.y, Pos.z, 65, ARGB(255, 0, 255, 0))
		-- table.remove(self.CondemnDrawPositions, i)
	-- end
-- end

-- function _Vayne:_CondemnGapCloser()
	-- if VayneMenu.sidasacvaynesub.condemnClosers then
		-- if (GetTickCount() - spellInfo.spellCastAt) <= (spellInfo.Range/spellInfo.Speed) * 1000 and not spellEnded then
			-- local myPos = Point(myHero.x, myHero.z)
			-- local Direction = (spellInfo.EndPos - spellInfo.StartPos):normalized()
			-- local spellStart = spellInfo.StartPos + Direction
			-- local spellEnd = spellInfo.StartPos + Direction * spellInfo.Range
			
			-- local Seg = LineSegment(Point(spellStart.x, spellStart.y), Point(spellEnd.x, spellEnd.y)
			
			-- if Seg:distance(myPos) <= (not spellInfo.IsException and 65 or 200) then
				-- CastSpell(_E, spellInfo.Source)
			-- end
		-- else
			-- spellInfo = {}
			-- spellEnded = true
		-- end
	-- end
-- end

-- function _Vayne:CheckCondemn()
	-- if VayneMenu.sidasacvaynesub.condemnSAC then
		-- if Crosshair.Attack_Crossair.target then
			-- local Bush = self:DoCondemn(Crosshair.Attack_Crosshair.target)
		-- else
			-- for i, Enemy in pairs(GetEnemyHeroes()) do
				-- local Bush = self:DoCondemn(Enemy)
				-- if Bush then
					-- break
				-- end
			-- end
		-- end
		
		-- if Bush then
			-- self.WardInfo = Bush
		-- end
	-- end
-- end

-- -- Credits to vadash
-- function _Vayne:FindNearestNonWall( x0, y0, z0, maxRadius, precision )
    -- if not IsWall(D3DXVECTOR3(x0, y0, z0)) then return D3DXVECTOR3(x0, y0, z0) end
    -- local radius, gP = 1, precision or 50
    -- x0, y0, z0, maxRadius = math.round(x0/gP)*gP, math.round(y0/gP)*gP, math.round(z0/gP)*gP, maxRadius and math.floor(maxRadius/gP) or math.huge
    -- local function toGamePos(x, y) return x0+x*gP, y0, z0+y*gP end
    -- while radius<=maxRadius do
        -- for i = 1, 4 do
           -- local p = D3DXVECTOR3(toGamePos((i==2 and radius) or (i==4 and -radius) or 0,(i==1 and radius) or (i==3 and -radius) or 0))
           -- if not IsWall(p) then return p end
        -- end
        -- local f, x, y = 1-radius, 0, radius
        -- while x<y-1 do
            -- x = x + 1
            -- if f < 0 then f = f+1+x+x
            -- else y, f = y-1, f+1+x+x-y-y end
            -- for i=1, 8 do
                -- local w = math.ceil(i/2)%2==0
                -- local p = D3DXVECTOR3(toGamePos(((i+1)%2==0 and 1 or -1)*(w and x or y),(i<=4 and 1 or -1)*(w and y or x)))
                -- if not IsWall(p) then return p end
            -- end
        -- end
        -- radius = radius + 1
    -- end
-- end

-- function _Vayne:DoCondemn(Enemy)
	-- -- Check Menu Key Down
	-- if VayneMenu.sidasacvayneallowed["enabled"..i] then
		-- if ValidTarget(Enemy, 715) and GetDistance(Enemy) > 0 then
			-- local EnemyPos = tp:GetPrediction(Enemy)
			-- local PushPos = EnemyPos + (Vector(EnemyPos) - myHero):normalized() * VayneMenu.sidasacvaynesub.pushDistance
			-- local AtBush
			
			-- if Enemy.x > 0 and Enemy.z > 0 then
				-- local TMS = math.ceil((VayneMenu.sidasacvaynesub.pushDistance)/65)
				-- local TMSDistance = (VayneMenu.sidasacvaynesub.pushDistance)/TMS)
				-- local InWall = false
				-- local IsBush = false
				-- for j = 1, TMS, 1 do
					-- local TMSPos = EnemyPos + (Vector(EnemyPos) - myHero):normalised() * (TMSDistance * j)
					-- local AtWall = IsWall(D3DXVECTOR3(TMSPos.x, TMSPos.y, TMSPos.z))
					-- local AtBush = self:FindNearestNonWall(TSMPos.x, TSMPos.y, TSMPos.z, 100, 20)
					-- if AtWall then
						-- InWall  = true
						-- if AtBush and IsBladeOfGrass(AtBush) then
							-- InBush = true
						-- end
						-- break
					-- end
				-- end
				
				-- if VayneMenu.sidasacvaynesub.drawCircle then
					-- self:AddCondemnPos(PushPos)
				-- end
				
				-- if InWall then
					-- CastSpell(_E, Enemy)
					-- if InBush then
						-- return {CastAt = GetTickCount(), CollisionPos = AtBush}
					-- end
				-- end
			-- end
		-- end
	-- end
-- end

-- function _Vayne:_OnProcessSpell(Unit, Spell)
	-- if not VayneMenu.sidasacvaynesub.condemnClosers then return end
	
	-- local JarKey = unit.charName == "JarvanIV" and unit:CanUseSpell(_Q) ~= READY and _R or _Q
	-- local GetSpellData = {
		-- Aatrox      = {true, Key = _Q,                  Range = 1000,  Speed = 1200, },
        -- Akali       = {true, Key = _R,                  Range = 800,   Speed = 2200, }, 
        -- Alistar     = {true, Key = _W,                  Range = 650,   Speed = 2000, }, 
        -- Diana       = {true, Key = _R,                  Range = 825,   Speed = 2000, }, 
        -- Gragas      = {true, Key = _E,                  Range = 600,   Speed = 2000, },
        -- Graves      = {true, Key = _E,                  Range = 425,   Speed = 2000, Exception = true },
        -- Hecarim     = {true, Key = _R,                  Range = 1000,  Speed = 1200, },
        -- Irelia      = {true, Key = _Q,                  Range = 650,   Speed = 2200, }, 
        -- JarvanIV    = {true, Key = JarKey,      Range = 770,   Speed = 2000, },
        -- Jax         = {true, Key = _Q,                  Range = 700,   Speed = 2000, }, 
        -- Jayce       = {true, Key = 'JayceToTheSkies',   Range = 600,   Speed = 2000, }, 
        -- Khazix      = {true, Key = _E,                  Range = 900,   Speed = 2000, },
        -- Leblanc     = {true, Key = _W,                  Range = 600,   Speed = 2000, },
        -- LeeSin      = {true, Key = 'blindmonkqtwo',     Range = 1300,  Speed = 1800, },
        -- Leona       = {true, Key = _E,                  Range = 900,   Speed = 2000, },
        -- Malphite    = {true, Key = _R,                  Range = 1000,  Speed = 1500 + Unit.ms},
        -- Maokai      = {true, Key = _Q,                  Range = 600,   Speed = 1200, }, 
        -- MonkeyKing  = {true, Key = _E,                  Range = 650,   Speed = 2200, }, 
        -- Pantheon    = {true, Key = _W,                  Range = 600,   Speed = 2000, }, 
        -- Poppy       = {true, Key = _E,                  Range = 525,   Speed = 2000, }, 
        -- Renekton    = {true, Key = _E,                  Range = 450,   Speed = 2000, },
        -- Sejuani     = {true, Key = _Q,                  Range = 650,   Speed = 2000, },
        -- Shen        = {true, Key = _E,                  Range = 575,   Speed = 2000, },
        -- Tristana    = {true, Key = _W,                  Range = 900,   Speed = 2000, },
        -- Tryndamere  = {true, Key = 'Slash',             Range = 650,   Speed = 1450, },
        -- XinZhao     = {true, Key = _E,                  Range = 650,   Speed = 2000, }, 
	-- }
	
	-- local SpellData = GetSpellData[Unit.charName]
	
	-- if Unit.type == myHero.type and Unit.team ~= myHero.team and SpellData and GetDistance(Unit) < 2000 and Spell then
		-- Spell.name == (type(SpellData.Key) == 'number' and Unit:GetSpellData(SpellData.Key).name or SpellData(Unit.charName).Key) then
			-- if Spell.target and Spell.target == myHero or SpellData[Unit.charName].Key == 'blindmonkqtwo' then
				-- CastSpell(_E, Unit)
			-- else
				-- spellEnded = false
				-- spellInfo = {
				-- Source = Unit,
				-- spellCastAt = GetTickCount(),
				-- EndPos = Point(Spell.endPos.x, spell.endPos.z)
				-- StartPos = Point(Spell.startPos.x, Spell.startPos.z)
				-- Range = SpellData.Range
				-- Speed = SpellData.Speed
				-- IsException = SpellData.Exception or false
				-- }
			-- end
		-- end
	-- end
-- end

--[[ Cone Helper by llama ]]

function areClockwise(testv1,testv2)
    return -testv1.x * testv2.y + testv1.y * testv2.x>0 --true if v1 is clockwise to v2
end
function sign(x)
    if x> 0 then return 1
    elseif x<0 then return -1
    end
end
function GetCone(radius,theta)
    --Build table of enemies in range
    n = 1
    v1,v2,v3 = 0,0,0
    largeN,largeV1,largeV2 = 0,0,0
    theta1,theta2,smallBisect = 0,0,0
    coneTargetsTable = {}
   
    for i = 1, heroManager.iCount, 1 do
    hero = heroManager:getHero(i)
    if ValidTarget(hero,radius) then-- and inRadius(hero,radius*radius) then
            coneTargetsTable[n] = hero
            n=n+1
        end
    end

    if #coneTargetsTable>=2 then -- true if calculation is needed
    --Determine if angle between vectors are < given theta
        for i=1, #coneTargetsTable,1 do
            for j=1,#coneTargetsTable, 1 do
                if i~=j then
                    --Position vector from player to 2 different targets.
                    v1 = Vector(coneTargetsTable[i].x-player.x , coneTargetsTable[i].z-player.z)
                    v2 = Vector(coneTargetsTable[j].x-player.x , coneTargetsTable[j].z-player.z)
                    thetav1 = sign(v1.y)*90-math.deg(math.atan(v1.x/v1.y))
                    thetav2 = sign(v2.y)*90-math.deg(math.atan(v2.x/v2.y))
                    thetaBetween = thetav2-thetav1                 

                    if (thetaBetween) <= theta and thetaBetween>0 then --true if targets are close enough together.
                            if #coneTargetsTable == 2 then --only 2 targets, the result is found.
                                largeV1 = v1
                                largeV2 = v2
                            else
                                --Determine # of vectors between v1 and v2                                                     
                                tempN = 0
                                for k=1, #coneTargetsTable,1 do
                                    if k~=i and k~=j then
                                        --Build position vector of third target
                                        v3 = Vector(coneTargetsTable[k].x-player.x , coneTargetsTable[k].z-player.z)
                                        --For v3 to be between v1 and v2
                                        --it must be clockwise to v1
                                        --and counter-clockwise to v2
                                        if areClockwise(v3,v1) and not areClockwise(v3,v2) then
                                            tempN = tempN+1
                                        end
                                    end
                                end
                                if tempN > largeN then
                                --store the largest number of contained enemies
                                --and the bounding position vectors
                                    largeN = tempN
                                    largeV1 = v1
                                    largeV2 = v2
                                end
                            end
                        end
                    end
                end
            end
    elseif #coneTargetsTable==1 then
        return coneTargetsTable[1]
    end
   
    if largeV1 == 0 or largeV2 == 0 then
    --No targets or one target was found.
            return nil
    else
    --small-Bisect the two vectors that encompass the most vectors.
        if largeV1.y == 0 then
            theta1 = 0
        else
            theta1 = sign(largeV1.y)*90-math.deg(math.atan(largeV1.x/largeV1.y))
        end
        if largeV2.y == 0 then
            theta2 = 0
        else
            theta2 = sign(largeV2.y)*90-math.deg(math.atan(largeV2.x/largeV2.y))
        end

        smallBisect = math.rad((theta1 + theta2) / 2)
        vResult = {}
        vResult.x = radius*math.cos(smallBisect)+player.x
        vResult.y = player.y
        vResult.z = radius*math.sin(smallBisect)+player.z
       
        return vResult
    end
end


function OnLoad()
	-- PrintChat("Checking Reborn license, don't press F9 until complete...")
	-- RunCmdCommand('mkdir "' .. string.gsub(SCRIPT_PATH..'/Sida"', [[/]], [[\]]))
	-- baseEnc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
 -- 	if FileExist(tostring(SCRIPT_PATH .. "/Sida/serial.key")) then
 --        local fp = io.open(tostring(SCRIPT_PATH .. "/Sida/serial.key"), "r" )
 --        for line in fp:lines() do
 --            decode = split(descifrar(line,"regkey"),":")
 --            if decode then
 --                CheckID(decode[1],decode[2],decode[3],false)
 --            end
 --        end
 --  		fp:close()
 --    else
 --        local _, count = string.gsub(GetClipboardText(), ":", "")
 --        if count == 2 then 
 --        	SaveSerial(GetClipboardText())
 --        else
 --        	PrintChat("You do not have a valid Closed Beta account")
 --        end
 --    end
end

function SaveSerial(key)
    Values = split(key,":")
    local file = io.open(tostring(SCRIPT_PATH .. "/Sida/serial.key"), "w" )
    file:write(cifrar(key,"regkey"))
    file:close()
    CheckID(Values[1],Values[2],Values[3],true)
end

function split(pString, pPattern)
    local Table = {}
    local fpat = "(.-)" .. pPattern
    local last_end = 1
    local s, e, cap = pString:find(fpat, 1)
    while s do
        if s ~= 1 or cap ~= "" then
            table.insert(Table,cap)
        end
        last_end = e+1
        s, e, cap = pString:find(fpat, last_end)
    end
    if last_end <= #pString then
        cap = pString:sub(last_end)
        table.insert(Table, cap)
    end
    return Table
end

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
        function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str	
end
	
function enc(data)
    return ((data:gsub('.', function(x) 
        local r,baseEnc='',x:byte()
        for i=8,1,-1 do r=r..(baseEnc%2^i-baseEnc%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return baseEnc:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

function cifrar(str, pass)
	crypt = {}
	local seed = 0
	for i = 1, pass:len() do
		seed = seed + pass:byte(i) + i
	end
	math.randomseed(seed)
	for i = 1, str:len() do
		table.insert(crypt, string.char(str:byte(i) + math.random()*15))
	end
	return table.concat(crypt)
end

function FinishCheck(result)
if string.find(result,"VIP") and VIP_USER then
		SACstate = 2
	elseif string.find(result,"FREE") then
		SACstate = 2
	elseif  string.find(result,"Username") then
		PrintChat("Username is already taken.")
		SACstate = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Sida/serial.key")) then
			os.remove(SCRIPT_PATH .. "/Sida/serial.key")
		end
	elseif string.find(result,"Serial is Invalid") then
		PrintChat("Invalid serial")
		SACstate = 3
	elseif string.find(result,"HWID Invalid") then
		PrintChat("HWID Mismatch: please contact Sida")
		SACstate = 3
	elseif string.find(result, "suspended") then
		SACstate = 3
		PrintChat("Your license has been suspended.")
	else
		SACstate = 3
		PrintChat("You do not have a valid Closed Beta license.")
	end
end

function FinishRegister(result)
	if result and string.find(result,"claimed") then
			PrintChat("Key already claimed")
			SACState = 3
			if FileExist(tostring(SCRIPT_PATH .. "/Sida/serial.key")) then
	--os.remove(SCRIPT_PATH .. "/Sida/serial.key")
	end
		elseif  string.find(result,"SUCCESS") then
		PrintChat("Key successfully registered. Please reload script.")
		SACstate = 2
	end
end	

function CheckID(User,Pass,Serial,Register)
    local authPath = "Auth/"
    local Host = "sidascripts.com"	
	local text = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. os.getenv("USERNAME") .. os.getenv("COMPUTERNAME") .. os.getenv("PROCESSOR_LEVEL") .. os.getenv("PROCESSOR_REVISION"))
	local hwid = url_encode(enc(text))
	--local hwid = url_encode(enc(text))
	local result = ""

   -- PrintChat(Host..authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid)
   	math.randomseed(tonumber(tostring(os.time()):reverse():sub(1,6))*tonumber(myHero.health)+tonumber(myHero.networkID))
	local randomNumber = math.random(1,999999)
	if Register then
		GetAsyncWebResult(Host,authPath.."auth.php".."?a=activateSerial&serial="..Serial.."&user="..User.."&pass="..Pass.."&hwid="..hwid,FinishRegister)
	else
		 GetAsyncWebResult(Host,authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid.."&bol="..GetUser().."&junk="..randomNumber,FinishCheck)
	end
end

function descifrar(str, pass)
    uncry = {}
    local seed = 0
    for i = 1, pass:len() do
        seed = seed + pass:byte(i) + i
    end
    math.randomseed(seed)
    for i = 1, str:len() do
        table.insert(uncry, string.char(str:byte(i) - math.random()*15))
    end
    return table.concat(uncry)
end