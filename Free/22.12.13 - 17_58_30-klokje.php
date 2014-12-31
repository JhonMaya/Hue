<?php exit() ?>--by klokje 80.57.15.158
if VIP_USER then require 'Collision' require "Prodiction" end
-----------------------------------------------走A开始---------------------------------------------------------------------

_G.ChampInfo =
	{
        Ahri         = { projSpeed = 1.6},
        Anivia       = { projSpeed = 1.05},
        Annie        = { projSpeed = 1.0},
        Ashe         = { projSpeed = 2.0, aaspell = "frostarrow"},
		Blitzcrank   = { resetspell = "PowerFist"},
        Brand        = { projSpeed = 1.975},
        Caitlyn      = { projSpeed = 2.5, aaspell = "CaitlynHeadshotMissile"},
        Cassiopeia   = { projSpeed = 1.22},
        Corki        = { projSpeed = 2.0},
		Darius		 = { resetspell = "DariusNoxianTacticsONH"},
        Draven       = { projSpeed = 1.4},
        Ezreal       = { projSpeed = 2.0},
		Elise        = { projSpeed = 2.0},
        FiddleSticks = { projSpeed = 1.75},
		Garen		 = { aaspell = "GarenSlash2", resetspell = "GarenSlash3"},
        Graves       = { projSpeed = 3.0},
		Hecarim		 = { resetspell = "HecarimRamp"},
        Heimerdinger = { projSpeed = 1.4},
        Janna        = { projSpeed = 1.2},
        Jayce        = { projSpeed = 2.2},
		Jax 		 = { resetspell = "JaxEmpowerTwo"},
        Karma        = { projSpeed = 1.2},
        Karthus      = { projSpeed = 1.25},
        Kayle        = { projSpeed = 1.8},
        Kennen       = { projSpeed = 1.35},
        KogMaw       = { projSpeed = 1.8},
        Leblanc      = { projSpeed = 1.7},
		Leona 		 = { resetspell = "LeonaShieldOfDaybreak"},
		Lucian       = { projSpeed = 2.0},
        Lulu         = { projSpeed = 2.5},
        Lux          = { projSpeed = 1.55},
        Malzahar     = { projSpeed = 1.5},
        MissFortune  = { projSpeed = 2.0},
		MonkeyKing	 = { notattack = "MonkeyKingDoubleAttack", resetspell = "MonkeyKingDoubleAttack"},
		Mordekaiser  = { resetspell = "MordekaiserMaceOfSpades"},
        Morgana      = { projSpeed = 1.6},
		Nasus        = { resetspell = "SiphoningStrikeNew"},
        Nidalee      = { projSpeed = 1.7, resetspell = "Takedown"},
		Poppy		 = { resetspell = "PoppyDevastatingBlow"},
        Orianna      = { projSpeed = 1.4},
        Quinn        = { projSpeed = 1.85, aaspell = "QuinnWEnhanced"},
		Rengar       = { resetspell = "RengarQ"},
		Renekton     = { aaspell = {"RenektonExecute", "RenektonSuperExecute"}},
        Ryze         = { projSpeed = 2.4},
		Shyvana		 = { notattack = {"shyvanadoubleattackdragon","ShyvanaDoubleAttack"}, resetspell = {"ShyvanaDoubleAttack", "shyvanadoubleattackdragon"}},
        Sivir        = { projSpeed = 1.4, resetspell = "Ricochet"},
        Sona         = { projSpeed = 1.6},
        Soraka       = { projSpeed = 1.0},
        Swain        = { projSpeed = 1.6},
        Syndra       = { projSpeed = 1.2},
		Talon		 = { resetspell = "TalonNoxianDiplomacy"},
        Teemo        = { projSpeed = 1.3, resetspell = "BlindingDart"},
        Tristana     = { projSpeed = 2.25},
		Trundle      = { aaspell = "TrundleQ", resetspell = "TrundleTrollSmash"},
        TwistedFate  = { projSpeed = 1.5},
        Twitch       = { projSpeed = 2.5},
        Urgot        = { projSpeed = 1.3},
        Vayne        = { projSpeed = 2.0, resetspell = "VayneTumble"},
        Varus        = { projSpeed = 2.0},
        Veigar       = { projSpeed = 1.05},
		Vi			 = { resetspell = "ViE"},
        Viktor       = { projSpeed = 2.25},
        Vladimir     = { projSpeed = 1.4},
		Volibear	 = { resetspell = "VolibearQ"},
        Xerath       = { projSpeed = 1.2},
		XinZhao		 = { aaspell = "XenZhaoThrust", resetspell = "XenZhaoComboTarget"},
		Yorick       = { resetspell = "YorickSpectral"},
        Ziggs        = { projSpeed = 1.5},
        Zilean       = { projSpeed = 1.25},
        Zyra         = { projSpeed = 1.7}
    }
_G.GetTrueRange = 
function()
	return myHero.range + GetDistance(myHero.minBBox)
end



class "myCarry"

STAGE_WINDUP = 1
STAGE_ORBWALK = 2
STAGE_NONE = 3

AASPELL = 1
NOTATTACK = 2
REFRESHATTACK = 3

function myCarry:__init(tianfu,show,ts,closetype,LastHitSkill)
	self.lastAttack, self.previousWindUp, self.projAt, self.previousAttackCooldown,self.WindUpTime,self.NextAttack = 0, 0, 0, 0, 0, 0
	if ChampInfo[myHero.charName] then
		self.AASpell = ChampInfo[myHero.charName].aaspell or nil
		self.projSpeed = ChampInfo[myHero.charName].projSpeed or 0
		self.NotAttack = ChampInfo[myHero.charName].notattack or nil
		self.isrefreshAttack = ChampInfo[myHero.charName].resetspell or nil
	else
		self.AASpell,self.projSpeed,self.NotAttack,self.isrefreshAttack = nil,0,nil,nil
	end

	self.lastRange = GetTrueRange()
	self.HeroType = closetype or 1
	self.enable = true
	self.tianfu = tianfu or nil
	self.show = show or nil
	self.ts = ts or nil
	
	
	self.AutoCarryTarget = nil
	self.killableMinion = nil
	self.pluginMinion = nil
	
	self.LastHitSkill = LastHitSkill or nil
	self.skillkillableMinion = nil
	
	self.enemyMinions = minionManager(MINION_ENEMY, 2000, player, MINION_SORT_HEALTH_ASC)
	self.allyMinions = minionManager(MINION_ALLY, 2000, player, MINION_SORT_HEALTH_ASC)
	self.incomingDamage = {}
	self.minionTimeToHit = 0
	self.minionInfo = {}
	self.jungleMobs = {}
	self.tMinion = nil
	self.jMinion = nil
	self:OrbwalkingOnLoad()
	self:LastHitOnLoad()
	
	AddTickCallback(function() self:OnTick() end)
	AddDrawCallback(function() self:OnDraw() end)
	AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
	AddDeleteObjCallback(function(obj) self:OnDeleteObj(obj) end)
--	AddDrawCallback(function() self:OnDraw() end)
	AddProcessSpellCallback(function(unit,spell) self:OnProcessSpell(unit,spell) end)
	
end

function myCarry:EnemyInRange(target)
	--PrintChat("range"..GetTrueRange())
	if ValidBBoxTarget(target, GetTrueRange()) then
		return true
	end
	return false
end

function myCarry:GetStage()
	if self:timeToShoot() then return STAGE_NONE 
	elseif self:heroCanMove() then return STAGE_ORBWALK 
	else return STAGE_WINDUP
	end
end

function myCarry:timeToShoot()
	return (GetTickCount() + GetLatency()/2 > self.lastAttack + self.previousAttackCooldown)
end

function myCarry:heroCanMove()
	return (GetTickCount() + GetLatency()/2 > self.lastAttack + self.previousWindUp + 20 + 30)
end

function myCarry:moveToCursor(range)
--    if (self.HeroType == 1 or self.HeroType == 3) and not self.ConfigMainMenu.enableMove then return end
	local moveDist = 480 + (GetLatency()/10)
	if not range then
		if self.isMelee and self.OrbwalkTarget.target and self.OrbwalkTarget.type == myHero.type and GetDistance(self.OrbwalkTarget.target) < 80 then 
			self:attackEnemy(self.OrbwalkTarget.target)
			return
		elseif GetDistance(mousePos) < moveDist and GetDistance(mousePos) > 100 then 
			moveDist = GetDistance(mousePos) 
		end
	end
	local moveSqr = math.sqrt((mousePos.x - myHero.x)^2+(mousePos.z - myHero.z)^2)
	local moveX = myHero.x + (range and range or moveDist)*((mousePos.x - myHero.x)/moveSqr)
	local moveZ = myHero.z + (range and range or moveDist)*((mousePos.z - myHero.z)/moveSqr)
--[[		if StreamingMenu.MinRand > StreamingMenu.MaxRand then
			PrintChat("\228\189\160\229\191\133\233\161\187\229\156\168\229\185\179\230\187\145\231\167\187\229\138\168\232\143\156\229\141\149\228\184\173\232\174\190\231\189\174\231\154\132\230\156\128\229\164\167\229\128\188\233\171\152\228\186\142\230\156\128\229\176\143\229\128\188")
		elseif StreamingMenu.ShowClick and GetTickCount() > nextClick then
			if StreamingMenu.Colour == 0 then
				ShowGreenClick(mousePos)
			else
				ShowRedClick(mousePos)
			end
			nextClick = GetTickCount() + math.random(StreamingMenu.MinRand, StreamingMenu.MaxRand)
		end--]]
	myHero:MoveTo(moveX, moveZ)
--	myHero:MoveTo(mousePos.x,mousePos.z)
end

function myCarry:attackEnemy(target)
--	if CustomAttackEnemy then CustomAttackEnemy(enemy) return end
	if target.dead or not target.valid then return end
	myHero:Attack(target)
--[[	lastAttacked = enemy
	AutoCarry.shotFired = true--]]
end

function myCarry:Orbwalk()
	if not self.enable then return end
--[[	local myTarget = GetTarget()
	if myTarget~=nil and (myTarget.type == "obj_AI_Hero" or myTarget.type == "obj_AI_Minion") and ValidTarget(myTarget) then
		self.AutoCarryTarget = myTarget
	else
		self.AutoCarryTarget = self.OrbwalkTarget.target
	end
	
	if self.AutoCarryTarget and self.AutoCarryTarget.health ~= 0 and self:EnemyInRange(self.AutoCarryTarget) then
		if self:timeToShoot() then

			self:attackEnemy(self.AutoCarryTarget)
		elseif self:heroCanMove() then

			self:moveToCursor()
		end
	elseif self:heroCanMove() then
		self:moveToCursor()
	end	--]]
	
	if self.OrbwalkTarget.target and self.OrbwalkTarget.target.health ~= 0 and self:EnemyInRange(self.OrbwalkTarget.target) then
		if self:timeToShoot() then

			self:attackEnemy(self.OrbwalkTarget.target)
		elseif self:heroCanMove() then

			self:moveToCursor()
		end
	elseif self:heroCanMove() then
		self:moveToCursor()
	end
end

function myCarry:OnTick()
	self:OrbwalkingOnTick()
	self:LastHitOnTick()
end

function myCarry:OnCreateObj(obj)
	self:LastHitOnCreateObj(obj)
end

function myCarry:OnDeleteObj(obj)
	self:LastHitOnDeleteObj(obj)
end
	

function myCarry:OnProcessSpell(unit,spell)
	self:OrbwalkingOnProcessSpell(unit,spell)
	self:LastHitOnProcessSpell(unit,spell)
end

function myCarry:OnDraw()
	self:LastHitOnDraw()
end

function myCarry:OrbwalkingOnLoad()
	self.OrbwalkTarget = TargetSelector(TARGET_LOW_HP, GetTrueRange(), DAMAGE_PHYSICAL, true)
	self.OrbwalkTarget:SetBBoxMode(true)
	self.OrbwalkTarget:SetDamages(0, myHero.totalDamage, 0)
	self.OrbwalkTarget.name = "OrbwalkTarget"
--	self.lastRange = GetTrueRange()
--	self.EnemyTable = GetEnemyHeroes()
	self.isMelee = myHero.range < 300
	self:OrbwalkingMenu()
end

function myCarry:OrbwalkingMenu()
	if not self.ConfigMainMenu then
		if self.HeroType == 1 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:连招走A选项/Carry按键选项", "myCarryMain"..myHero.charName)
			self.ConfigMainMenu:addParam("useAA", "连招自动AA", SCRIPT_PARAM_ONOFF, true)
			self.ConfigMainMenu:addParam("enableMove", "连招自动AA使用走A(跟随鼠标)", SCRIPT_PARAM_ONOFF, true)
--			self.ConfigMainMenu:addParam("HoldZone", "鼠标在此范围内不跟随鼠标", SCRIPT_PARAM_SLICE, 0, 0, getTrueRange(), 0)
		elseif self.HeroType == 2 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:Carry按键选项", "myCarryMain"..myHero.charName)
			self.ConfigMainMenu:addParam("Orbwalk", "自动Carry(T)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
			self.ConfigMainMenu:permaShow("Orbwalk")
--			self.ConfigMainMenu:addParam("HoldZone", "鼠标在此范围内不跟随鼠标", SCRIPT_PARAM_SLICE, 0, 0, getTrueRange(), 0)
		elseif self.HeroType == 3 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:连招走A选项/Carry按键选项", "myCarryMain"..myHero.charName)
			self.ConfigMainMenu:addParam("useAA", "连招自动AA", SCRIPT_PARAM_ONOFF, true)
			self.ConfigMainMenu:addParam("enableMove", "连招自动AA使用走A(跟随鼠标)", SCRIPT_PARAM_ONOFF, true)
			self.ConfigMainMenu:addParam("Orbwalk", "自动Carry(T)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
			self.ConfigMainMenu:permaShow("Orbwalk")
--			self.ConfigMainMenu:addParam("HoldZone", "鼠标在此范围内不跟随鼠标", SCRIPT_PARAM_SLICE, 0, 0, getTrueRange(), 0)
		elseif self.HeroType == 4 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:Carry按键选项", "myCarryMain"..myHero.charName)
			self.ConfigMainMenu:addParam("Orbwalk", "自动Carry(T)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
			self.ConfigMainMenu:permaShow("Orbwalk")
		end
	end
end

function myCarry:OrbwalkingOnTick()
	if GetTickCount() + GetLatency()/2 > self.lastAttack + self.previousWindUp + 20 
	and GetTickCount() + GetLatency()/2 < self.lastAttack + self.previousWindUp + 400 then 
	self:attackedSuccessfully() end
	
	self.isMelee = myHero.range < 300
--[[	if self.isMelee then PrintChat("Melee") 
	else PrintChat("not Melee")
	end--]]
	if myHero.range ~= self.lastRange then
		self.OrbwalkTarget.range = myHero.range
		self.lastRange = myHero.range
	end
--[[	if GetTrueRange() ~= self.OrbwalkTarget.range then
		self.OrbwalkTarget.range = GetTrueRange()
	end--]]
--	if not self.ts then
		self.OrbwalkTarget:update()
--	end
	if self.ConfigMainMenu.Orbwalk then
		self:Orbwalk()
	end
end

function myCarry:OrbwalkingOnProcessSpell(unit,spell)
	if myHero.dead then return end
	if unit.isMe and (spell.name:lower():find("attack") or self:isSpellAttack(spell.name)) and not self.isNotAttack(spell.name) then
		self.lastAttack = GetTickCount() - GetLatency()/2
		self.previousWindUp = spell.windUpTime*1000
		self.previousAttackCooldown = spell.animationTime*1000
		self.WindUpTime = spell.windUpTime
        self.nextAttack =  GetGameTimer() + spell.animationTime
	elseif unit.isMe and self:refreshAttack(spell.name) then
		self.lastAttack = GetTickCount() - GetLatency()/2 - self.previousAttackCooldown
	end
end

function myCarry:isSpellAttack(spellname)
--	PrintChat("1")
	if self.AASpell then
		if type(self.AASpell) == "string" then
			return self.AASpell == spellname
		elseif type(self.AASpell) == "table" then
			for _, AAName in pairs(self.AAspell) do
				if AAName == spellname then
					return true
				end
			end
		end
	end
	return false
end

function myCarry:isNotAttack(spellname)
--	PrintChat("2")
	if self.NotAttack then
		if type(self.NotAttack) == "string" then
			return self.NotAttack == spellname
		elseif type(self.NotAttack) == "table" then
			for _, AAName in pairs(self.NotAttack) do
				if AAName == spellname then
					return true
				end
			end
		end
	end
	return false
end

function myCarry:refreshAttack(spellname)
--	PrintChat("3")
	if self.isrefreshAttack then
		if type(self.isrefreshAttack) == "string" then
			return self.isrefreshAttack == spellname
		elseif type(self.isrefreshAttack) == "table" then
			for _, AAName in pairs(self.isrefreshAttack) do
				if AAName == spellname then
					return true
				end
			end
		end
	end
	return false
end

function myCarry:LastHitMenu()
	if not self.ConfigMainMenu then
		if self.HeroType == 1 or self.HeroType == 3 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:走A选项/Carry按键选项", "myCarryMain"..myHero.charName)
		elseif self.HeroType == 2 or self.HeroType == 4 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:Carry按键选项", "myCarryMain"..myHero.charName)
--[[		elseif self.HeroType == 3 then
			self.ConfigMainMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:走A选项/Carry按键选项", "myCarryMain"..myHero.charName)--]]
		end
	end
	self.ConfigMainMenu:addParam("LastHit", "自动补兵(C)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	if self.HeroType == 2 or self.HeroType == 3 then
		self.ConfigMainMenu:addParam("MixedMode", "混合模式(A)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
	end
	self.ConfigMainMenu:addParam("LaneClear", "推线补兵/走A打野(G)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("G"))
	self.ConfigMainMenu:permaShow("LastHit")
	if self.HeroType == 2 or self.HeroType == 3 then
		self.ConfigMainMenu:permaShow("MixedMode")
	end
	self.ConfigMainMenu:permaShow("LaneClear")
end

function myCarry:FarmMenu()
	self.ConfigFarmMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:Carry补兵选项", "myCarryFarm"..myHero.charName)
	self.ConfigFarmMenu:addParam("Predict", "\212\164\197\208\182\212\208\161\177\248\212\236\179\201\181\196\201\203\186\166", SCRIPT_PARAM_ONOFF, true)
	self.ConfigFarmMenu:addParam("moveLastHit", "自动补兵时跟随鼠标移动", SCRIPT_PARAM_ONOFF, true)
	if self.HeroType == 2 or self.HeroType == 3 then
		self.ConfigFarmMenu:addParam("moveMixed", "混合模式时跟随鼠标移动", SCRIPT_PARAM_ONOFF, true)
	end
	self.ConfigFarmMenu:addParam("moveClear", "推线补兵时跟随鼠标移动", SCRIPT_PARAM_ONOFF, true)
	self.ConfigFarmMenu:addParam("JungleFarm", "按住推线补兵键时也包括走A打野", SCRIPT_PARAM_ONOFF, true)
	if self.LastHitSkill then
		self.ConfigFarmMenu:addParam("skillfarm", "补兵时包括使用技能:"..self.LastHitSkill:Name(), SCRIPT_PARAM_ONOFF, true)
		self.ConfigFarmMenu:addParam("LastHitSkillfarm", "自动补兵时使用技能", SCRIPT_PARAM_ONOFF, true)
		if self.HeroType == 2 or self.HeroType == 3 then
			self.ConfigFarmMenu:addParam("MixedModeSkillfarm", "混合模式时使用技能", SCRIPT_PARAM_ONOFF, true)
		end
		self.ConfigFarmMenu:addParam("LaneClearSkillfarm", "推线补兵时使用技能", SCRIPT_PARAM_ONOFF, true)
		if self.LastHitSkill.cost ~= nil and self.LastHitSkill.cost ~= 0 then
			self.ConfigFarmMenu:addParam("skillmana", "技能补兵百分比蓝量:", SCRIPT_PARAM_SLICE, 40, 0, 100, 0)
		end
	end
end

function myCarry:MasteryMenu()
	self.ConfigMasteryMenu = scriptConfig(CNCharName(myHero.charName,1).." V2:Carry补兵天赋选项", "myCarryFarm"..myHero.charName)
	self.ConfigMasteryMenu:addParam("Butcher", "\205\192\183\242", SCRIPT_PARAM_SLICE, 0, 0, 2, 0)
	self.ConfigMasteryMenu:addParam("Spellblade", "\214\228\189\163", SCRIPT_PARAM_ONOFF, false)
	self.ConfigMasteryMenu:addParam("Executioner", "\203\192\201\241", SCRIPT_PARAM_ONOFF, false)
end


function myCarry:LastHitOnLoad()
	self.minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Basic"] =      { aaDelay = 400, projSpeed = 0    }
	self.minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Caster"] =     { aaDelay = 484, projSpeed = 0.68 }
	self.minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Wizard"] =     { aaDelay = 484, projSpeed = 0.68 }
	self.minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_MechCannon"] = { aaDelay = 365, projSpeed = 1.18 }
	self.minionInfo.obj_AI_Turret =                                         { aaDelay = 150, projSpeed = 1.14 }
   
	for i = 0, objManager.maxObjects do
		local obj = objManager:getObject(i)
		for _, mob in pairs(self:getJungleMobs()) do
			if obj and obj.valid and obj.name:find(mob) then
				table.insert(self.jungleMobs, obj)
			end
		end
	end
	self:LastHitMenu()
	self:FarmMenu()
	if not self.tianfu then
		self:MasteryMenu()
	end
end

function myCarry:LastHitOnDraw()
	if myHero.dead then return end
	if self.show then
		if self.show.ShowRangeConfig.minion and self.enemyMinions.objects[1] and ValidTarget(self.enemyMinions.objects[1]) then
			DrawCircle(self.enemyMinions.objects[1].x, self.enemyMinions.objects[1].y, self.enemyMinions.objects[1].z, 100, 0x19A712)
		end
		if self.show.ShowRangeConfig.killable and ValidTarget(self.killableMinion) then
			DrawCircle(self.killableMinion.x, self.killableMinion.y, self.killableMinion.z, 120, 0xFF0000)
		end
--[[	else
		if self.ConfigDisplayMenu.minion and self.enemyMinions.objects[1] and ValidTarget(self.enemyMinions.objects[1]) then
			DrawCircle(self.enemyMinions.objects[1].x, self.enemyMinions.objects[1].y, self.enemyMinions.objects[1].z, 100, 0x19A712)
		end--]]
	end

end

function myCarry:getJungleMobs()
        return {
--紫色方		
		"Dragon6.1.1", -- 小龙
		"Worm12.1.1",  -- 大龙       
		
		"Wraith9.1.1", "LesserWraith9.1.2", "LesserWraith9.1.3", "LesserWraith9.1.4", --四鬼
		"LizardElder10.1.1", "YoungLizard10.1.2", "YoungLizard10.1.3", -- 红BUFF
		"Golem11.1.2", "SmallGolem11.1.1", -- 石头人
		"GiantWolf8.1.1", "Wolf8.1.2", "Wolf8.1.3", --三狼
		"AncientGolem7.1.1", "YoungLizard7.1.2", "YoungLizard7.1.3", --蓝BUFF
		"GreatWraith14.1.1",
		
--蓝色方		
		"AncientGolem1.1.1", "YoungLizard1.1.2", "YoungLizard1.1.3", --蓝BUFF
		"GiantWolf2.1.1", "Wolf2.1.2", "Wolf2.1.3",  --三狼
		"Wraith3.1.1", "LesserWraith3.1.2", "LesserWraith3.1.3", "LesserWraith3.1.4", --四鬼
		"LizardElder4.1.1", "YoungLizard4.1.2", "YoungLizard4.1.3", --红BUFF
		"Golem5.1.2", "SmallGolem5.1.1", -- 石头人
		"GreatWraith13.1.1"
		} 
end

function myCarry:LastHitOnTick()
	if self.ConfigMainMenu.LastHit or self.ConfigMainMenu.MixedMode  or self.ConfigMainMenu.LaneClear or self.show.ShowRangeConfig.minion or self.show.ShowRangeConfig.killable then
		self.enemyMinions:update()
		self.allyMinions:update()
	end
	if self.ConfigMainMenu.LastHit or self.ConfigMainMenu.LaneClear or self.show.ShowRangeConfig.killable  then
		self:LastHitupdate()
	end
	
	if self.ConfigMainMenu.LastHit then
		if self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.LastHitSkillfarm then
			self:LastSkillHitupdate()
		end
		self:LastHit()
	end
	
	if self.ConfigMainMenu.MixedMode then
		if self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.MixedModeSkillfarm then
			self:LastSkillHitupdate()
		end
		self:MixedMode()
	end
	
	if self.ConfigMainMenu.LaneClear then
		if self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.LaneClearSkillfarm then
			self:LastSkillHitupdate()
		end
		self:LaneClear()
	end
end

function myCarry:LastHitOnProcessSpell(unit, spell)
	if not self.isMelee and self:isAllyMinionInRange(unit) then
        for i,minion in pairs(self.enemyMinions.objects) do
            if ValidTarget(minion) and minion ~= nil and GetDistance(minion, spell.endPos) < 3 then
                if unit ~= nil and (self.minionInfo[unit.charName] or unit.type == "obj_AI_turret") then
					self.incomingDamage[unit.name] = self:getNewAttackDetails(unit, minion)
                end
				--if unit.type == "obj_AI_Turret" and unit.team == myHero.team then
					--if FarmMenu.Predict then
							--handleTurretShot(unit, minion)
					--end
				--end
            end
        end
    end
end

 
function myCarry:LastHitOnCreateObj(obj)
	for _, mob in pairs(self:getJungleMobs()) do
		if obj.name:find(mob) then
			table.insert(self.jungleMobs, obj)
		end
	end
end
 
function myCarry:LastHitOnDeleteObj(obj)
	for i, mob in pairs(self:getJungleMobs()) do
		if obj and obj.valid and mob and mob.valid and obj.name:find(mob.name) then
			table.remove(self.jungleMobs, i)
		end
	end
end

--[[function myCarry:LastHitOnDraw()
	if ConfigDisplayMenu.minion and self.enemyMinions.objects[1] and ValidTarget(self.enemyMinions.objects[1]) and not isMelee then
		DrawCircle(self.enemyMinions.objects[1].x, self.enemyMinions.objects[1].y, self.enemyMinions.objects[1].z, 100, 0x19A712)
	end
end--]]

function myCarry:isAllyMinionInRange(minion)
	return minion ~= nil and minion.team == myHero.team
		and (minion.type == "obj_AI_Minion" or minion.type == "obj_AI_Turret")
		and GetDistance(minion) <= 2000
end

function myCarry:getNewAttackDetails(source, target)
	return  {
			sourceName = source.name,
			targetName = target.name,
			damage = source:CalcDamage(target),
			started = GetTickCount(),
			origin = { x = source.x, z = source.z },
			delay = self:getMinionDelay(source),
			speed = self:getMinionProjSpeed(source),
			sourceType = source.type}
end 

function myCarry:getMinionDelay(minion)
	return ( minion.type == "obj_AI_Turret" and self.minionInfo.obj_AI_Turret.aaDelay or self.minionInfo[minion.charName].aaDelay )
end
 
function myCarry:getMinionProjSpeed(minion)
	return ( minion.type == "obj_AI_Turret" and self.minionInfo.obj_AI_Turret.projSpeed or self.minionInfo[minion.charName].projSpeed )
end

function myCarry:LastHitupdate()
	if not ValidTarget(self.killableMinion) then self.killableMinion = self:getKillableCreep(1) end
end

function myCarry:CastLastHitSkill(target)
	if self.LastHitSkill.spelltype == 5 then
		if GetDistance(target) < self.LastHitSkill.range and  not self.LastHitSkill.coll:GetMinionCollision(myHero,target)  then
				self.LastHitSkill:CastPos(target)
		end
	elseif self.LastHitSkill.spelltype == 6 then
		if GetDistance(target) < self.range then
			self.LastHitSkill:Cast()
			myHero:Attack(target)
		end
	else
		self.LastHitSkill:Cast(target)
	end
end

--[[function myCarry:GetStage()
	if self:timeToShoot() then return STAGE_NONE end
	if self:heroCanMove() then return STAGE_ORBWALK end
	return STAGE_WINDUP
end--]]

function myCarry:LastHit()
	if self:GetStage() == STAGE_WINDUP then return end
	if not (self.LastHitSkill and self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.LastHitSkillfarm) then
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
			
		elseif self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end
--[[		if ValidTarget(self.killableMinion) and self:timeToShoot() then
			self:attackEnemy(self.killableMinion)
			
		elseif self:heroCanMove() and self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end--]]
	else
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
			self.attackminion = self.killableMinion.networkID
		elseif ValidTarget(self.skillkillableMinion) and self.skillkillableMinion.networkID ~= self.attackminion and (self:GetStage() == STAGE_ORBWALK or (GetDistance(self.skillkillableMinion) > GetTrueRange())) and (self.ConfigFarmMenu.skillmana == nil or (self.ConfigFarmMenu.skillmana ~= nil and (myHero.mana/myHero.maxMana > self.ConfigFarmMenu.skillmana/100 )))  then
			self:CastLastHitSkill(self.skillkillableMinion)
		elseif self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end
			
			
--[[		if ValidTarget(self.killableMinion) and self:timeToShoot() then
			self:attackEnemy(self.killableMinion)
			self.attackminion = self.killableMinion.networkID
		elseif ValidTarget(self.skillkillableMinion) then
			if not (self:timeToShoot() and GetDistance(self.skillkillableMinion) < GetTrueRange()) and  self:heroCanMove() and (self.skillkillableMinion.networkID ~= self.attackminion) then
				self:CastLastHitSkill(self.skillkillableMinion)
			end
		elseif self:heroCanMove() and self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end	--]]
	end
end

function myCarry:getKillableCreep(iteration)
	if self.isMelee then return self:meleeLastHit() end
	local minion = self.enemyMinions.objects[iteration]
	if minion ~= nil then
		local distanceToMinion = GetDistance(minion)
		local predictedDamage = 0
		if distanceToMinion < GetTrueRange() then
			if self.ConfigFarmMenu.Predict then
				for l, attack in pairs(self.incomingDamage) do
					predictedDamage = predictedDamage + self:getPredictedDamage(l, minion, attack)
				end
			end
			
			local myDamage = myHero:CalcDamage(minion, myHero.totalDamage) + self:getBonusLastHitDamage(minion) + self:LastHitPassiveDamage(minion)
			if self.tianfu then
				myDamage = self.tianfu:GetAddDmg(myDamage, minion)
--[[				myDamage = (self.tianfu.MasteryConfig.sishen and myDamage * 1.05 or myDamage)
			else
				myDamage = (self.ConfigMasteryMenu.Executioner and myDamage * 1.05 or myDamage)--]]
			end
			
			myDamage = myDamage - 10
			--if minion.health - predictedDamage <= 0 then
					--return getKillableCreep(iteration + 1)
			if minion.health + 1.2 - predictedDamage < myDamage then
					return minion
			--elseif minion.health + 1.2 - predictedDamage < myDamage + (0.5 * predictedDamage) then
			--		return nil
			end
		end
	end
	return nil
end
--[[
function Mastery:GetAddDmg(dmg,target)
	local tianfujiachen = 1.0 
	if self.MasteryConfig.haojie then
		tianfujiachen = tianfujiachen + 0.03
	end
	
	if self.MasteryConfig.sishen ~= 0 and target.health <= target.maxHealth*(0.05+ 0.15*self.MasteryConfig.sishen)  then
		tianfujiachen = tianfujiachen + 0.05
	end
	
	if self.MasteryConfig.shuangrenjian then
		if myHero.range > 300 then
			tianfujiachen = tianfujiachen + 0.015
		else
			tianfujiachen = tianfujiachen + 0.02
		end
	end
	
	return dmg*tianfujiachen-self.MasteryConfig.baoliu
end--]]

function myCarry:getBonusLastHitDamage(minion)
	if PluginBonusLastHitDamage then 
		return PluginBonusLastHitDamage(minion)
	elseif self.BonusLastHitDamage then
		return self.BonusLastHitDamage(minion)
	else
		return 0
	end
end

function myCarry:LastHitPassiveDamage(minion)
		if PluginLastHitPassiveDamage then return PluginLastHitPassiveDamage(minion) end
        local bonus = 0
        if GetInventoryHaveItem(3153) then
                if ValidTarget(minion) then
                        bonus = minion.health / 20
                        if bonus >= 60 then
                                bonus = 60
                        end
                end
        end
		if self.tianfu then
			if self.tianfu.MasteryConfig.tufu then
				bonus = bonus + 2
			end
			
			bonus = bonus + (self.tianfu.zhoujian and bonus + (myHero.ap * 0.05) or 0)
		else
			bonus = bonus + (self.ConfigMasteryMenu.Butcher * 2)
			bonus = (self.ConfigMasteryMenu.Spellblade and bonus + (myHero.ap * 0.05) or 0)
		end

        return bonus
end

function myCarry:meleeLastHit()
	for _, minion in pairs(self.enemyMinions.objects) do
		local aDmg = getDmgcn("AD", minion, myHero)+ self:getBonusLastHitDamage(minion)
		if GetDistance(minion) <= (myHero.range + 75) then
			if minion.health < aDmg then
				return minion
            end            
        end
	end
end

function myCarry:getPredictedDamage(counter, minion, attack)
	if not self:minionSpellStillViable(attack) then
		self.incomingDamage[counter] = nil
	elseif self:isSameMinion(minion, self:getEnemyMinion(attack.targetName)) then
		local myTimeToHit = self:getTimeToHit(minion, self.projSpeed)
		self.minionTimeToHit = self:getMinionTimeToHit(minion, attack)
		if GetTickCount() >= (attack.started + self.minionTimeToHit) then
			self.incomingDamage[counter] = nil
		elseif GetTickCount() + myTimeToHit > attack.started + self.minionTimeToHit then
			return attack.damage
		end
	end
	return 0
end

function myCarry:minionSpellStillViable(attack)
	if attack == nil then return false end
	local sourceMinion = self:getAllyMinion(attack.sourceName)
	local targetMinion = self:getEnemyMinion(attack.targetName)
	if sourceMinion == nil or targetMinion == nil then return false end
	if sourceMinion.dead or targetMinion.dead or GetDistance(sourceMinion, attack.origin) > 3 then return false else return true end
end

function myCarry:getAllyMinion(name)
	for i, minion in pairs(self.allyMinions.objects) do
		if minion ~= nil and minion.valid and minion.name == name then
			return minion
		end
	end
	return nil
end

function myCarry:getEnemyMinion(name)
	for i, minion in pairs(self.enemyMinions.objects) do
		if minion ~= nil and ValidTarget(minion) and minion.name == name then
			return minion
		end
	end
	return nil
end

function myCarry:isSameMinion(minion1, minion2)
	return (minion1.networkID == minion2.networkID) 
end

function myCarry:getTimeToHit(enemy, speed)
	return (( GetDistance(enemy) / speed ) + GetLatency()/2)
end

function myCarry:getMinionTimeToHit(minion, attack)
	local sourceMinion = self:getAllyMinion(attack.sourceName)
	return ( attack.speed == 0 and ( attack.delay ) or ( attack.delay + GetDistance(sourceMinion, minion) / attack.speed ) )
end


function myCarry:MixedMode()
	if self:GetStage() == STAGE_WINDUP then return end
--[[	local myMixTarget = GetTarget()
	if myMixTarget~= nil and (myMixTarget.type == "obj_AI_Hero" or myMixTarget.type == "obj_AI_Minion") and ValidTarget(myMixTarget) then 
		self.AutoCarryTarget = myMixTarget
	else
		self.AutoCarryTarget = self.OrbwalkTarget.target
	end


	if self.AutoCarryTarget ~= nil and self:EnemyInRange(self.AutoCarryTarget) then
		if self:timeToShoot() then
			self:attackEnemy(self.AutoCarryTarget)
        elseif self:heroCanMove() then
            self:moveToCursor()
		end--]]
	if self.OrbwalkTarget.target ~= nil and self:EnemyInRange(self.OrbwalkTarget.target) then
		if self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.OrbwalkTarget.target)
        else
            self:moveToCursor()
		end
	else
		if not (self.LastHitSkill and self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.MixedModeSkillfarm) then
			if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
				self:attackEnemy(self.killableMinion)
			
			elseif self.ConfigFarmMenu.moveLastHit then
				self:moveToCursor()
			end
			
		else
			if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
				self:attackEnemy(self.killableMinion)
				self.attackminion = self.killableMinion.networkID
			elseif ValidTarget(self.skillkillableMinion) and self.skillkillableMinion.networkID ~= self.attackminion and (self:GetStage() == STAGE_ORBWALK or (GetDistance(self.skillkillableMinion) > GetTrueRange())) and (self.ConfigFarmMenu.skillmana == nil or (self.ConfigFarmMenu.skillmana ~= nil and (myHero.mana/myHero.maxMana > self.ConfigFarmMenu.skillmana/100 )))  then
				self:CastLastHitSkill(self.skillkillableMinion)
			elseif self.ConfigFarmMenu.moveLastHit then
				self:moveToCursor()
			end
		end
	end
	
--[[	if self.OrbwalkTarget.target ~= nil and self:EnemyInRange(self.OrbwalkTarget.target) then
		if self:timeToShoot() then
			self:attackEnemy(self.OrbwalkTarget.target)
        elseif self:heroCanMove() then
            self:moveToCursor()
		end
	else
		if ValidTarget(self.killableMinion) and self:timeToShoot() then
			self:attackEnemy(self.killableMinion)
		elseif self:heroCanMove() and self.ConfigFarmMenu.moveMixed then
			self:moveToCursor()
		end
	end--]]
end

function myCarry:LastHit()
	if self:GetStage() == STAGE_WINDUP then return end
	if not (self.LastHitSkill and self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.LastHitSkillfarm) then
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
			
		elseif self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end
--[[		if ValidTarget(self.killableMinion) and self:timeToShoot() then
			self:attackEnemy(self.killableMinion)
			
		elseif self:heroCanMove() and self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end--]]
	else
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
			self.attackminion = self.killableMinion.networkID
		elseif ValidTarget(self.skillkillableMinion) and self.skillkillableMinion.networkID ~= self.attackminion and (self:GetStage() == STAGE_ORBWALK or (GetDistance(self.skillkillableMinion) > GetTrueRange())) and (self.ConfigFarmMenu.skillmana == nil or (self.ConfigFarmMenu.skillmana ~= nil and (myHero.mana/myHero.maxMana > self.ConfigFarmMenu.skillmana/100 )))  then
			self:CastLastHitSkill(self.skillkillableMinion)
		elseif self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end
			
			
--[[		if ValidTarget(self.killableMinion) and self:timeToShoot() then
			self:attackEnemy(self.killableMinion)
			self.attackminion = self.killableMinion.networkID
		elseif ValidTarget(self.skillkillableMinion) then
			if not (self:timeToShoot() and GetDistance(self.skillkillableMinion) < GetTrueRange()) and  self:heroCanMove() and (self.skillkillableMinion.networkID ~= self.attackminion) then
				self:CastLastHitSkill(self.skillkillableMinion)
			end
		elseif self:heroCanMove() and self.ConfigFarmMenu.moveLastHit then
			self:moveToCursor()
		end	--]]
	end
end


--[[function myCarry:LaneClear()
	if ValidTarget(self.killableMinion) and self:timeToShoot() then
		self:attackEnemy(self.killableMinion)
	else
		local tMinion = self:getHighestMinion()
		if tMinion and ValidTarget(tMinion) and self:timeToShoot() then
			self.pluginMinion = tMinion
			self:attackEnemy(tMinion)
		else
			if self.ConfigFarmMenu.JungleFarm then
				local tMinion = self:getJungleMinion()
				if tMinion and ValidTarget(tMinion) and self:timeToShoot() then
					self.pluginMinion = tMinion
					self:attackEnemy(tMinion)
				elseif self:heroCanMove() and self.ConfigFarmMenu.moveClear then
					self:moveToCursor()
				end	
			elseif self:heroCanMove() and self.ConfigFarmMenu.moveClear then
				self:moveToCursor()
			end
		end
	end
end--]]



function myCarry:LaneClear()
	if self:GetStage() == STAGE_WINDUP then return end
	if not (self.LastHitSkill and self.ConfigFarmMenu.skillfarm and self.ConfigFarmMenu.LaneClearSkillfarm) then
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
		else
			self.tMinion = self:getHighestMinion()
			if self.tMinion and ValidTarget(self.tMinion) and self:GetStage() == STAGE_NONE then
				self.pluginMinion = self.tMinion
				self:attackEnemy(self.tMinion)
			else
				if self.ConfigFarmMenu.JungleFarm then
					self.jMinion = self:getJungleMinion()
					if self.jMinion and ValidTarget(self.jMinion) and self:GetStage() == STAGE_NONE then
						self.pluginMinion = self.jMinion
						self:attackEnemy(self.jMinion)
					elseif self.ConfigFarmMenu.moveClear then
						self:moveToCursor()
					end	
				elseif self.ConfigFarmMenu.moveClear then
					self:moveToCursor()
				end
			end
		end
	else
		if ValidTarget(self.killableMinion) and self:GetStage() == STAGE_NONE then
			self:attackEnemy(self.killableMinion)
			self.attackminion = self.killableMinion.networkID
		elseif ValidTarget(self.skillkillableMinion) and self.skillkillableMinion.networkID ~= self.attackminion and (self:GetStage() == STAGE_ORBWALK or (GetDistance(self.skillkillableMinion) > GetTrueRange())) and (self.ConfigFarmMenu.skillmana == nil or (self.ConfigFarmMenu.skillmana ~= nil and (myHero.mana/myHero.maxMana > self.ConfigFarmMenu.skillmana/100 )))  then
			self:CastLastHitSkill(self.skillkillableMinion)
		else
			self.tMinion = self:getHighestMinion()
			if self.tMinion and ValidTarget(self.tMinion) and self:GetStage() == STAGE_NONE then
				self.pluginMinion = self.tMinion
				self:attackEnemy(self.tMinion)
			else
				if self.ConfigFarmMenu.JungleFarm then
					self.jMinion = self:getJungleMinion()
					if self.jMinion and ValidTarget(self.jMinion) and self:GetStage() == STAGE_NONE then
						self.pluginMinion = self.jMinion
						self:attackEnemy(self.jMinion)
					elseif self.ConfigFarmMenu.moveClear then
						self:moveToCursor()
					end	
				elseif self.ConfigFarmMenu.moveClear then
					self:moveToCursor()
				end
			end
		end 
	end
end

function myCarry:getSkillKillableCreep(iteration)
	if not self.LastHitSkill:GetStat() then return nil end
--	if self.isMelee then return self:meleeLastHit() end
	local minion = self.enemyMinions.objects[iteration]
	if minion ~= nil then
		local distanceToMinion = GetDistance(minion)
		local predictedDamage = 0
		if distanceToMinion < self.LastHitSkill.range then
			if self.ConfigFarmMenu.Predict then
				for l, attack in pairs(self.incomingDamage) do
					predictedDamage = predictedDamage + self:getPredictedDamage(l, minion, attack)
				end
			end
			
			local myDamage = getDmgcn(self.LastHitSkill:Name(), minion, myHero)
			if self.tianfu then
				myDamage = self.tianfu:GetAddDmg(myDamage,minion)
--[[				myDamage = (self.tianfu.MasteryConfig.haojie and myDamage * 
				myDamage = (self.tianfu.MasteryConfig.sishen and myDamage * 1.05 or myDamage)--]]
			else
				myDamage = (self.ConfigMasteryMenu.Executioner and myDamage * 1.05 or myDamage)
			end
			
			myDamage = myDamage - 10
			--if minion.health - predictedDamage <= 0 then
					--return getKillableCreep(iteration + 1)
			if minion.health + 1.2 - predictedDamage < myDamage then
					return minion
			--elseif minion.health + 1.2 - predictedDamage < myDamage + (0.5 * predictedDamage) then
			--		return nil
			end
		end
	end
	return nil
end

function myCarry:LastSkillHitupdate()
	 self.skillkillableMinion = self:getSkillKillableCreep(1)
end

function myCarry:getJungleMinion()
	table.sort(self.jungleMobs, function(x, y)
    local aDmg = x:CalcDamage(myHero,x.totalDamage)
    local bDmg = y:CalcDamage(myHero,y.totalDamage)
    if aDmg == bDmg then return x.health < y.health end
    return aDmg > bDmg
	end)
	for _, mob in pairs(self.jungleMobs) do
		if ValidTarget(mob) and GetDistance(mob) <= GetTrueRange() then return mob end
	end
	return nil
end

function myCarry:getHighestMinion()
	if GetTarget() ~= nil then
		local currentTarget = GetTarget()
		local validTarget = false
		validTarget = ValidTarget(currentTarget, GetTrueRange(), player.enemyTeam)
		if validTarget and (currentTarget.type == "obj_BarracksDampener" or currentTarget.type == "obj_HQ" or currentTarget.type == "obj_AI_Turret") then
			return currentTarget
		end
	end

	local highestHp = {obj = nil, hp = 0}
	for _, tMinion in pairs(self.enemyMinions.objects) do
		if GetDistance(tMinion) <= GetTrueRange() and tMinion.health > highestHp.hp then
				highestHp = {obj = tMinion, hp = tMinion.health}
		end
	end
	return highestHp.obj
end

function myCarry:attackedSuccessfully()
    self.projAt = GetTickCount()
	if self.OnAttacked then self.OnAttacked() end
end

function myCarry:TimeToSkill()
	return GetTickCount() < self.projAt + 400
end


--[[function myCarry:setMovement()
	if GetDistance(mousePos) <= self.MainMenuConfig.HoldZone and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.LastHit or AutoCarry.MainMenu.MixedMode or AutoCarry.MainMenu.LaneClear) then
		myHero:HoldPosition()
		if self.CanMove then
			self.CanMove = false
		end
	else
		self.CanMove = true
	end
end--]]






-----------------------------------------------走A结束---------------------------------------------------------------------

local Predelay= 0.15

local _gameHeroes, _gameAllyCount, _gameEnemyCount = {}, 0, 0

local function _gameHeroes__init()
    if #_gameHeroes == 0 then
        _gameAllyCount, _gameEnemyCount = 0, 0
        for i = 1, heroManager.iCount do
            local hero = heroManager:getHero(i)
            if hero ~= nil and hero.valid then
                if hero.team == player.team then
                    _gameAllyCount = _gameAllyCount + 1
                    table.insert(_gameHeroes, { hero = hero, index = i, tIndex = _gameAllyCount, ignore = false, priority = 1, enemy = false })
                else
                    _gameEnemyCount = _gameEnemyCount + 1
                    table.insert(_gameHeroes, { hero = hero, index = i, tIndex = _gameEnemyCount, ignore = false, priority = 1, enemy = true })
                end
            end
        end
    end
end

local function _gameHeroes__index(target, assertText, enemyTeam)
    local assertText = assertText or ""
    local enemyTeam = (enemyTeam ~= false)
    if type(target) == "string" then
        for _, _gameHero in ipairs(_gameHeroes) do
            if _gameHero.hero.charName == target and (_gameHero.hero.team ~= player.team) == enemyTeam then
                return _gameHero.index
            end
        end
    elseif type(target) == "number" then
        return target
    else
        assert(type(target.networkID) == "number", assertText .. ": wrong argument types (<charName> or <heroIndex> or <hero> or nil expected)")
        return _gameHeroes__index(target.charName, assertText, (target.team ~= player.team))
    end
end


local _TargetPredictionLuacn__tick = 0
local __TargetPredictionLuacn__OnTick
local function TargetPredictionLuacn__Onload()
    if not __TargetPredictionLuacn__OnTick then
        function __TargetPredictionLuacn__OnTick()
            local osTime = os.clock()
            if osTime - _TargetPredictionLuacn__tick > Predelay then
                _TargetPredictionLuacn__tick = osTime
                for _, _enemyHero in ipairs(_gameHeroes) do
                    local hero = _enemyHero.hero
                    if hero.dead then
                        _enemyHero.prediction = nil
                    elseif hero.visible then
                        if _enemyHero.prediction then
                            local deltaTime = osTime - _enemyHero.prediction.lastUpdate
                            _enemyHero.prediction.movement = (Vector(hero) - _enemyHero.prediction.position) / deltaTime
                            _enemyHero.prediction.healthDifference = (hero.health - _enemyHero.prediction.health) / deltaTime
                            _enemyHero.prediction.health = hero.health
                            _enemyHero.prediction.position = Vector(hero)
                            _enemyHero.prediction.lastUpdate = osTime
                        else
                            _enemyHero.prediction = { position = Vector(hero), lastUpdate = osTime, minions = false, health = hero.health }
                        end
                    end
                end
            end
        end

        AddTickCallback(__TargetPredictionLuacn__OnTick)
    end
end

class'TargetPredictionLuacn'
function TargetPredictionLuacn:__init(range, proj_speed, delay, widthCollision, smoothness,from)
    assert(type(range) == "number", "TargetPredictionLuacn: wrong argument types (<number> expected for range)")
    _gameHeroes__init()
    TargetPredictionLuacn__Onload()
    self.range = range or 0
    self.proj_speed = proj_speed or math.huge
    self.delay = delay or 0
    self.width = widthCollision
    self.smoothness = smoothness
	self.from = from or myHero
    if self.width then
        self.minionTable = minionManager(MINION_ENEMY, self.range + 300)
    end
end

function TargetPredictionLuacn:SetMinionCollisionType(minionType)
    if minionType then
        self.minionTable = minionManager(minionType, self.range + 300)
    else
        self.minionTable = nil
    end
end

function TargetPredictionLuacn:GetPrediction(target)
    assert(target ~= nil, "GetPrediction: wrong argument types (<target> expected)")
    local index = _gameHeroes__index(target, "GetPrediction")
    if not index then return end
    local selected = _gameHeroes[index].hero
    if self.minionTable then self.minionTable:update() end
    if _gameHeroes[index].prediction and _gameHeroes[index].prediction.movement then
        if index ~= self.target then
            self.nextPosition = nil
            self.target = index
        end
        local osTime = os.clock()
        local delay = self.delay / 1000
        local proj_speed = self.proj_speed and self.proj_speed * 1000
        if GetDistanceSqr(selected) < (self.range + 300) ^ 2 then
            if osTime - (_gameHeroes[index].prediction.calculateTime or 0) > 0 then
                local latency = (GetLatency() / 1000) or 0
                local PositionPrediction
                if selected.visible then
                    PositionPrediction = (_gameHeroes[index].prediction.movement * (delay + latency)) + selected
                elseif osTime - _gameHeroes[index].prediction.lastUpdate < 3 then
                    PositionPrediction = (_gameHeroes[index].prediction.movement * (delay + latency + osTime - _gameHeroes[index].prediction.lastUpdate)) + _gameHeroes[index].prediction.position
                else _gameHeroes[index].prediction = nil return
                end
                local t = 0
                if proj_speed and proj_speed > 0 then
                    local a, b, c = PositionPrediction, _gameHeroes[index].prediction.movement, Vector(self.from)
                    local d, e, f, g, h, i, j, k, l = (-a.x + c.x), (-a.z + c.z), b.x * b.x, b.z * b.z, proj_speed * proj_speed, a.x * a.x, a.z * a.z, c.x * c.x, c.z * c.z
                    local t = (-(math.sqrt(-f * (l - 2 * c.z * a.z + j) + 2 * b.x * b.z * d * e - g * (k - 2 * c.x * a.x + i) + (k - 2 * c.x * a.x + l - 2 * c.z * a.z + i + j) * h) - b.x * d - b.z * e)) / (f + g - h)
                    PositionPrediction = (_gameHeroes[index].prediction.movement * t) + PositionPrediction
                end
                if self.smoothness and self.smoothness < 100 and self.nextPosition then
                    self.nextPosition = (PositionPrediction * ((100 - self.smoothness) / 100)) + (self.nextPosition * (self.smoothness / 100))
                else
                    self.nextPosition = PositionPrediction:clone()
                end
                if GetDistanceSqr(PositionPrediction) < (self.range) ^ 2 then
                    --update next Health
                    self.nextHealth = selected.health + (_gameHeroes[index].prediction.healthDifference or selected.health) * (t + self.delay + latency)
                    --update minions collision
                    self.minions = false
                    if self.width and self.minionTable then
                        self.minions = GetMinionCollision(self.from, PositionPrediction, self.width, self.minionTable.objects)
                    end
                else return
                end
            end
            return self.nextPosition, self.minions, self.nextHealth
        end
    end
end

function TargetPredictionLuacn:ShowPre(target,radi,color,minion)
	local pos = self:GetPrediction(target)
	if pos then
		if not minion or minion == nil then
			DrawCircle(pos.x,pos.y,pos.z,radi,color)
		else
			local col = minionCol(self.range,self.width)
			if not col:GetMinionCollision() then
				DrawCircle(pos.x,pos.y,pos.z,radi,color)
			end
		end
	end
end


class'minionCol'
function minionCol:__init(range, width,from)
	self.from = from or myHero
	self.range = range
	self.width = width
	self.enemyMinions = minionManager(MINION_ENEMY, 2000, self.from, MINION_SORT_HEALTH_ASC)
end

function minionCol:GetMinionCollision(predic)
	self.enemyMinions:update()
	for _, minionObjectE in pairs(self.enemyMinions.objects) do
		if predic ~= nil and GetDistance(minionObjectE) < self.range then
			ex = self.from.x
			ez = self.from.z
			tx = predic.x
			tz = predic.z
			dx = ex - tx
			dz = ez - tz
			if dx ~= 0 then
				m = dz/dx
				c = ez - m*ex
			end
			mx = minionObjectE.x
			mz = minionObjectE.z
			if m == nil then return false end
			distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
			if distanc < self.width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
				return true
			end
		end
	end
	return false
end

class'PrintVer'
function PrintVer:__init(name,ver)
	self.name = name
	self.ver = ver
end

function PrintVer:Print()
	PrintChat("<font color='#FF3333'> >>  "..self.name.."\232\132\154\230\156\172\229\138\160\232\189\189\230\136\144\229\138\159  \231\137\136\230\156\172\229\143\183 : "..self.ver.." </font>")
	PrintChat("<font color='#FFCC00'> >>  \230\156\172\232\132\154\230\156\172\231\148\177 www.luacn.net \232\174\186\229\157\155\229\136\182\228\189\156 </font>")
	PrintChat("<font color='#FF88E7'> >>  \230\155\180\229\164\154\232\132\154\230\156\172\232\175\183\232\174\191\233\151\174 www.luacn.net </font>")
	PrintChat("<font color='#88FF88'> >>  \229\136\182\228\189\156\232\128\133: Yooooooooo </font>")
end


class'mySpell'

SPELL_TARGETED_SELF = 0
SPELL_TARGETED = 1
SPELL_LINEAR = 2
SPELL_CIRCLE = 3
SPELL_CONE = 4
SPELL_LINEAR_COL = 5
SPELL_SELF = 6

function mySpell:__init(skill,cost,range,skilltype,spellname,who,spellType,speed,delay,width,smoothness,VIP,skillname,Orb,nopre)
	self.Orb = Orb or nil
	self.VIP = VIP and VIP_USER
	self.nopre = nopre or nil
	self.skill = skill
	self.cost = cost
	self.skilltype = skilltype or 1
	self.spellname = spellname or nil
	self.who = who or myHero
	self.range = range or 0
--	self.hitrange = 0
	self.addrange ={}
	self.added = false
	self.spelltype = spellType or SPELL_SELF
	
	self.name = skillname or self:Name()
	
	self.speed = speed or math.huge
	self.speedorg = self.speed
	
	self.delay = delay or 0
	self.delayorg = self.delay
	
	self.width = width
	self.widthorg = self.width
	
	self.smoothness = smoothness or 0
	self.smoothnessorg = self.smoothness
	
	self.spellData = self.who:GetSpellData(self.skill)
--[[	if self.VIP then
		self.Dash = false
		self.dashendT = 0
		self.dashendPos = nil
		self.dashtarget = nil
	end--]]
	
	if (self.spelltype == SPELL_LINEAR or self.spelltype == SPELL_LINEAR_COL) then
		if skillname then
			self.predConfig = scriptConfig(CNCharName(myHero.charName,1).." V2:"..skillname.."预判参数", "Pred"..myHero.charName..skillname)
		else
			self.predConfig = scriptConfig(CNCharName(myHero.charName,1).." V2:"..self:Name().."预判参数", "Pred"..myHero.charName..self:Name())
		end
		if self.VIP then
--			self.predConfig:addParam("enablee","应用以下更改(空格)", SCRIPT_PARAM_ONKEYDOWN, false, 32) 

			self.predConfig:addParam("mode","< Yoo改进版官方普通预判 >", SCRIPT_PARAM_INFO, "")
			self.predConfig:addParam("VIP", "预判模式切换",SCRIPT_PARAM_SLICE, 1, 1, 3, 0)
--			self.predConfig:addParam("NoMiss", "(3号预判选项)提高命中率模式", SCRIPT_PARAM_ONOFF, false)


			self.VIPmode = self.predConfig.VIP
		else
			self.VIPmode = 1
		end
		self:SetPred(self.VIPmode)
		
		
		self.predConfig:addParam("canshu","-- 预判参数设置 --", SCRIPT_PARAM_INFO, "")
		self.predConfig:addParam("NoADV", "不使用以下设置,用默认数据", SCRIPT_PARAM_ONOFF, true)
		if self.speed >= 0.1 and self.speed <= 10 then
			self.predConfig:addParam("speed", "速度",  SCRIPT_PARAM_SLICE, self.speed, 0.1, 10, 1)
		else
			self.predConfig:addParam("speed", "速度",  SCRIPT_PARAM_SLICE, 10, 0.1, 10, 1)
		end
		
		if self.delay >= 10 and self.delay <= 2000 then
			self.predConfig:addParam("delay", "延迟",  SCRIPT_PARAM_SLICE, self.delay, 10, 2000, 0)
		else
			self.predConfig:addParam("delay", "延迟",  SCRIPT_PARAM_SLICE, 2000, 10, 2000, 0)
		end
		
		if self.spelltype == SPELL_LINEAR_COL then
			if self.width >= 10 and self.width <= 500 then
				self.predConfig:addParam("Width", "宽度",  SCRIPT_PARAM_SLICE, self.width, 10, 500, 0)
			else
				self.predConfig:addParam("Width", "宽度",  SCRIPT_PARAM_SLICE, 500, 10, 500, 0)
			end
		end
		
		if self.smoothness >= 0 and self.smoothness <= 100 then
			self.predConfig:addParam("smoothness", "(1号预判选项)预判点平滑度",  SCRIPT_PARAM_SLICE, self.smoothness, 0, 100, 0)
		else
			self.predConfig:addParam("smoothness", "(1号预判选项)预判点平滑度",  SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
		end
		
--		PrintChat(""..self.predConfig.VIP)
		if spellType == SPELL_LINEAR_COL then
			if self.VIP then
				self.coll = Collision(self.range, self.speed*1000, self.delay/1000, self.width) 
			else
				self.coll = minionCol(self.range,self.width,self.who)
			end
		end
	end

	self.change = false
	AddTickCallback(function() self:OnTick() end)
end

function mySpell:SetPred(mode,chat)
	if type(mode) ~= "number" then 
		return
	end
	if mode == 1 then
		self.pred = TargetPredictionLuacn(self.range, self.speed, self.delay, self.width,self.smoothness,self.who)
	elseif mode == 2 then
		self.pred = TargetPredictionVIP(self.range,self.speed*1000,self.delay/1000,self.width,self.who) 
	elseif mode == 3 then
		self.pred = ProdictManager.GetInstance():AddProdictionObject(self.skill, self.range, self.speed*1000, self.delay/1000, self.width, self.who)
--[[		self:NOmissmode(self.predConfig.NoMiss)
		self.NoMissorg = self.predConfig.NoMiss--]]
	end
	if chat then
--		PrintChat("\233\162\132\229\136\164\232\174\190\231\189\174\229\183\178\231\148\159\230\149\136\33")
		MyPrintAlert("技能:"..self.name.."的预判设置已成功更改", 2, 255, 255, 255)
	end
end

function mySpell:OnTick()
--	PrintChat("speed"..self.speed)
	if self.predConfig ~= nil then
--		PrintChat(""..type(self.pred))
		if IsKeyDown(16) and self.VIP then
			if self.predConfig.VIP == 1 then
				self.predConfig._param[1].text = "< Yoo改进版官方普通预判 >"
			elseif self.predConfig.VIP == 2 then
				self.predConfig._param[1].text = "< 官方VIP预判 >"
			elseif self.predConfig.VIP == 3 then
				self.predConfig._param[1].text = "< K大改进版官方VIP预判 >"
			end
		end
		self:PredUpdate()
	end
	
	

end

function mySpell:PredUpdate()
	if self.predConfig.NoADV then
		if self.speed ~= self.speedorg then
			self.change = true
			self.speed = self.speedorg
		end
		
		if self.delay ~= self.delayorg then
			self.change = true
			self.delay = self.delayorg
		end
		
		if self.width ~= self.widthorg then
			self.change = true
			self.width = self.widthorg
		end
		
		if self.smoothness ~= self.smoothnessorg then
			self.change = true
			self.smoothness = self.smoothnessorg
		end
		
	else
		if self.speed ~= self.predConfig.speed then
			self.change = true
			self.speed = self.predConfig.speed
		end
		
		if self.delay ~= self.predConfig.delay then
			self.change = true
			self.delay = self.predConfig.delay
		end
		
		if spellType == SPELL_LINEAR_COL then
			if self.width ~= self.predConfig.width then
				self.change = true
				self.width = self.predConfig.width
			end
		end
		
		if self.smoothness ~= self.predConfig.smoothness then
			self.change = true
			self.smoothness = self.predConfig.smoothness
		end
	end
	if self.VIP then
		if self.VIPmode ~= self.predConfig.VIP then
			self.change = true
			self.VIPmode = self.predConfig.VIP
		end
--[[		if self.VIPmode == 3 then
			if type(self.NoMissorg) == "boolean" and type(self.predConfig.NoMiss) == "boolean" and self.NoMissorg ~= self.predConfig.NoMiss then
				self.change = true
			end
		end--]]
	end
	
	if self.change --[[and self.predConfig.enablee--]] then
		self:SetPred(self.VIPmode,true)
		
--[[		if self.VIP then
			if self.predConfig.VIP == 1 then
				self.pred = TargetPredictionLuacn(self.range, self.speed, self.delay, self.width,self.smoothness,self.who)
			elseif self.predConfig.VIP == 2 then
				self.pred = TargetPredictionVIP(self.range,self.speed*1000,self.delay/1000,self.width,self.who) 
			elseif self.predConfig.VIP == 3 then
				self.pred = ProdictManager.GetInstance():AddProdictionObject(self.skill, self.range, self.speed*1000, self.delay/1000, self.width, self.who, function() end)
			end
			PrintChat("\233\162\132\229\136\164\232\174\190\231\189\174\229\183\178\231\148\159\230\149\136\33")
		else
			self.pred = TargetPredictionLuacn(self.range, self.speed, self.delay, self.width,smoothness,self.who)
			PrintChat("\233\162\132\229\136\164\232\174\190\231\189\174\229\183\178\231\148\159\230\149\136\33")
		end
		if self.VIPmode == 3 then
			self:NOmissmode(self.predConfig.NoMiss)
			PrintChat("\51\229\143\183\233\162\132\229\136\164\230\168\161\229\188\143\232\174\190\231\189\174\229\183\178\231\148\159\230\149\136\33")
		end--]]
		self.change = false
	end
end
	

function mySpell:AddHitChance(chance)
	self.HitChance = chance
end

function mySpell:Name()
	if self.skill == _Q then return "Q" 
	elseif self.skill == _W then return "W"
	elseif self.skill == _E then return "E"
	elseif self.skill == _R then return "R"
	end
end

--[[function mySpell:NOmissmode(sw)
	for I = 1, heroManager.iCount do
		local hero = heroManager:GetHero(I)
		if hero.team ~= myHero.team then
			self.pred:CanNotMissMode(sw, hero)
		end
	end
end--]]

function mySpell:update(range)
	if range then 
		self.range = range
	end
end

function mySpell:GetStat()
	if self.skilltype == 1 then
		return ((self.spellData.level > 0) and ((self.cost~= nil and (self.who.mana >= self.cost)) or (self.cost == nil))  and (self.who:CanUseSpell(self.skill) == READY))
	elseif self.skilltype == 2 then
		return (self.spellData.name == self.spellname and self.spellData.level > 0 and ((self.cost~=nil and self.who.mana >= self.cost) or self.cost == nil) and self.who:CanUseSpell(self.skill) == READY)
	elseif self.skilltype == 3 then
		return (self.skill ~= nil and self.who:CanUseSpell(self.skill) == READY)
	end
end

function mySpell:Preupdate(moren,range,speed,delay,width,smoothness,who)
	if moren then self.pred = TargetPredictionLuacn(self.range, self.speed, self.delay, self.width,smoothness,self.who) return end
	self.pred = TargetPredictionLuacn(range, speed, delay, width,smoothness, who)
end

function mySpell:Draw(color)
	if self:GetStat() then
		DrawCircle(self.who.x, self.who.y, self.who.z, self.range, color~= nil and color or self:GetColor())
		if self.added then
			for i,range in pairs(self.addrange) do
				DrawCircle(myHero.x, myHero.y, myHero.z, range, color~= nil and color or self:GetColor())
			end
		end
	end
end

function mySpell:Addrange(range)
	table.insert(self.addrange,range)
	self.added = true
end

function mySpell:GetColor()
	if self.skill == _Q then return 0x87CEFA 
	elseif self.skill == _W then return 0xFFFF00
	elseif self.skill == _E then return 0x19A712
	elseif self.skill == _R then return 0x540069
	else return 0x655600
	end
end

function mySpell:Data()
	return self.who:GetSpellData(self.skill)
end

function mySpell:GetPrediction(target)
	if ValidTarget(target) then return self.pred:GetPrediction(target) end
end

function mySpell:GetCollision(spellPos,from)
	if not (spellPos and spellPos.x and spellPos.z) then return nil end
	return self.coll:GetMinionCollision(from,spellPos) and self.VIP or self.coll:GetMinionCollision(spellPos)
end

function mySpell:Cast(target,hasdetect,heroblock,radius,GoPred)
	if self.Orb and not self.Orb:TimeToSkill() then return false end
	if self.spelltype == SPELL_LINEAR or self.spelltype == SPELL_LINEAR_COL then
--		self.hitrange = self.range + getHitBoxRadius(target)/2
	end
	if hasdetect then
		if self.spelltype == SPELL_SELF or self.spelltype == SPELL_TARGETED_SELF then
			CastSpell(self.skill)
			return true
		elseif self.spelltype == SPELL_TARGETED or spelltype == SPELL_TARGETED_FRIENDLY then
			CastSpell(self.skill, target)
			return true
		elseif self.spelltype == SPELL_CONE then
			CastSpell(self.skill, target.x, target.z)
			return true
		elseif self.spelltype == SPELL_CIRCLE then
			local spellPos = GetAoESpellPosition(radius, target, self.delay)
--[[			if mode == 1 then
				local spellPos = GetAoESpellPosition(radius, nil, self.range)
			else
				local spellPos = GetAoESpellPosition(radius, target, self.range)
			end--]]
			if spellPos and GetDistance(spellPos) < self.range then
				CastSpell(self.skill, spellPos.x, spellPos.z)
				return true
			else
				CastSpell(self.skill, target.x, target.z)
				return true
			end	
		elseif self.spelltype == SPELL_LINEAR then
			local spellPos = self.pred:GetPrediction(target)
			if spellPos == nil then
--				PrintChat(self.name.."Posnil")
			end
			if spellPos and GetDistance(spellPos,self.who) < self.range then
				CastSpell(self.skill, spellPos.x, spellPos.z)
				return true
			end
		elseif self.spelltype == SPELL_LINEAR_COL then
--[[			if self.VIPmode == 3 then
				local spellPos = self.pred:GetPrediction(target)
			else
				local spellPos = self:CanSpell(target,true) and heroblock or self:CanSpell(target)
			end--]]
			local spellPos = self:CanSpell(target,true) and heroblock or self:CanSpell(target)
			if spellPos ~= nil and GetDistance(spellPos) <= self.range then
				CastSpell(self.skill, spellPos.x, spellPos.z)
				return true
			end
		end
	else
		if not self:GetStat() then
			return false
		end
		if self.spelltype == SPELL_SELF then
			CastSpell(self.skill)
			return true
		elseif self.spelltype == SPELL_TARGETED_SELF then
			if ValidTarget(target, self.range) then
				CastSpell(self.skill)
			return true 
			end
		elseif self.spelltype == SPELL_TARGETED then
			if ValidTarget(target, self.range) then
				CastSpell(self.skill, target)
				return true
			end
		elseif self.spelltype == SPELL_TARGETED_FRIENDLY then
			if target ~= nil and not target.dead and GetDistance(target) < self.range and target.team == self.who.team then
				CastSpell(self.skill, target)
				return true
			end
		elseif self.spelltype == SPELL_CONE then
			if ValidTarget(target, self.range) then
				CastSpell(self.skill, target.x, target.z)
				return true
			end
		elseif self.spelltype == SPELL_CIRCLE then
			if ValidTarget(target, self.range) then
				local spellPos = GetAoESpellPosition(radius, target, self.delay)
--[[				if mode == 1 then
					local spellPos = GetAoESpellPosition(radius, nil, self.delay)
				else
					local spellPos = GetAoESpellPosition(radius, target, self.delay)
				end--]]
				if spellPos and GetDistance(spellPos) < self.range then
--					PrintChat("pos!")
					CastSpell(self.skill, spellPos.x, spellPos.z)
				else
--					PrintChat("posnimei!")
					CastSpell(self.skill, target.x, target.z)
				end
			end		
		elseif self.spelltype == SPELL_LINEAR then
			if ValidTarget(target) then
				local spellPos = self.pred:GetPrediction(target)
				if spellPos and GetDistance(spellPos,self.who) < self.range then

					if self.HitChance and self.VIPmode == 2 then 
						if self.pred:GetHitChance(target) > self.HitChance then
							if GoPred then
								if not self:TargetGoAway(target,spellPos) or (self:TargetGoAway(target,spellPos) and not self:TargetNearRange(spellPos, self.range)) then
									CastSpell(self.skill, spellPos.x, spellPos.z)
									return true
								end
							else
								CastSpell(self.skill, spellPos.x, spellPos.z)
								return true
							end
						end
					else
						if GoPred then
							if not self:TargetGoAway(target,spellPos) or (self:TargetGoAway(target,spellPos) and not self:TargetNearRange(spellPos, self.range)) then
								CastSpell(self.skill, spellPos.x, spellPos.z)
								return true
							end
						else
							CastSpell(self.skill, spellPos.x, spellPos.z)
							return true
						end
					end
				end
			end
		elseif self.spelltype == SPELL_LINEAR_COL then
			if ValidTarget(target) then
--[[				if self.VIPmode == 3 then
					local spellPos = self.pred:GetPrediction(target)
				else
					local spellPos = self:CanSpell(target,true) and heroblock or self:CanSpell(target)
				end--]]
				local spellPos = self:CanSpell(target,true) and heroblock or self:CanSpell(target)
				if spellPos ~= nil and GetDistance(spellPos,self.who) < self.range then
					if self.HitChance and self.VIPmode == 2 then 
						if self.pred:GetHitChance(target) > self.HitChance then
							if GoPred then
								if not self:TargetGoAway(target,spellPos) or (self:TargetGoAway(target,spellPos) and not self:TargetNearRange(spellPos, self.range)) then
									CastSpell(self.skill, spellPos.x, spellPos.z)
									return true
								end
							else
								CastSpell(self.skill, spellPos.x, spellPos.z)
								return true
							end
						end
					else
						if GoPred then
							if not self:TargetGoAway(target,spellPos) or (self:TargetGoAway(target,spellPos) and not self:TargetNearRange(spellPos, self.range)) then
								CastSpell(self.skill, spellPos.x, spellPos.z)
								return true
							end
						else
							CastSpell(self.skill, spellPos.x, spellPos.z)
							return true
						end
					end
				end
			end
		end
	end
	return false
end

function mySpell:CanSpell(target,hero)
	local spellPos = self.pred:GetPrediction(target)
	if spellPos == nil then return nil end
	local block = nil
	if hero ~= nil and self.VIP then
		block = self:GetCollision(spellPos,self.who) and self.coll:GetHeroCollision(self.who,spellPos)
	else
		block = self:GetCollision(spellPos,self.who)
	end
	if not block  then
		return spellPos
	else 
		return nil
	end
end

function mySpell:CastPos(spellPos,hasdetect)
	if self.Orb~=nil and not self.Orb:TimeToSkill() then return false end
	if hasdetect then 
		CastSpell(self.skill, spellPos.x, spellPos.z)
		return true
	elseif self:GetStat() and spellPos and GetDistance(spellPos) < self.range then
		CastSpell(self.skill, spellPos.x, spellPos.z)
		return true
	end
	return false
end

function mySpell:CastMouse(hasdetect)
	if hasdetect then 
		CastSpell(self.skill, spellPos.x, spellPos.z)
		return true
	else
		if self:GetStat() then
			CastSpell(self.skill, mousePos.x, mousePos.z)
			return true
		end
	end
	return false
end

function mySpell:DrawPre(target,radi,color)
	if ValidTarget(target) and self:GetStat() and self.pred ~= nil then
		local Pos = self.pred:GetPrediction(target)
		if Pos ~= nil then
			if self.spelltype == SPELL_LINEAR_COL then
				if not self:GetCollision(Pos,self.who) then 
					DrawCircle(Pos.x,Pos.y,Pos.z,radi,color)
				end
			else
				DrawCircle(Pos.x,Pos.y,Pos.z,radi,color)
			end
		end
	end
end

function mySpell:TargetNearRange(predic)
	return GetDistance(predic) >= (self.range * .95)
end

function mySpell:TargetGoAway(target,predic)
	return GetDistance(predic) > GetDistance(target)
end


class 'myProdict'

function myProdict:__init(skill,range,speed,delay,width,who)
	self.range = range 
	self.myPred = ProdictManager.GetInstance():AddProdictionObject(skill, range, speed*1000, delay/1000, width, who, function() end)
	self.endT = {}
	self.dashendPos = {}
	AdvancedCallback:bind('OnDash', function(unit,dash) self:OnDash(unit,dash) end)
end

function myProdict:OnDash(unit, dash)
	if unit ~= nil and unit.valid and GetDistance(unit) < 2000 and unit.team ~= myHero.team and dash ~= nil then
		
		PrintChat("hasDash")
		self.endT[unit.networkID] = dash.endT
--		self.dash[unit.networkID] = true
		

		if dash.endPos ~= nil and GetDistance(dash.endPos) < self.range --[[and (dash.endT - GetGameTimer()) < (self.delay/1000)--]] then
			self.dashendPos[unit.networkID] = dash.endPos
		else
			self.dashendPos[unit.networkID] = nil
		end
	end
end

function myProdict:GetPrediction(target)
	if self.endT[target.networkID] ~= nil and GetGameTimer() < self.endT[target.networkID] then
		PrintChat("return Dash")
		return self.dashendPos[target.networkID]
	else
		return self.myPred:GetPrediction(target)
	end
end

function myProdict:CanNotMissMode(sw, hero)
	self.myPred:CanNotMissMode(sw, hero)
end


class'myTarget'
SortList = {
    ["Ashe"]= 1,["Caitlyn"] = 1,["Corki"] = 1,["Draven"] = 1,["Ezreal"] = 1,["Graves"] = 1,["Jayce"] = 1,["KogMaw"] = 1,["MissFortune"] = 1,["Quinn"] = 1,["Sivir"] = 1,
    ["Tristana"] = 1,["Twitch"] = 1,["Varus"] = 1,["Vayne"] = 1,["Lucian"] = 1,["Jinx"] = 1,

    ["Ahri"] = 2,["Annie"] = 2,["Akali"] = 2,["Anivia"] = 2,["Brand"] = 2,["Cassiopeia"] = 2,["Diana"] = 2,["Evelynn"] = 2,["FiddleSticks"] = 2,["Fizz"] = 2,["Gragas"] = 2,
    ["Heimerdinger"] = 2,["Karthus"] = 2,["Kassadin"] = 2,["Katarina"] = 2,["Kayle"] = 2,["Kennen"] = 2,["Leblanc"] = 2,["Lissandra"] = 2,["Lux"] = 2,["Malzahar"] = 2,["Zed"] = 2,
    ["Mordekaiser"] = 2,["Morgana"] = 2,["Nidalee"] = 2,["Orianna"] = 2,["Rumble"] = 2,["Ryze"] = 2,["Sion"] = 2,["Swain"] = 2,["Syndra"] = 2,["Teemo"] = 2,["TwistedFate"] = 2,
    ["Veigar"] = 2,["Viktor"] = 2,["Vladimir"] = 2,["Xerath"] = 2,["Ziggs"] = 2,["Zyra"] = 2,["MasterYi"] = 2,["Shaco"] = 2,["Jayce"] = 2,["Pantheon"] = 2,["Urgot"] = 2,["Talon"] = 2,
    
    ["Alistar"] = 3,["Blitzcrank"] = 3,["Janna"] = 3,["Karma"] = 3,["Leona"] = 3,["Lulu"] = 3,["Nami"] = 3,["Nunu"] = 3,["Sona"] = 3,["Soraka"] = 3,["Taric"] = 3,["Thresh"] = 3,["Zilean"] = 3,

    ["Darius"] = 4,["Elise"] = 4,["Fiora"] = 4,["Gangplank"] = 4,["Garen"] = 4,["Irelia"] = 4,["JarvanIV"] = 4,["Jax"] = 4,["Khazix"] = 4,["LeeSin"] = 4,["Nautilus"] = 4,
    ["Olaf"] = 4,["Poppy"] = 4,["Renekton"] = 4,["Rengar"] = 4,["Riven"] = 4,["Shyvana"] = 4,["Trundle"] = 4,["Tryndamere"] = 4,["Udyr"] = 4,["Vi"] = 4,["MonkeyKing"] = 4,
    ["Aatrox"] = 4,["Nocturne"] = 4,["XinZhao"] = 4,

    ["Amumu"] = 5,["Chogath"] = 5,["DrMundo"] = 5,["Galio"] = 5,["Hecarim"] = 5,["Malphite"] = 5,["Maokai"] = 5,["Nasus"] = 5,["Rammus"] = 5,["Sejuani"] = 5,["Shen"] = 5,
    ["Singed"] = 5,["Skarner"] = 5,["Volibear"] = 5,["Warwick"] = 5,["Yorick"] = 5,["Zac"] = 5
} 

EnemySort = function(x,y) 
        local dmgx = myHero:CalcDamage(x, 100)
        local dmgy = myHero:CalcDamage(y, 100)

        dmgx = dmgx/ (1 + (SortList[x.charName]/10) - 0.1)
        dmgy = dmgy/ (1 + (SortList[y.charName]/10) - 0.1)

        local valuex = x.health/dmgx
        local valuey = y.health/dmgy

        return valuex < valuey
        end

InsecSort = function(x,y) 
        if SortList[x.charName] == SortList[y.charName] then return x.health < y.health end
        return SortList[x.charName] < SortList[y.charName]
        end
		
DisSoft = function(x, y)
		return GetDistance(x) < GetDistance(y)
		end
				
ADSoft = function(x, y)
		return x.totalDamage > y.totalDamage
		end

APSoft = function(x, y)
		return x.ap > y.ap
		end



function myTarget:__init(range,showdmg)
	self.AllClassMenu = 16
	self.TargetConfig = scriptConfig(CNCharName(myHero.charName,1).." V2:目标选择方式", "myTarget"..myHero.charName)
	self.TargetConfig:addParam("sep", "-- 容易击杀+切后排优先 --", SCRIPT_PARAM_INFO, "")
	self.TargetConfig:addParam("swith", "选择方式切换:", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	self.enemys = {}
--	self.untarget = Untargetable()
	self.untarget = false
	self.range = range or 0
	self.target = nil
	self.TryRbuff = false
	self.visionList = {}
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy.charName == "Tryndamere" then
--			self.TryRbuff = TargetHaveObject("Tryndamere",nil,1)
			self.TryRbuff = true
			self.untarget = true
		end
--[[		if enemy.charName == "Kayle" then
			self.KayRbuff = TargetHaveObject("Kayle",nil,1)
			self.KayRbuff = true
			self.untarget = true
		end--]]
		table.insert(self.enemys,enemy)
	end
	for i, enemy in pairs (self.enemys) do
		enemy.killable = 0
	end
	AddTickCallback(function() self:OnTick() end)
	
	if VIP_USER then
		AdvancedCallback:bind('OnLoseVision', function(unit) self:OnLoseVision(unit) end)	
		AdvancedCallback:bind('OnGainVision', function(unit) self:OnGainVision(unit) end)	
	end
		
	if showdmg then
		AddDrawCallback(function() self:DmgShow() end)
	end
end

function myTarget:OnTick()
	if IsKeyDown(self.AllClassMenu) then
		if self.TargetConfig.swith == 1 then
			self.TargetConfig._param[1].text = "-- 优先级+能对其造成更多伤害优先 --"
		elseif self.TargetConfig.swith == 2 then
			self.TargetConfig._param[1].text = "-- 优先级+血量最少优先 --"
		elseif self.TargetConfig.swith == 3 then
			self.TargetConfig._param[1].text = "-- 距离最近优先 --"
		elseif self.TargetConfig.swith == 4 then
			self.TargetConfig._param[1].text = "-- AD最高优先 --"
		elseif self.TargetConfig.swith == 5 then
			self.TargetConfig._param[1].text = "-- AP最高优先 --"
		end
	end
end

function myTarget:ValidT(target,range)
	if GetDistance(target) > range then return false end
	if self.visionList[target.networkID] == nil then 
		return true
	elseif target.type == myHero.type and (GetGameTimer() - self.visionList[target.networkID] > 0.125) then
		return true
	else
		return false
	end
end

function myTarget:OnLoseVision(unit)
	if unit and unit.type == myHero.type and unit.team ~= myHero.team then
		self.visionList[unit.networkID] = math.huge
	end
end

function myTarget:OnGainVision(unit)
	if unit and unit.type == myHero.type and unit.team ~= myHero.team then
		self.visionList[unit.networkID] = GetGameTimer()
	end
end

function myTarget:update(TargetType,range,Tmode)
	self:Sort(self.TargetConfig.swith)
	local enemyrange = 0
	if range then 
		enemyrange = range
	else
		enemyrange = self.range
	end
	
	if Tmode ~= nil and VIP_USER then
		self.target = self:GetTarget(enemyrange,nil,true,1)
	else
		self.target = self:GetTarget(enemyrange,nil,true)
	end
end

function myTarget:Sort(TargetType)
	if TargetType == 1 then
		table.sort(self.enemys,EnemySort)
	elseif TargetType == 2 then
		table.sort(self.enemys,InsecSort)
	elseif TargetType == 3 then
		table.sort(self.enemys,DisSoft)
	elseif TargetType == 4 then
		table.sort(self.enemys,ADSoft)
	elseif TargetType == 5 then
		table.sort(self.enemys,APSoft)
	end
end

function myTarget:GetTarget(range,TargetType,updated,Tmode)
	if not updated then
		self:Sort(TargetType)
	end
--	if self.untarget then PrintChat("wrong") end
	if GetTarget() ~= nil then
		local selecttarget = GetTarget() 
		if ((Tmode~= nil and self:ValidT(selecttarget,range)) or (Tmode == nil and ValidTarget(selecttarget,range))) and selecttarget.team ~= myHero.team and not selecttarget.dead and selecttarget.health ~= 0 and (selecttarget.type == "obj_AI_Hero" or selecttarget.type == "obj_AI_Minion")   then
			if not self.untarget then
				return selecttarget
			else
				if not self:unTarget(selecttarget) then
					return selecttarget
				end
			end
		end
	end
	for i,enemy in ipairs(self.enemys) do
		if ((Tmode~= nil and self:ValidT(enemy,range)) or (Tmode == nil and ValidTarget(enemy,range))) and enemy.team ~= myHero.team and not enemy.dead and enemy.health ~= 0 then
			if not self.untarget then
--				PrintChat("yesTarget")
				return enemy
			else
				if not self:unTarget(enemy) then
					return enemy
				end
			end
		end
	end
	return nil
end


function myTarget:unTarget(target)
--	if not self.untarget then return false end
	if self.TryRbuff and target.charName == "Tryndamere" and GetObj("UndyingRage",target) then
		return true
	end
--[[	if self.KayRbuff and GetObj("",target) then
		return true
	end--]]

	return false
end
	

	
function myTarget:DmgShow()
	if myHero.dead then return end
--[[		if ValidTarget(newTarget) then
			DrawLine3D(myHero.x,myHero.y,myHero.z,newTarget.x,newTarget.y,newTarget.z, 10, ARGB(255,255,10,20))
		end--]]
		for i ,Enemy in pairs (ts.enemys) do
			
			if ValidTarget(Enemy,2000) then
				local pos = WorldToScreen(D3DXVECTOR3(Enemy.x,Enemy.y,Enemy.z))
				if Enemy.killable == 4 then
					DrawTextWithBorder("搞死他!",14,pos.x-30,pos.y-30,ARGB(255,255,10,20),0xFF000000)
--					DrawText3D("搞死他!",Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,255,10,20), true)
				elseif Enemy.killable == 3 then
					DrawTextWithBorder("可击杀!",14,pos.x-30,pos.y-30,ARGB(255,255,143,20),0xFF000000)
--					DrawText3D("可击杀!",Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,255,143,20), true)
				elseif Enemy.killable == 2 then
					DrawTextWithBorder("连招他!",14,pos.x-30,pos.y-30,ARGB(255,248,255,20),0xFF000000)
--					DrawText3D("连招他!",Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,248,255,20), true) 
				elseif Enemy.killable == 1 then
					DrawTextWithBorder("消耗他!",14,pos.x-30,pos.y-30,ARGB(255,10,255,20),0xFF000000)
--					DrawText3D("消耗他!",Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,10,255,20), true)
				else
					DrawTextWithBorder("伤害不够杀不了!",14,pos.x,pos.y,ARGB(244,66,155,255),0xFF000000)
--					DrawText3D("伤害不够杀不了",Enemy.x,Enemy.y, Enemy.z,16,ARGB(244,66,155,255), true)
				end
			end
		end 
end



class'Item'

Items = {
        BRK = {id=3153, range = 500, reqTarget = true, slot = nil, att = true},        -- Blade of the Ruined King
        BWC = {id=3144, range = 400, reqTarget = true, slot = nil, att = true},        -- Bilgewater Cutlass
        HGB = {id=3146, range = 400, reqTarget = true, slot = nil, att = true},        -- Hextech Gunblade
        DFG = {id=3128, range = 750, reqTarget = true, slot = nil, att = true},        -- Deathfire Grasp
        YGB = {id=3142, range = 350, reqTarget = false, slot = nil, att = true},    -- Youmuu's Ghostblade
        STD = {id=3131, range = 350, reqTarget = false, slot = nil, att = true},    -- Sword of the Divine
        RSH = {id=3074, range = 350, reqTarget = false, slot = nil, att = true},    -- Ravenous Hydra
        TMT = {id=3077, range = 350, reqTarget = false, slot = nil, att = true},    -- Tiamat
        EXE = {id=3123, range = 350, reqTarget = false, slot = nil, att = true},    -- Executioner's Calling
        RAN = {id=3143, range = 350, reqTarget = false, slot = nil, att = true},    -- Randuin's Omen
		
		
		LOC = {id=3190, range = 600, reqTarget = false, slot = nil}		-- 鸟盾
        }
function Item:__init(showpre,HeroType,Orb)
	self.Orb = Orb or nil
	self.HeroType = HeroType
	self.Config = scriptConfig(CNCharName(myHero.charName,1).." V2:自动道具\32Yooo\212\173\180\180", "AutoItem"..myHero.charName)
	if not HeroType then
		self.Config:addParam("autoattackitem", "连招自动释放攻击类道具(开关)", SCRIPT_PARAM_ONOFF, true)
		self.Config:addParam("autoattackitem2", "手动释放攻击类道具(按住)", SCRIPT_PARAM_ONKEYDOWN, false, 49)
	else
		self.Config:addParam("Tautoattackitem","按自动Carry键时释放攻击类道具", SCRIPT_PARAM_ONOFF, true)
		self.Config:addParam("Aautoattackitem","按混合模式键时释放攻击类道具", SCRIPT_PARAM_ONOFF, true)
	end
	self.Config:addParam("sep", "-- 鸟盾 --", SCRIPT_PARAM_INFO, "")
	self.Config:addParam("id3190", "自动鸟盾", SCRIPT_PARAM_ONOFF, true)
	self.Config:addParam("LOC", "使用鸟盾血量百分比(自己)",SCRIPT_PARAM_SLICE, 20, 0, 100, 0)
	self.Config:addParam("ADC", "使用鸟盾血量百分比(AD最高队友)", SCRIPT_PARAM_SLICE, 40, 0, 100, 0)
	self.Config:addParam("sep", "-- 中亚 --", SCRIPT_PARAM_INFO, "")
	self.Config:addParam("id3157", "自动中亚", SCRIPT_PARAM_ONOFF, true)
	self.Config:addParam("per3157", "使用中亚血量百分比",SCRIPT_PARAM_SLICE, 20, 0, 100, 0)
	self.Config:addParam("sep", "-- 炽天使之拥 --", SCRIPT_PARAM_INFO, "")
	self.Config:addParam("id3040", "自动炽天使之拥", SCRIPT_PARAM_ONOFF, true)
	self.Config:addParam("per3040", "使用炽天使之拥血量百分比",SCRIPT_PARAM_SLICE, 20, 0, 100, 0)
--	self.Config:addParam("EnemyCount", "周围敌人数量", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
	
--	if self.Config.id3190 then
	self.ls = TargetSelector(TARGET_LOW_HP, 1100,DAMAGE_PHYSICAL, true,false)
	AddProcessSpellCallback(function(unit,spell) self:OnProcessSpell(unit,spell) end)
--	end
	
	if showpre and not HeroType then
		self.Config:permaShow("autoattackitem2")
	end
end

function Item:update()
	for _,item in pairs(Items) do
		item.slot = GetInventorySlotItem(item.id)
	end 
end

function Item:UseAllAttackItem(target)
	if not ((self.Config.autoattackitem or self.Config.autoattackitem2 or self.HeroType ~= nil) and target ~= nil) then return end
	self:update()
	
	for _,item in pairs(Items) do
		if item.slot ~= nil and item.att then
			if item.reqTarget and GetDistance(target) <= item.range then
				CastSpell(item.slot, target)
			end
			if item.reqTarget == false and GetDistance(target) <= item.range then
				CastSpell(item.slot)
			end
		end
	end
end

function Item:GetSlot(id)
	self:update()
	for _,item in pairs(Items) do
		if item.id == id and item.slot ~= nil then
			return item.slot
		end
	end
	return nil
end

function Item:GetStat(id,who)
	local who = who or myHero
	local slot = self:GetSlot(id)
	return (slot and who:CanUseSpell(slot) == READY),slot
end

function Item:OnProcessSpell(unit,spell)
	if myHero.dead or not unit.valid or unit.name == nil or spell ==nil or spell.name == nil or spell.target == nil then return end

	if self.Config.id3190 then
		local ready,slot = self:GetStat(3190)
		if ready and slot and unit.name:find("minion") == nil and unit.name:find("Minion_") == nil and unit.team ~= myHero.team then
			self.ls:update()
			if ((myHero.health/myHero.maxHealth) < (self.Config.LOC/100) and spell.target == myHero) or ((self.ls.target ~= nil and GetDistance(self.ls.target)<=600 and not self.ls.target.dead and (self.ls.target.health/self.ls.target.maxHealth) < (self.Config.ADC/100)) and spell.target == self.ls.target) then 
				CastSpell(slot)
			end
		end
	end
	
	if self.Config.id3157 then
		local ready3157,slot3157 = self:GetStat(3157)
		if ready3157 and slot3157 and unit.type == "obj_AI_Hero" and unit.team ~= myHero.team and spell.target == myHero and (myHero.health/myHero.maxHealth) < (self.Config.per3157/100) then
			 CastSpell(slot3157)
		end
	end
	if self.Config.id3040 then
		local ready3040,slot3040 = self:GetStat(3040)
		if ready3040 and slot3040 and unit.type == "obj_AI_Hero" and unit.team ~= myHero.team and spell.target == myHero and (myHero.health/myHero.maxHealth) < (self.Config.per3040/100) then
			 CastSpell(slot3157)
		end
	end
end


class 'myInterrupt'

champsStun = {
                { charName = "Caitlyn",      spell = _R,   spellName = "CaitlynAceintheHole" ,        important = 1},
				{ charName = "Ezreal",       spell = _R,   spellName = "" , 					      important = 1},
				{ charName = "FiddleSticks", spell = _R,   spellName = "Crowstorm" ,                  important = 1},
				{ charName = "Galio",        spell = _R,   spellName = "GalioIdolOfDurand" ,          important = 1},
                { charName = "Karthus",      spell = _R,   spellName = "FallenOne" ,                  important = 1},
				{ charName = "Katarina",     spell = _R,   spellName = "KatarinaR" ,                  important = 1},
                { charName = "Lux",      	 spell = _R,   spellName = "LuxLightBinding" ,			  important = 1},
				{ charName = "Malzahar",     spell = _R,   spellName = "AlZaharNetherGrasp" ,         important = 1},
				{ charName = "MissFortune",  spell = _R,   spellName = "MissFortuneBulletTime" ,      important = 1},
				{ charName = "Morgana",		 spell = _R,   spellName = "" ,    						  important = 1},
				{ charName = "Nami",		 spell = _R,   spellName = "" ,    						  important = 1},
				{ charName = "Nunu",         spell = _R,   spellName = "AbsoluteZero" ,               important = 1},
				{ charName = "Pantheon",     spell = _R,   spellName = "Pantheon_GrandSkyfall_Jump" , important = 1},
				{ charName = "Rengar",       spell = _R,   spellName = "RengarR" ,           	      important = 1},	
				{ charName = "Shen",         spell = _R,   spellName = "ShenStandUnited" ,            important = 1},
				{ charName = "Talon",     	 spell = _R,   spellName = "TalonShadowAssault" ,         important = 1},
				{ charName = "TwistedFate",  spell = _R,   spellName = "gate" ,					      important = 1},
				{ charName = "Urgot",        spell = _R,   spellName = "UrgotSwap2" ,                 important = 1},
				
				
				
                { charName = "FiddleSticks", spell = _R,   spellName = "DrainChannel" ,               important = 2},
				{ charName = "MasterYi",	 spell = _W,   spellName = "Meditate" ,              	  important = 2},
                { charName = "Varus",   	 spell = _Q,   spellName = "VarusQ" ,                	  important = 2}
                
		
				
			}

function myInterrupt:__init(skill)
	self.champs = {}
	self.skill = skill
	for i, enemy in ipairs(GetEnemyHeroes()) do
		for j, champ in ipairs(champsStun) do
			if enemy.charName == champ.charName then
				table.insert(self.champs,champ)
			end
		end
	end
	if #(self.champs) == 0 then 
		PrintChat("\230\149\140\230\150\185\230\178\161\230\156\137\233\128\130\231\148\168\232\135\170\229\138\168\230\137\147\230\150\173\231\154\132\230\138\128\232\131\189\44\232\135\170\229\138\168\230\137\147\230\150\173\229\129\156\230\173\162\232\189\189\229\133\165") 
		return 
	end
	
	self.Config = scriptConfig(CNCharName(myHero.charName,1).." V2:自动打断\32Yooo\212\173\180\180", "myInterrupt"..myHero.charName)
	self.Config:addParam("ON", "开启自动打断", SCRIPT_PARAM_ONOFF, true)
	AddProcessSpellCallback(function(unit,spell) self:OnProcessSpell(unit,spell) end)
end

function myInterrupt:OnProcessSpell(unit,spell)
	if self.Config.ON and unit.valid and unit.health ~= 0 and unit.team ~= myHero.team and spell and spell.name ~= nil then
		for i, champ in pairs(self.champs) do
			if GetSpellData(champ.spell).name == spell.name and unit.canMove then
				if type(self.skill) == "table" then
					for i,sk in pairs (self.skill) do
						if	type(sk) ~= "function" then
							sk:Cast(unit)
						else 
							sk(unit)
						end
					end
				elseif type(self.skill) ~= "function" then
					skill:Cast(unit)
				else
					self.skill(unit)
				end
			end
		end
	end
end

class'Mastery'

function Mastery:__init()
	self.MasteryConfig = scriptConfig(CNCharName(myHero.charName,1).." V2:天赋伤害修正\32Yooo\212\173\180\180", "Mastery"..myHero.charName)
--[[	self.MasteryConfig:addParam("sep1", "-- 连招击杀伤害修正 --", SCRIPT_PARAM_INFO, "")
	self.MasteryConfig:addParam("fashubianzhi","天赋:法术编织",SCRIPT_PARAM_ONOFF, false)
	self.MasteryConfig:addParam("zhourenbianzhi","天赋:咒刃编织",SCRIPT_PARAM_ONOFF, false)--]]
	self.MasteryConfig:addParam("baoliu","保留伤害",SCRIPT_PARAM_SLICE, 10, 0, 30, 0)
	
	self.MasteryConfig:addParam("sep2", "-- Carry补刀伤害修正 --", SCRIPT_PARAM_INFO, "")
	self.MasteryConfig:addParam("tufu","天赋:屠夫",SCRIPT_PARAM_ONOFF, false)
	self.MasteryConfig:addParam("zhoujian", "天赋:奥术之刃", SCRIPT_PARAM_ONOFF, false)
	
	self.MasteryConfig:addParam("sep3", "-- 击杀/补刀通用伤害修正 --", SCRIPT_PARAM_INFO, "")
	self.MasteryConfig:addParam("death", "天赋:死神", SCRIPT_PARAM_SLICE, 0, 0, 3, 0)
	self.MasteryConfig:addParam("shuangrenjian", "天赋:双刃剑", SCRIPT_PARAM_ONOFF, false)
	self.MasteryConfig:addParam("haojie","天赋:末日浩劫",SCRIPT_PARAM_ONOFF, false)
end

function Mastery:GetAddDmg(dmg,target)
	local tianfujiachen = 1.0 
	if self.MasteryConfig.haojie then
		tianfujiachen = tianfujiachen + 0.03
	end
	
	if self.MasteryConfig.death ~= 0 and target.health <= target.maxHealth*(0.05+ 0.15*self.MasteryConfig.death)  then
		tianfujiachen = tianfujiachen + 0.05
	end
	
	if self.MasteryConfig.shuangrenjian then
		if myHero.range > 300 then
			tianfujiachen = tianfujiachen + 0.015
		else
			tianfujiachen = tianfujiachen + 0.02
		end
	end
	
	return dmg*tianfujiachen-self.MasteryConfig.baoliu
end

class 'MyTargetHaveBuff'
function MyTargetHaveBuff:__init(buffname,who,unittype)
	self.havebuff = {}
	self.who = who or 0
	self.buffname = buffname
	self.unittype = unittype or "obj_AI_Hero"
	
	if self.who == 1 or self.who == 4 then
		AddTickCallback(function() self:OnTick() end)
	end
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
    AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
end

function MyTargetHaveBuff:OnTick()
	for i,buff in pairs(self.havebuff) do
		if buff ~= nil and GetGameTimer() > buff.bufftime then
			buff = nil
		end
	end
end

function MyTargetHaveBuff:GetBuff(target)
	return (self.havebuff[target.networkID] ~= nil)
end

function MyTargetHaveBuff:OnGainBuff(unit, buff)
    if unit == nil or buff == nil or buff.name == nil then return end
	self:buffDec(unit,buff,true)
end

function MyTargetHaveBuff:OnLoseBuff(unit, buff)
	if unit == nil or buff == nil or buff.name == nil then return end
	self:buffDec(unit,buff,false)
end

function MyTargetHaveBuff:buffDec(unit,buff,bufftype)
	if self.who == 0 then
		if unit.isMe and self:Detecebuff(buff) then
			if bufftype then
				self.havebuff[unit.networkID] = {buffon = true, bufftime = buff.endT}
			else
				self.havebuff[unit.networkID] = nil
			end
		end
	elseif self.who == 1 then
		if unit.type == self.unittype and unit.team ~= myHero.team and self:Detecebuff(buff) then
			if bufftype then
				self.havebuff[unit.networkID] = {buffon = true, bufftime = buff.endT}
			else
				self.havebuff[unit.networkID] = nil
			end
		end
	elseif self.who == 2 then
		if unit.type == self.unittype and unit.team == myHero.team and self:Detecebuff(buff) then
			if bufftype then
				self.havebuff[unit.networkID] = {buffon = true, bufftime = buff.endT}
			else
				self.havebuff[unit.networkID] = nil
			end
		end
	elseif self.who == 3 then
		if unit.isMe or (unit.type == self.unittype and unit.team == myHero.team) and self:Detecebuff(buff) then
			if bufftype then
				self.havebuff[unit.networkID] = {buffon = true, bufftime = buff.endT}
			else
				self.havebuff[unit.networkID] = nil
			end
		end
	elseif self.who == 4 then
		if unit.type == self.unittype and self:Detecebuff(buff) then
			if bufftype then
				self.havebuff[unit.networkID] = {buffon = true, bufftime = buff.endT}
			else
				self.havebuff[unit.networkID] = nil
			end
		end
	end
end

function MyTargetHaveBuff:Detecebuff(buff)
	if type(self.buffname) == "string" then
		return buff.name:find(self.buffname)
	elseif type(self.buffname) == "table" then
		for i,buffn in pairs (self.buffname) do
			if buff.name:find(buffn) then
				return true
			end
		end
	end
	return false
end

class'TargetHaveObject'
-- myself = 0, ally = 1, ally+myself =2 
function TargetHaveObject:__init(objectname,distance,who)
	self.objectname = objectname
	self.distance = distance or 100
	self.who = who or 0
	self.have = {}
	if self.who == 1 or self.who == 4 then
		AddTickCallback(function() self:OnTick() end)	
	end
	AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
	AddDeleteObjCallback(function(obj) self:OnDeleteObj(obj) end)
end


function TargetHaveObject:GetObj(target)
	return self.have[target.networkID] ~= nil
end

function TargetHaveObject:DeteceObj(obj)
	if type(self.objectname) == "string" then
		return obj.name:find(self.objectname)
	elseif type(self.objectname) == "table" then
		for i,object in pairs (self.objectname) do
			if obj.name:find(object) then
				return true
			end
		end
	end
	return false
end

function TargetHaveObject:myself(obj,objtype)
	if self:DeteceObj(obj) and GetDistance(obj) < self.distance then
		if objtype then
			self.have[myHero.networkID] = GetTickCount()
		else
			self.have[myHero.networkID] = nil
		end
	end
end

--[[function TargetHaveObject:enemyonly(obj,objtype)
	for i, target in pairs(GetEnemyHeroes()) do
		if not target.dead and self:DeteceObj(obj) and GetDistance(obj,target) < self.distance then
		if objtype then
			self.have[myHero.networkID] = GetTickCount()
		else
			self.have[myHero.networkID] = nil
		end
		end
	end
end--]]

function TargetHaveObject:allyonly(obj,objtype)
	for i, target in pairs(GetAllyHeroes()) do
		if self:DeteceObj(obj) and GetDistance(obj,target) < self.distance then
			if objtype then
				self.have[myHero.networkID] = GetTickCount()
			else
				self.have[myHero.networkID] = nil
			end
		end
	end
end


function TargetHaveObject:OnCreateObj(obj)
	if obj and obj.name then
		if self.who == 0 or self.who == 3 then
			self:myself(obj,true)
		end
--[[		if self.who == 1 or self.who == 4 then
			self:enemyonly(obj,true)
		end--]]
		if self.who == 1 or self.who == 3 then
			self:allyonly(obj,true)
		end
	end
end

function TargetHaveObject:OnDeleteObj(obj)
	if obj and obj.name then
		if self.who == 0 or self.who == 3 then
			self:myself(obj)
		end
--[[		if self.who == 1 or self.who == 4 then
			self:enemyonly(obj,true)
		end--]]
		if self.who == 1 or self.who == 3 then
			self:allyonly(obj)
		end
	end
end

class 'AutoLevel'

function AutoLevel:__init()
	self.F,self.S,self.T = 0,0,0
	self.order = {}
	self.hasset = false
	--local name = myHero.charName.."levelup"
	self.Config = scriptConfig(CNCharName(myHero.charName,1).." V2:自动升级技能\32Yooo\212\173\180\180", "AutoLevel"..myHero.charName)
	self.Config:addParam("autolevelon", "开启自动升级技能", SCRIPT_PARAM_ONOFF, true)
	self.Config:addParam("onlyR", "只自动升级R", SCRIPT_PARAM_ONOFF, false)
--	self.Config:addParam("LV1", "1级不升技能", SCRIPT_PARAM_ONOFF, false)
	self.Config:addParam("F", "主升(1=Q,2=W,3=E)", SCRIPT_PARAM_SLICE, 1, 1, 3, 0)
	self.Config:addParam("S", "副升(1=Q,2=W,3=E)", SCRIPT_PARAM_SLICE, 3, 1, 3, 0)
	self.Config:addParam("R", "有R点R", SCRIPT_PARAM_ONOFF, true)
	self.Config:addParam("savep","应用以上设置", SCRIPT_PARAM_ONKEYDOWN, false, 96)
	self.Config:addParam("autoread", "以后此英雄自动应用此设置", SCRIPT_PARAM_ONOFF, false)
	--if GetSave("autolevel").name then self.order = GetSave("autolevel").name end
	AddTickCallback(function() self:OnTick() end)
end

function AutoLevel:OnTick()
	if (self.Config.savep or self.Config.autoread) and not self.hasset then 
		self:update() 
	end
	if #self.order ~= 0 then
		self.hasset = true 
	end
	if not (self.Config.autolevelon and self.hasset) --[[or (self.Config.LV1 and myHero.level == 1)--]] then return end
	if self.Config.onlyR then 
		if myHero.level == 6 or myHero.level == 11 or myHero.level == 16 then
			LevelSpell(_R)
		end
		return
	end
	if self.Config.R then 
		autoLevelSetSequence(self.order)
	else
		LevelSpell(self.order[myHero.level])
	end
end

function AutoLevel:update()
	if self.Config.F == self.Config.S then
		PrintChat("\228\184\187\229\137\175\232\175\183\228\184\141\232\166\129\232\174\190\231\189\174\229\144\140\228\184\128\230\138\128\232\131\189")
		return
	end
	self.F = self.Config.F
	self.S = self.Config.S
	if self.F ~= 1 and self.S ~= 1 then self.T = 1
	elseif self.F ~= 2 and self.S ~= 2 then self.T = 2
	else self.T = 3
	end
	if self.Config.R then
		self.order = {self.F, self.S, self.T, self.F, self.F, 4, self.F, self.S, self.F, self.S, 4, self.S, self.S, self.T, self.T, 4, self.T, self.T}
	else
		self:setord()
		
		self.order = {self.F, self.S, self.T, self.F, self.F, self.S, self.F, self.S, self.F, self.S, self.S, self.T, self.T, self.T, self.T, _R, _R, _R}
	end
	--GetSave("autolevel").name = self.order
end

function AutoLevel:setord()
		if self.F == 1 then
			self.F = _Q
		elseif self.F == 2 then
			self.F = _W
		else 
			self.F = _E
		end
		
		if self.S == 1 then
			self.S = _Q
		elseif self.S == 2 then
			self.S = _W
		else 
			self.S = _E
		end
		
		if self.T == 1 then
			self.T = _Q
		elseif self.T == 2 then
			self.T = _W
		else 
			self.T = _E
		end
end

--[[function AutoLevel:noR()
	LevelSpell(self.order[myHero.level])
end--]]

class'ShowRange'

function ShowRange:__init(name,arg,ts,Orb)
	self.name = name or myHero.charName
	self.skills = arg
	self.ts = ts or nil
	self.Orb = Orb or false
	self.AAColor = 0x19A712
	self.ShowRangeConfig = scriptConfig(CNCharName(self.name,1).." V2:显示选项\32Yooo\212\173\180\180", "ShowRange"..self.name)
	if self.ts then
		self.ShowRangeConfig:addParam("sep1","-- 目标显示 --", SCRIPT_PARAM_INFO, "")
		self.ShowRangeConfig:addParam("DrawTarget", "显示目标圈", SCRIPT_PARAM_ONOFF, true)
		self.ShowRangeConfig:addParam("DrawLineTarget", "显示目标线", SCRIPT_PARAM_ONOFF, true)
	end
	if #self.skills ~=0 then
		self.ShowRangeConfig:addParam("sep2","-- 技能范围显示 --", SCRIPT_PARAM_INFO, "")
		self.ShowRangeConfig:addParam("DrawAA", "显示普攻范围", SCRIPT_PARAM_ONOFF, true)
		self.ShowRangeConfig:addParam("DrawON", "开启显示技能范围", SCRIPT_PARAM_ONOFF, true)
	end
--[[	if #arg2 > 1 then
		self.ShowRangeConfig:addParam("DrawFurthest", "只显示最远技能范围", SCRIPT_PARAM_ONOFF, true)
	end--]]

	for _, spell in pairs (self.skills) do
		local skillname = spell.name or spell:Name()
		self.ShowRangeConfig:addParam(skillname, "显示"..skillname.."范围", SCRIPT_PARAM_ONOFF, true)
		if spell.spelltype == SPELL_LINEAR or spell.spelltype == SPELL_LINEAR_COL and not spell.nopre then
			self.ShowRangeConfig:addParam(skillname.."pre","显示"..skillname.."预判点", SCRIPT_PARAM_ONOFF, true)
		end
		self.ShowRangeConfig:addParam(skillname.."Color", skillname.."范围颜色", SCRIPT_PARAM_COLOR, self:GetColors(skillname))
	end
	
	if self.Orb then
		self.ShowRangeConfig:addParam("sep3","-- Carry补刀显示 --", SCRIPT_PARAM_INFO, "")
		self.ShowRangeConfig:addParam("minion", "标记下一个要补刀的小兵", SCRIPT_PARAM_ONOFF, true)
		self.ShowRangeConfig:addParam("killable", "可补刀时提示", SCRIPT_PARAM_ONOFF, true)
	end
	
	AddDrawCallback(function() self:OnDraw() end)
end

function ShowRange:GetColors(skillname)
	if skillname == "Q" then
		return {150, 55, 175, 255}
	elseif skillname == "W" then
		return {150, 255, 128, 128}
	elseif skillname == "E" then
		return {150, 255, 128, 64}
	elseif skillname == "R" then
		return {150, 153, 102, 204}
	else
		return {150, 128, 255, 128}
	end
end

--[[function ShowRange:Color(skillname)
	if skillname == "Q" then return 10
	elseif skillname == "W" then return 20	
	elseif skillname == "E" then return 30
	elseif skillname == "R" then return 40
	end
end--]]

function ShowRange:FromTable(table)
        return ARGB(table[1], table[2], table[3], table[4])
end 

--[[function ShowRange:AddCirle(skill,range)
	for i,spell in pairs(self.showtable) do
		if spell.skill.skill == skill then
			table.insert(spell.addrange,range)
		end
	end
	self.added = true
end--]]

--[[function ShowRange:FindFurthestReadySpell()
	local far = nil
	for i,spell in pairs (self.showtable) do
		if spell.skill.skill == _Q and self.ShowRangeConfig.drawQ and spell.skill:GetStat() then
			far = spell
		end
		if spell.skill.skill == _W and self.ShowRangeConfig.drawW and spell.skill:GetStat() and (not far or spell.skill.range > far.skill.range) then
			far = spell
		end
		if spell.skill.skill == _E and self.ShowRangeConfig.drawE and spell.skill:GetStat() and (not far or spell.skill.range > far.skill.range) then
			far = spell
		end
		if spell.skill.skill == _R and self.ShowRangeConfig.drawR and spell.skill:GetStat() and (not far or spell.skill.range > far.skill.range) then
			far = spell
		end
	end
	return far
end--]]

function ShowRange:OnDraw()
	if myHero.dead then return end
	if self.ts.target then
		self:DrawTarget()
		self:DrawLineTarget()
		self:DrawPred()
	end
	self:DrawRange()
end

function ShowRange:DrawTarget()
	if not self.ShowRangeConfig.DrawTarget then return end
	DrawCircle(self.ts.target.x, self.ts.target.y, self.ts.target.z, 100, 0xFF0000)
	DrawCircle(self.ts.target.x, self.ts.target.y, self.ts.target.z, 99, 0xFF0000)
end

function ShowRange:DrawLineTarget()
	if not self.ShowRangeConfig.DrawLineTarget then return end
	DrawLine3D(myHero.x, myHero.y, myHero.z, self.ts.target.x, self.ts.target.y, self.ts.target.z, 1, 0xFFFF0000)
end


function ShowRange:DrawRange()
	if not self.ShowRangeConfig.DrawON then return end
	for i,spell in pairs (self.skills) do
		if self.ShowRangeConfig[spell.name] then
			spell:Draw(self:FromTable(self.ShowRangeConfig[spell.name.."Color"]))
		end
	end
	if self.ShowRangeConfig.DrawAA then
		DrawCircle(myHero.x, myHero.y, myHero.z, GetTrueRange(), self.AAColor)
	end
end

function ShowRange:DrawPred()
	for i,spell in pairs (self.skills) do
		if self.ShowRangeConfig[spell.name.."pre"] then
			if spell.width ~=0 then
				spell:DrawPre(ts.target,spell.width,spell:GetColor())
			else
				spell:DrawPre(ts.target,120,spell:GetColor())
			end
		end
	end
end

class 'myJump'

function myJump:__init(skill,name)
	if myHero.charName ~= "LeeSin" and myHero.charName ~= "Jax" and myHero.charName ~= "Katarina" then return end
	self.name = name or myHero.charName
	self.wardtime = 0
	self.skill = skill
	self.jumpReady = false
	self.jumpRange = 600
	self.WardTable = {}
	self.wardSlot = nil
	self.SWard, self.VWard, self.SStone, self.RSStone, self.Wriggles = 2044, 2043, 2049, 2045, 3154
	self.SWardSlot, self.VWardSlot, self.SStoneSlot, self.RSStoneSlot, self.WrigglesSlot = nil, nil, nil, nil, nil
	self.JumpConfig = scriptConfig(CNCharName(self.name,1).." V2:一键瞬眼\32Yooo\212\173\180\180", "myJump"..self.name)
	self.JumpConfig:addParam("jump", "按住一键瞬眼(Z)", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Z"))
--[[	self.JumpConfig:addParam("text", "< 向鼠标方向瞬眼 >", SCRIPT_PARAM_INFO, "")
	self.JumpConfig:addParam("mode", "模式选择:", SCRIPT_PARAM_SLICE, 1, 1, 3, 0)--]]
	self.JumpConfig:addParam("range", "显示瞬眼范围", SCRIPT_PARAM_ONOFF, true)
	self.JumpConfig:permaShow("jump")
	AddTickCallback(function() self:OnTick() end)
	AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
	if self.JumpConfig.range then AddDrawCallback(function() self:OnDraw() end) end
end

function myJump:OnTick()
	if self.jumpReady then self:JumpReady() end
	if self.JumpConfig.jump then self:JumpCheck() end
end

function myJump:OnDraw()
	if myHero.dead or not self.JumpConfig.range then return end
	self:updateWard()
	if self.wardSlot and self.skill:GetStat() then
		DrawCircle(myHero.x, myHero.y, myHero.z, self.jumpRange, 0x85667F)
	end
end
_G.Item7 = false
function myJump:updateWard()
	self.SWardSlot = GetInventorySlotItem(self.SWard)
	self.VWardSlot = GetInventorySlotItem(self.VWard)
	self.SStoneSlot = GetInventorySlotItem(self.SStone) 
	self.RSStoneSlot = GetInventorySlotItem(self.RSStone)
	self.WrigglesSlot = GetInventorySlotItem(self.Wriggles)
	
	if _G.Item7== true then
		if myHero:CanUseSpell(ITEM_7) == READY and myHero:getItem(ITEM_7).id == 3340 then
			self.wardSlot = ITEM_7
		end
	else
		if self.RSStoneSlot ~= nil and CanUseSpell(self.RSStoneSlot) == READY then
			self.wardSlot = self.RSStoneSlot 
		elseif self.SStoneSlot ~= nil and CanUseSpell(self.SStoneSlot) == READY then
			self.wardSlot = self.SStoneSlot 
		elseif self.SWardSlot ~= nil then
			self.wardSlot = self.SWardSlot 
		elseif VWardSlot ~= nil then
			self.wardSlot = self.VWardSlot 
		elseif WrigglesSlot ~= nil then
			self.wardSlot = self.WrigglesSlot 
		else self.wardSlot = nil
		end
	end
end

function myJump:JumpCheck()

	
	self:updateWard()

	if self.wardSlot ~= nil then
		self:GetWardPos()
	end
end

function myJump:GetWardPos()
	if GetGameTimer() < self.wardtime + 5 then return end
	if not self.skill:GetStat() then return end
	if GetDistance(mousePos) <= self.skill.range then
		local x = mousePos.x
		local z = mousePos.z
		local dx = x - player.x
		local dz = z - player.z
		local rad1 = math.atan2(dz, dx)
		local dx1 = self.jumpRange*math.cos(rad1)
		local dz1 = self.jumpRange*math.sin(rad1)
		local x1 = x - dx1
		local z1 = z - dz1
		if self.skill:GetStat() and math.sqrt(dx*dx + dz*dz) <= self.skill.range then
			CastSpell(self.wardSlot, x, z )
			self.checkpos = mousePos
			self.jumpReady = true
--[[		elseif self.skill:GetStat() then 
				myHero:MoveTo(x1, z1) 
		else 
			myHero:StopPosition() --]]
		end
	else
		local mousepos = Vector(mousePos)
		local mypos = Vector(myHero)
		local wardpos = mypos + (mousepos - mypos):normalized() * self.jumpRange
		
		CastSpell(self.wardSlot, wardpos.x, wardpos.z)
		self.wardtime = GetGameTimer()
		self.checkpos = wardpos
		self.jumpReady = true
	end
end

function myJump:JumpReady()
	for i,object in ipairs(self.WardTable) do
--		PrintChat("loc:"..object.x,object.z)
		if object ~= nil and object.valid and math.sqrt((object.x-self.checkpos.x)^2+(object.z-self.checkpos.z)^2) < 150 then
--			CastSpell(self.skill.skill,object)
			self.skill:Cast(object,true)
			self.jumpReady = false
		end
	end
end

function myJump:OnCreateObj(obj)
	if obj and obj.valid and (string.find(obj.name, "Ward") ~= nil or string.find(obj.name, "Wriggle") ~= nil) then table.insert(self.WardTable, obj) end
end


local __mmyAlerter, __myAlerter_OnTick, __myAlerter_OnDraw = nil, nil, nil

function MyPrintAlert(text, duration, r, g, b, sprite)
    if not __mmyAlerter then __mmyAlerter = __myAlerter() end
    return __mmyAlerter:Push(text, duration, r, g, b, sprite)
end

class("__myAlerter")
function __myAlerter:__init()
    if not __myAlerter_OnTick then
        function __myAlerter_OnTick() if __mmyAlerter then __mmyAlerter:OnTick() end end
        AddTickCallback(__myAlerter_OnTick)
    end
    if not __myAlerter_OnDraw then
        function __myAlerter_OnDraw() if __mmyAlerter then __mmyAlerter:OnDraw() end end
        AddDrawCallback(__myAlerter_OnDraw)
    end

--[[    self.config = scriptConfig("myAlerter", "MyalerterClass")
    self.config:addParam("max", "Max Alerts", SCRIPT_PARAM_SLICE, 4, 2, 5, 0)
    self.config:addParam("yOffset", "Y Offset", SCRIPT_PARAM_SLICE, 0.25, 0.1, 0.5, 2)
    self.config:addParam("textSize", "Font Size", SCRIPT_PARAM_SLICE, 20, 12, 30, 0)
    self.config:addParam("textOutline", "Font Outline (May cause FPS drops)", SCRIPT_PARAM_SLICE, 0, 0, 3, 0)
    self.config:addParam("fadeDuration", "Fade In/Out Duration (Sec)", SCRIPT_PARAM_SLICE, 1, 0, 3, 1)
    self.config:addParam("fadeOffset", "Fade In/Out X Offset", SCRIPT_PARAM_SLICE, 50, 20, 200, 0)--]]
	self.max = 4
	self.yOffset = 0.25
	self.textSize = 20
	self.textOutline = 0
	self.fadeDuration = 1
	self.fadeOffset = 50

    self.yO = -WINDOW_H * self.yOffset
    self.x = WINDOW_W/2
    self.y = WINDOW_H/2 + self.yO
    self._alerts = {}
    self.activeCount = 0
    return self
end

function __myAlerter:OnTick()
    self.yO = -WINDOW_H * self.yOffset
    self.x = WINDOW_W/2
    self.y = WINDOW_H/2 + self.yO
    --if #self._alerts == 0 then self:__finalize() end
end

function __myAlerter:OnDraw()
    local gameTime = GetInGameTimer()
    for i, alert in ipairs(self._alerts) do
        self.activeCount = 0
        for j = 1, i do
            local cAlert = self._alerts[j]
            if not cAlert.outro then self.activeCount = self.activeCount + 1 end
        end
        if self.activeCount <= self.max and (not alert.playT or alert.playT + self.fadeDuration*2 + alert.duration > gameTime) then
            alert.playT = not alert.playT and self._alerts[i-1] and (self._alerts[i-1].playT + 0.5 >= gameTime and self._alerts[i-1].playT + 0.5 or gameTime) or alert.playT or gameTime
            local intro = alert.playT + self.fadeDuration > gameTime
            alert.outro = alert.playT + self.fadeDuration + alert.duration <= gameTime
            alert.step = alert.outro and math.min(1, (gameTime - alert.playT - self.fadeDuration - alert.duration) / self.fadeDuration)
                    or gameTime >= alert.playT and math.min(1, (gameTime - alert.playT) / self.fadeDuration)
                    or 0
            local xO = alert.outro and self:Easing(alert.step, 0, self.fadeOffset) or self:Easing(alert.step, -self.fadeOffset, self.fadeOffset)
            local alpha = alert.outro and self:Easing(alert.step, 255, -255) or self:Easing(alert.step, 0, 255)
            local yOffsetTar = GetTextArea(alert.text, self.textSize).y * 1.2 * (self.activeCount-1)
            alert.yOffset = intro and alert.step == 0 and yOffsetTar
                    or #self._alerts > 1 and not alert.outro and (alert.yOffset < yOffsetTar and math.min(yOffsetTar, alert.yOffset + 0.5) or alert.yOffset > yOffsetTar and math.max(yOffsetTar, alert.yOffset - 0.5))
                    or alert.yOffset
            local x = self.x + xO
            local y = self.y - alert.yOffset
            -- outline:
            local o = self.textOutline
            if o > 0 then
                for j = -o, o do
                    for k = -o, o do
                        DrawTextA(alert.text, self.textSize, math.floor(x+j), math.floor(y+k), ARGB(alpha, 0, 0, 0), "center", "center")
                    end
                end
            end
            -- sprite:
            if alert.sprite then
                alert.sprite:SetScale(alert.spriteScale, alert.spriteScale)
                alert.sprite:Draw(math.floor(x - GetTextArea(alert.text, self.textSize).x/2 - alert.sprite.width * alert.spriteScale * 1.5), math.floor(y - alert.sprite.width * alert.spriteScale / 2), alpha)
            end
            -- text:
            DrawTextA(alert.text, self.textSize, math.floor(x), math.floor(y), ARGB(alpha, alert.r, alert.g, alert.b), "center", "center")
        elseif alert.playT and alert.playT + self.fadeDuration*2 + alert.duration <= gameTime then
            table.remove(self._alerts, i)
        end
    end
end

function __myAlerter:Push(text, duration, r, g, b, sprite)
    local alert = {}
    alert.text = text
    alert.sprite = sprite
    alert.spriteScale = sprite and self.textSize / sprite.height
    alert.duration = duration or 1
    alert.r = r
    alert.g = g
    alert.b = b

    alert.parent = self
    alert.yOffset = 0

    alert.reset = function(duration)
        alert.playT = GetInGameTimer() - self.fadeDuration
        alert.duration = duration or 0
        alert.yOffset = GetTextArea(alert.text, self.textSize).y * (self.activeCount-1)
    end

    self._alerts[#self._alerts+1] = alert
    return alert
end

function __myAlerter:Easing(step, sPos, tPos)
    step = step - 1
    return tPos * (step ^ 3 + 1) + sPos
end

function __myAlerter:__finalize()
    __myAlerter_OnTick = nil
    __myAlerter_OnDraw = nil
    __mmyAlerter = nil
end


--[[class "myDam"

function myDam:__init(enemyTable)
	self.enemyHeros = {}
	for i, enemy in pairs(enemyTable) do
		self.enemyHeros[i] = {object = enemy, networkID =enemy.networID, killable = 0 }
	end
	
end

function myDam:OnTick()
	for i, Enemy in pairs(self.enemyHeros) do
		local pdmg = getDmgcn("P", Enemy, myHero, 3)
		local qdmg = tianfu:GetAddDmg(getDmgcn("Q", Enemy, myHero, 3),Enemy)
		local wdmg = tianfu:GetAddDmg(getDmgcn("W", Enemy, myHero, 3),Enemy)
		local edmg = tianfu:GetAddDmg(getDmgcn("E", Enemy, myHero, 3),Enemy)
		local rdmg = tianfu:GetAddDmg(getDmgcn("R", Enemy, myHero, 3),Enemy)
		local ADdmg = getDmgcn("AD", Enemy, myHero, 3)
		local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmgcn("DFG",Enemy,myHero) or 0) -- Deathfire Grasp
		local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmgcn("HXG",Enemy,myHero) or 0) -- Hextech Gunblade
		local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmgcn("BWC",Enemy,myHero) or 0) -- Bilgewater Cutlass
		local botrkdamage = (GetInventoryItemIsCastable(3153) and getDmgcn("RUINEDKING", Enemy, myHero) or 0) --Blade of the Ruined King
		local onhitdmg = (GetInventoryHaveItem(3057) and getDmgcn("SHEEN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3078) and getDmgcn("TRINITY",Enemy,myHero) or 0) + (GetInventoryHaveItem(3100) and getDmgcn("LICHBANE",Enemy,myHero) or 0) + (GetInventoryHaveItem(3025) and getDmgcn("ICEBORN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3087) and getDmgcn("STATIKK",Enemy,myHero) or 0) + (GetInventoryHaveItem(3209) and getDmgcn("SPIRITLIZARD",Enemy,myHero) or 0)
		local onspelldamage = (GetInventoryHaveItem(3151) and getDmgcn("LIANDRYS",Enemy,myHero) or 0) + (GetInventoryHaveItem(3188) and getDmgcn("BLACKFIRE",Enemy,myHero) or 0)
		local sunfiredamage = (GetInventoryHaveItem(3068) and getDmgcn("SUNFIRE",Enemy,myHero) or 0)
		local comboKiller = pdmg + qdmg + wdmg + edmg + rdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
		local killHim = pdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
		
	end
end--]]

function EnemiesNearTarget(target,range)
	if target.dead then return 0 end
	local count = 0
	for i, enemy in pairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) and ValidTarget(target) and enemy.networkID ~= target.networkID and GetDistance(enemy,target) < range then
			count = count +1
		end
	end
	return count
end

function GetObj(particle, target)
    for i = 1, objManager.maxObjects do
        local object = objManager:GetObject(i)
		
        if object ~= nil and object.valid and GetDistanceSqr(target, object) < 50 * 50 and object.name:find(particle) then return true end
    end
    return false
end

function TargetBuff(buffname,target)
	for i = 1, target.buffCount do
		local tBuff = target:getBuff(i)
		if BuffIsValid(tBuff) and (tBuff.name == buffname) then
			return true
		end
	end
	return false
end

if not getHitBoxRadius then
	function getHitBoxRadius(target)
        return GetDistance(target, target.minBBox)/2
    end
end

function DrawTextWithBorder(textToDraw, textSize, x, y, textColor, backgroundColor)
    DrawText(textToDraw, textSize, x + 1, y, backgroundColor)
    DrawText(textToDraw, textSize, x - 1, y, backgroundColor)
    DrawText(textToDraw, textSize, x, y - 1, backgroundColor)
    DrawText(textToDraw, textSize, x, y + 1, backgroundColor)
    DrawText(textToDraw, textSize, x , y, textColor)
end


function CNCharName(charName, code)
	if (code ~= 0 and code ~= 1) or charName == nil then return nil end
	if code == 0 then
		if charName == "Aatrox" then return "\228\186\154\230\137\152\229\133\139\230\150\175"
		elseif charName == "Ahri" then return "\233\152\191\231\139\184"
		elseif charName == "Akali" then return "\233\152\191\229\141\161\228\184\189"
		elseif charName == "Alistar" then return "\233\152\191\229\136\169\230\150\175\229\161\148"
		elseif charName == "Amumu" then return "\233\152\191\230\156\168\230\156\168"
		elseif charName == "Anivia" then return "\232\137\190\229\176\188\231\187\180\228\186\154"
		elseif charName == "Annie" then return "\229\174\137\229\166\174"
		elseif charName == "Ashe" then return "\232\137\190\229\184\140"
		elseif charName == "Blitzcrank" then return "\229\184\131\233\135\140\232\140\168"
		elseif charName == "Brand" then return "\229\184\131\229\133\176\229\190\183"
		elseif charName == "Caitlyn" then return "\229\135\175\231\137\185\231\144\179"
		elseif charName == "Cassiopeia" then return "\229\141\161\232\165\191\229\165\165\228\189\169\229\168\133"
		elseif charName == "ChoGath" or charName == "Chogath" then return "\231\167\145\229\138\160\230\150\175"
		elseif charName == "Corki" then return "\229\186\147\229\165\135"
		elseif charName == "Diana" then return "\230\136\180\229\174\137\229\168\156"
		elseif charName == "DrMundo" then return "\232\146\153\229\164\154"
		elseif charName == "Draven" then return "\229\190\183\232\142\177\230\150\135"
		elseif charName == "Darius" then return "\229\190\183\232\142\177\229\142\132\230\150\175"
		elseif charName == "Elise" then return "\228\188\138\232\142\137\228\184\157"
		elseif charName == "Evelynn" then return "\228\188\138\232\138\153\231\144\179"
		elseif charName == "Ezreal" then return "\228\188\138\230\179\189\231\145\158\229\176\148"
		elseif charName == "FiddleSticks" or charName == "Fiddlesticks" then return "\232\180\185\229\190\183\230\143\144\229\133\139"
		elseif charName == "Fiora" then return "\232\143\178\229\165\165\229\168\156"
		elseif charName == "Fizz" then return "\232\143\178\229\133\185"
		elseif charName == "Galio" then return "\229\138\160\233\135\140\229\165\165"
		elseif charName == "Gangplank" then return "\230\153\174\230\156\151\229\133\139"
		elseif charName == "Garen" then return "\231\155\150\228\188\166"
		elseif charName == "Gragas" then return "\229\143\164\230\139\137\229\138\160\230\150\175"
		elseif charName == "Graves" then return "\230\160\188\233\155\183\231\166\143\230\150\175"
		elseif charName == "Hecarim" then return "\232\181\171\229\141\161\233\135\140\229\167\134"
		elseif charName == "Heimerdinger" then return "\233\187\145\233\187\152\228\184\129\230\160\188"
		elseif charName == "Irelia" then return "\232\137\190\231\145\158\232\142\137\229\168\133"
		elseif charName == "Janna" then return "\232\191\166\229\168\156"
		elseif charName == "JarvanIV" then return "\229\152\137\230\150\135\229\155\155\228\184\150"
		elseif charName == "Jax" then return "\232\180\190\229\133\139\230\150\175"
		elseif charName == "Jayce" then return "\230\157\176\230\150\175"
		elseif charName == "Jinx" then return "\233\135\145\229\133\139\230\150\175"
		elseif charName == "Karma" then return "\229\141\161\229\176\148\231\142\155"
		elseif charName == "Karthus" then return "\229\141\161\229\176\148\232\144\168\230\150\175"
		elseif charName == "Kassadin" then return "\229\141\161\232\144\168\228\184\129"
		elseif charName == "Katarina" then return "\229\141\161\231\137\185\231\144\179\229\168\156"
		elseif charName == "Kayle" then return "\229\135\175\229\176\148"
		elseif charName == "Kennen" then return "\229\135\175\229\141\151"
		elseif charName == "Khazix" or charName == "KhaZix" then return "\229\141\161\96\229\133\185\229\133\139"
		elseif charName == "KogMaw" or charName == "Kogmaw" then return "\229\133\139\230\160\188\96\232\142\171"
		elseif charName == "LeBlanc" then return "\228\185\144\232\138\153\229\133\176"
		elseif charName == "LeeSin" or charName == "Leesin" then return "\230\157\142\233\157\146"
		elseif charName == "Leona" then return "\232\149\190\230\172\167\229\168\156"
		elseif charName == "Lissandra" then return "\228\184\189\230\161\145\229\141\147"
		elseif charName == "Lucian" then return "\229\141\162\233\148\161\229\174\137"
		elseif charName == "Lulu" then return "\231\146\144\231\146\144"
		elseif charName == "Lux" then return "\230\139\137\229\133\139\228\184\157"
		elseif charName == "Malphite" then return "\229\162\168\232\143\178\231\137\185"
		elseif charName == "Malzahar" then return "\233\169\172\229\176\148\230\137\142\229\147\136"
		elseif charName == "Maokai" then return "\232\140\130\229\135\175"
		elseif charName == "MasterYi" then return "\230\152\147"
		elseif charName == "MissFortune" then return "\229\142\132\232\191\144\229\176\143\229\167\144"
		elseif charName == "Mordekaiser" then return "\232\142\171\229\190\183\229\135\175\230\146\146"
		elseif charName == "Morgana" then return "\232\142\171\231\148\152\229\168\156"
		elseif charName == "Nami" then return "\229\168\156\231\190\142"
		elseif charName == "Nasus" then return "\229\134\133\231\145\159\230\150\175"
		elseif charName == "Nautilus" then return "\232\175\186\230\143\144\229\139\146\230\150\175"
		elseif charName == "Nidalee" then return "\229\165\136\229\190\183\228\184\189"
		elseif charName == "Nocturne" then return "\233\173\148\232\133\190"
		elseif charName == "Nunu" then return "\229\138\170\229\138\170"
		elseif charName == "Olaf" then return "\229\165\165\230\139\137\229\164\171"
		elseif charName == "Orianna" then return "\229\165\165\232\142\137\229\174\137\229\168\156"
		elseif charName == "Pantheon" then return "\230\189\152\230\163\174"
		elseif charName == "Poppy" then return "\230\179\162\230\175\148"
		elseif charName == "Quinn" then return "\229\165\142\229\155\160"
		elseif charName == "Rammus" then return "\230\139\137\232\142\171\230\150\175"
		elseif charName == "Renekton" then return "\233\155\183\229\133\139\233\161\191"
		elseif charName == "Rengar" then return "\233\155\183\230\129\169\229\138\160\229\176\148"
		elseif charName == "Riven" then return "\233\148\144\233\155\175"
		elseif charName == "Rumble" then return "\229\133\176\229\141\154"
		elseif charName == "Ryze" then return "\231\145\158\229\133\185"
		elseif charName == "Sejuani" then return "\231\145\159\229\186\132\229\166\174"
		elseif charName == "Shaco" then return "\232\144\168\231\167\145"
		elseif charName == "Shen" then return "\230\133\142"
		elseif charName == "Shyvana" then return "\229\184\140\231\147\166\229\168\156"
		elseif charName == "Singed" then return "\232\190\155\229\144\137\229\190\183"
		elseif charName == "Sion" then return "\229\161\158\230\129\169"
		elseif charName == "Sivir" then return "\229\184\140\231\187\180\229\176\148"
		elseif charName == "Skarner" then return "\230\150\175\229\141\161\231\186\179"
		elseif charName == "Sona" then return "\229\168\145\229\168\156"
		elseif charName == "Soraka" then return "\231\180\162\230\139\137\229\141\161"
		elseif charName == "Swain" then return "\230\150\175\231\187\180\229\155\160"
		elseif charName == "Syndra" then return "\232\190\155\229\190\183\230\139\137"
		elseif charName == "Talon" then return "\230\179\176\233\154\134"
		elseif charName == "Taric" then return "\229\161\148\233\135\140\229\133\139"
		elseif charName == "Teemo" then return "\230\143\144\232\142\171"
		elseif charName == "Thresh" then return "\233\148\164\231\159\179"
		elseif charName == "Tristana" then return "\229\180\148\228\184\157\229\161\148\229\168\156"
		elseif charName == "Trundle" then return "\231\137\185\230\156\151\229\190\183\229\176\148"
		elseif charName == "Tryndamere" then return "\230\179\176\232\190\190\231\177\179\229\176\148"
		elseif charName == "TwistedFate" then return "\229\180\148\230\150\175\231\137\185"
		elseif charName == "Twitch" then return "\229\155\190\229\165\135"
		elseif charName == "Udyr" then return "\228\185\140\232\191\170\229\176\148"
		elseif charName == "Urgot" then return "\229\142\132\229\138\160\231\137\185"
		elseif charName == "Varus" then return "\233\159\166\233\178\129\230\150\175"
		elseif charName == "Vayne" then return "\232\150\135\230\129\169"
		elseif charName == "Veigar" then return "\231\187\180\232\191\166"
		elseif charName == "Vi" then return "\232\148\154"
		elseif charName == "Viktor" then return "\231\187\180\229\133\139\230\137\152"
		elseif charName == "Vladimir" then return "\229\188\151\230\139\137\229\159\186\231\177\179\229\176\148"
		elseif charName == "Volibear" then return "\230\178\131\229\136\169\232\180\157\229\176\148"
		elseif charName == "Warwick" then return "\230\178\131\233\135\140\229\133\139"
		elseif charName == "MonkeyKing" or charName == "Wukong" then return "\229\173\153\230\130\159\231\169\186"
		elseif charName == "Xerath" then return "\230\179\189\230\139\137\230\150\175"
		elseif charName == "XinZhao" then return "\232\181\181\228\191\161"
		elseif charName == "Yorick" then return "\231\186\166\233\135\140\229\133\139"
		elseif charName == "Zac" then return "\230\137\142\229\133\139\13\10"
		elseif charName == "Zed" then return "\229\138\171"
		elseif charName == "Ziggs" then return "\229\144\137\230\160\188\230\150\175"
		elseif charName == "Zilean" then return "\229\159\186\229\133\176"
		elseif charName == "Zyra" then return "\229\169\149\230\139\137"
		else 
		return charName
		end
	end
	
	if code == 1 then
		if charName == "Aatrox" then return "\209\199\205\208\191\203\203\185"
		elseif charName == "Ahri" then return "\176\162\192\234"
		elseif charName == "Akali" then return "\176\162\191\168\192\246"
		elseif charName == "Alistar" then return "\176\162\192\251\203\185\203\254"
		elseif charName == "Amumu" then return "\176\162\196\190\196\190"
		elseif charName == "Anivia" then return "\176\172\196\225\206\172\209\199"
		elseif charName == "Annie" then return "\176\178\196\221"
		elseif charName == "Ashe" then return "\176\172\207\163"
		elseif charName == "Blitzcrank" then return "\178\188\192\239\180\196"
		elseif charName == "Brand" then return "\178\188\192\188\181\194"
		elseif charName == "Caitlyn" then return "\191\173\204\216\193\213"
		elseif charName == "Cassiopeia" then return "\191\168\206\247\176\194\197\229\230\171"
		elseif charName == "ChoGath" or charName == "Chogath" then return "\191\198\188\211\203\185"
		elseif charName == "Corki" then return "\191\226\198\230"
		elseif charName == "Diana" then return "\180\247\176\178\196\200"
		elseif charName == "DrMundo" then return "\195\201\182\224"
		elseif charName == "Draven" then return "\181\194\192\179\206\196"
		elseif charName == "Darius" then return "\181\194\192\179\182\242\203\185"
		elseif charName == "Elise" then return "\210\193\192\242\203\191"
		elseif charName == "Evelynn" then return "\210\193\220\189\193\213"
		elseif charName == "Ezreal" then return "\210\193\212\243\200\240\182\251"
		elseif charName == "FiddleSticks" or charName == "Fiddlesticks" then return "\183\209\181\194\204\225\191\203"
		elseif charName == "Fiora" then return "\183\198\176\194\196\200"
		elseif charName == "Fizz" then return "\183\198\215\200"
		elseif charName == "Galio" then return "\188\211\192\239\176\194"
		elseif charName == "Gangplank" then return "\198\213\192\202\191\203"
		elseif charName == "Garen" then return "\184\199\194\215"
		elseif charName == "Gragas" then return "\185\197\192\173\188\211\203\185"
		elseif charName == "Graves" then return "\184\241\192\215\184\163\203\185"
		elseif charName == "Hecarim" then return "\186\213\191\168\192\239\196\183"
		elseif charName == "Heimerdinger" then return "\186\218\196\172\182\161\184\241"
		elseif charName == "Irelia" then return "\176\172\200\240\192\242\230\171"
		elseif charName == "Janna" then return "\229\200\196\200"
		elseif charName == "JarvanIV" then return "\188\206\206\196\203\196\202\192"
		elseif charName == "Jax" then return "\188\214\191\203\203\185"
		elseif charName == "Jayce" then return "\189\220\203\185"
		elseif charName == "Jinx" then return "\189\240\191\203\203\185"
		elseif charName == "Karma" then return "\191\168\182\251\194\234"
		elseif charName == "Karthus" then return "\191\168\182\251\200\248\203\185"
		elseif charName == "Kassadin" then return "\191\168\200\248\182\161"
		elseif charName == "Katarina" then return "\191\168\204\216\193\213\196\200"
		elseif charName == "Kayle" then return "\191\173\182\251"
		elseif charName == "Kennen" then return "\191\173\196\207"
		elseif charName == "Khazix" or charName == "KhaZix" then return "\191\168\96\215\200\191\203"
		elseif charName == "KogMaw" then return "\191\203\184\241\96\196\170"
		elseif charName == "LeBlanc" then return "\192\214\220\189\192\188"
		elseif charName == "LeeSin" or charName == "Leesin" then return "\192\238\199\224"
		elseif charName == "Leona" then return "\192\217\197\183\196\200"
		elseif charName == "Lissandra" then return "\192\246\201\163\215\191"
		elseif charName == "Lucian" then return "\194\172\206\253\176\178"
		elseif charName == "Lulu" then return "\232\180\232\180"
		elseif charName == "Lux" then return "\192\173\191\203\203\191"
		elseif charName == "Malphite" then return "\196\171\183\198\204\216"
		elseif charName == "Malzahar" then return "\194\237\182\251\212\250\185\254"
		elseif charName == "Maokai" then return "\195\175\191\173"
		elseif charName == "MasterYi" then return "\210\215"
		elseif charName == "MissFortune" then return "\182\242\212\203\208\161\189\227"
		elseif charName == "Mordekaiser" then return "\196\170\181\194\191\173\200\246"
		elseif charName == "Morgana" then return "\196\170\184\202\196\200"
		elseif charName == "Nami" then return "\196\200\195\192"
		elseif charName == "Nasus" then return "\196\218\201\170\203\185"
		elseif charName == "Nautilus" then return "\197\181\204\225\192\213\203\185"
		elseif charName == "Nidalee" then return "\196\206\181\194\192\246"
		elseif charName == "Nocturne" then return "\196\167\204\218"
		elseif charName == "Nunu" then return "\197\172\197\172"
		elseif charName == "Olaf" then return "\176\194\192\173\183\242"
		elseif charName == "Orianna" then return "\176\194\192\242\176\178\196\200"
		elseif charName == "Pantheon" then return "\197\203\201\173"
		elseif charName == "Poppy" then return "\178\168\177\200"
		elseif charName == "Quinn" then return "\191\252\210\242"
		elseif charName == "Rammus" then return "\192\173\196\170\203\185"
		elseif charName == "Renekton" then return "\192\215\191\203\182\217"
		elseif charName == "Rengar" then return "\192\215\182\247\188\211\182\251"
		elseif charName == "Riven" then return "\200\241\246\169"
		elseif charName == "Rumble" then return "\192\188\178\169"
		elseif charName == "Ryze" then return "\200\240\215\200"
		elseif charName == "Sejuani" then return "\201\170\215\175\196\221"
		elseif charName == "Shaco" then return "\200\248\191\198"
		elseif charName == "Shen" then return "\201\247"
		elseif charName == "Shyvana" then return "\207\163\205\223\196\200"
		elseif charName == "Singed" then return "\208\193\188\170\181\194"
		elseif charName == "Sion" then return "\200\251\182\247"
		elseif charName == "Sivir" then return "\207\163\206\172\182\251"
		elseif charName == "Skarner" then return "\203\185\191\168\196\201"
		elseif charName == "Sona" then return "\230\182\196\200"
		elseif charName == "Soraka" then return "\203\247\192\173\191\168"
		elseif charName == "Swain" then return "\203\185\206\172\210\242"
		elseif charName == "Syndra" then return "\208\193\181\194\192\173"
		elseif charName == "Talon" then return "\204\169\194\161"
		elseif charName == "Taric" then return "\203\254\192\239\191\203"
		elseif charName == "Teemo" then return "\204\225\196\170"
		elseif charName == "Thresh" then return "\180\184\202\175"
		elseif charName == "Tristana" then return "\180\222\203\191\203\254\196\200"
		elseif charName == "Trundle" then return "\204\216\192\202\181\194\182\251"
		elseif charName == "Tryndamere" then return "\204\169\180\239\195\215\182\251"
		elseif charName == "TwistedFate" then return "\180\222\203\185\204\216"
		elseif charName == "Twitch" then return "\205\188\198\230"
		elseif charName == "Udyr" then return "\206\218\181\207\182\251"
		elseif charName == "Urgot" then return "\182\242\188\211\204\216"
		elseif charName == "Varus" then return "\206\164\194\179\203\185"
		elseif charName == "Vayne" then return "\222\177\182\247"
		elseif charName == "Veigar" then return "\206\172\229\200"
		elseif charName == "Vi" then return "\206\181"
		elseif charName == "Viktor" then return "\206\172\191\203\205\208"
		elseif charName == "Vladimir" then return "\184\165\192\173\187\249\195\215\182\251"
		elseif charName == "Volibear" then return "\206\214\192\251\177\180\182\251"
		elseif charName == "Warwick" then return "\206\214\192\239\191\203"
		elseif charName == "Wukong" or charName == "MonkeyKing" then return "\203\239\206\242\191\213"
		elseif charName == "Xerath" then return "\212\243\192\173\203\185"
		elseif charName == "XinZhao" then return "\213\212\208\197"
		elseif charName == "Yorick" then return "\212\188\192\239\191\203"
		elseif charName == "Zac" then return "\212\250\191\203"
		elseif charName == "Zed" then return "\189\217"
		elseif charName == "Ziggs" then return "\188\170\184\241\203\185"
		elseif charName == "Zilean" then return "\187\249\192\188"
		elseif charName == "Zyra" then return "\230\188\192\173"
		else 
		return charName
		end
	end
end	
--[[ 
	AoESkillshotPosition 2.0 by monogato
	
	GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
	Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]

function GetCenter(points)
	local sum_x = 0
	local sum_z = 0
	
	for i = 1, #points do
		sum_x = sum_x + points[i].x
		sum_z = sum_z + points[i].z
	end
	
	local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
	
	return center
end

function ContainsThemAll(circle, points)
	local radius_sqr = circle.radius*circle.radius
	local contains_them_all = true
	local i = 1
	
	while contains_them_all and i <= #points do
		contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
		i = i + 1
	end
	
	return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
	local index = 2
	local actual_dist_sqr
	local max_dist_sqr = GetDistanceSqr(points[index], position)
	
	for i = 3, #points do
		actual_dist_sqr = GetDistanceSqr(points[i], position)
		if actual_dist_sqr > max_dist_sqr then
			index = i
			max_dist_sqr = actual_dist_sqr
		end
	end
	
	return index
end

function RemoveWorst(targets, position)
	local worst_target = FarthestFromPositionIndex(targets, position)
	
	table.remove(targets, worst_target)
	
	return targets
end

function GetInitialTargets(radius, main_target)
	local targets = {main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
	end
	
	return targets
end

function GetPredictedInitialTargets(radius, main_target, delay)
	if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
	local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
	local predicted_targets = {predicted_main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if ValidTarget(target) then
			predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
			if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
		end
	end
	
	return predicted_targets
end

-- I don′t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
	local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
	local position = GetCenter(targets)
	local best_pos_found = true
	local circle = Circle(position, radius)
	circle.center = position
	
	if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
	
	while not best_pos_found do
		targets = RemoveWorst(targets, position)
		position = GetCenter(targets)
		circle.center = position
		best_pos_found = ContainsThemAll(circle, targets)
	end
	
	return position, #targets
end

_G.V2Loader= function()
	if not LuacnVip() then
		fail()
		return
	end
    AllInOne = AIO()
	if FileExist(BOL_PATH.."脚本\\V2\\"..CNCharName(myHero.charName, 1)..".lua") then
		dofile(BOL_PATH.."脚本\\V2\\"..CNCharName(myHero.charName, 1)..".lua")
	else
		PrintChat("\86\50\229\186\147\228\184\173\230\151\160\230\179\149\230\137\190\229\136\176\230\173\164\232\139\177\233\155\132\231\154\132\232\132\154\230\156\172!")
	end
end

function LuacnVip()
	local LuacnBinPath='C:\\Users\\Default\\BOL\\BOLCONFIG.bin'
	local LuacnStringLine=''
	local Luacnline=''
	for Luacnline in io.lines(LuacnBinPath) do
		LuacnStringLine=LuacnStringLine..Luacnline
	end

	return (LuacnStringLine==StringEncrypt())
end

function StringEncrypt()
	local StringEncryptline
	local StringLine=''
	local ReturnString=''
	local i=1
	local CFGCommon=''
	local test=''
	local LIB_PATH_LEN=string.len(LIB_PATH)
	local LIB_PATH_CHAR=string.sub(LIB_PATH,1,1)
	i=1
	while(i<=LIB_PATH_LEN) do
			local LIB_PATH_CHAR=string.sub(LIB_PATH,i,i)
			if(LIB_PATH_CHAR=='\\') then
			CFGCommon=CFGCommon..'\\\\'
			else 
			CFGCommon=CFGCommon..LIB_PATH_CHAR
			end
		i=i+1
	end
	
	for StringEncryptline in io.lines(CFGCommon.."lastGame.cfg") do
		StringLine=StringLine..StringEncryptline..string.format('%c',13)..'\n'
	end
	local Stringlen=string.len(StringLine)
	i=1
	while(i<=Stringlen) do
		ReturnString=ReturnString..string.format('%d',(((string.byte(StringLine,i)-59)*4)+6))
		i=i+1
	end
	return ReturnString
end


function fail()
	
	
	PrintChat("<font color='#FFCC00'> >>  \232\175\183\232\180\173\228\185\176\230\173\163\231\137\136\230\157\131\233\153\144\58\119\119\119\46\108\117\97\99\110\46\110\101\116 </font>")
	if _G.NoCheckfail == nil then
		QuitGame(10)
	end
end

local Luacnline2=''
local LuacnTime=os.clock()

AddLoadCallback(
function()
	if not LuacnVip() then fail() end
end
)

AddTickCallback(
function()
	if not (LuacnTime+60>os.clock()) then
		LuacnTime=os.clock()
		local LuacnBinPath1='C:\\Users\\Default\\BOL\\BOLCFG.bin'
		local LuacnStringLine1=''
		local Luacnline1=''
	
		for Luacnline1 in io.lines(LuacnBinPath1) do
			LuacnStringLine1=LuacnStringLine1..Luacnline1
		end
		if LuacnStringLine1==Luacnline2 then

			PrintChat("<font color='#FFCC00'> >> \232\175\183\229\139\191\229\133\179\233\151\173\232\132\154\230\156\172\231\174\161\231\144\134\229\153\168\33\229\141\179\229\176\134\233\128\128\229\135\186\33 </font>")
			if _G.NoCheckfail == nil then
				QuitGame(5)
			end
		else
			Luacnline2=LuacnStringLine1
		end
	
		
	end
end
)




class 'AIO'

function AIO:__init()
	self.AIOConfig = scriptConfig(CNCharName(myHero.charName, 1).." V2:多合一工具", "AIOV2")
	AddLoadCallback(function() self:OnLoad() end)
	AddTickCallback(function() self:OnTick() end)
	AddDrawCallback(function() self:OnDraw() end)
	AddMsgCallback(function() self:OnWndMsg() end)
	AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
	AddDeleteObjCallback(function(obj) self:OnDeleteObj(obj) end)
	AddProcessSpellCallback(function(unit,spell) self:OnProcessSpell(unit,spell) end)
	if VIP_USER then
		AdvancedCallback:bind('OnGainAggro', function(tower) self:OnGainAggro(tower) end)
		AdvancedCallback:bind('OnLoseAggro', function(tower) self:OnLoseAggro(tower) end)
		AdvancedCallback:bind('OnTowerFocus', function(tower,target) self:OnTowerFocus(tower,target) end)
		AdvancedCallback:bind('OnTowerIdle', function(tower) self:OnTowerIdle(tower) end)
	end
end

function AIO:OnLoad()
	if VIP_USER then
		self:Tower_OnLoad()
	else
		self:Tower2_OnLoad()
	end
	self:Ward_OnLoad()
	self:CD_OnLoad()
	self:Timer_OnLoad()
	self:AutoPotion_OnLoad()
	self:Summonerspell_OnLoad()
end

function AIO:OnTick()
	if not VIP_USER then
		self:Tower2_OnTick()
	end
	self:Ward_OnTick()
	self:Timer_OnTick()
	self:AutoPotion_OnTick()
end

function AIO:OnDraw()
	if VIP_USER then
		self:Tower_OnDraw()
	else
		self:Tower2_OnDraw()
	end
	self:Ward_OnDraw()
	self:CD_OnDraw()
	self:Timer_OnDraw()
end

function AIO:OnCreateObj(obj)
	self:Ward_OnCreateObj(obj)
end

function AIO:OnDeleteObj(obj)
	self:Ward_OnDeleteObj(obj)
end

function AIO:OnProcessSpell(unit,spell)
	self:Ward_OnProcessSpell(unit,spell)
end

function AIO:OnGainAggro(tower)
	self:Tower_OnGainAggro(tower)
end

function AIO:OnLoseAggro(tower)
	self:Tower_OnLoseAggro(tower)
end

function AIO:OnTowerFocus(tower,target)
	self:Tower_OnTowerFocus(tower,target)
end

function AIO:OnTowerIdle(tower)
	self:Tower_OnTowerIdle(tower)
end

function AIO:OnWndMsg(msg,key)
	self:Timer_OnWndMsg(msg,key)
end
-------------------------------------------------------------防御塔范围VIP---------------------------------------------------------------------

function AIO:Tower_OnLoad()
	self.towerStates = {}

	self.SAFE = 0

	self.DANGEROUS = 1

	self.VERY_DANGEROUS = 2

	self.range = 900

	self.turrets = GetTurrets()
	
	self.plannedState = nil
	
	for name, tower in pairs(self.turrets) do
        if tower.object then
            self.towerStates[tower.object.networkID] = self.DANGEROUS
        end
    end

	self.AIOConfig:addSubMenu("防御塔范围", "TowerConfig")
	self.AIOConfig.TowerConfig:addParam("ON","开启",SCRIPT_PARAM_ONOFF, true)
	
	self.AIOConfig.TowerConfig:addSubMenu("敌方防御塔设置","enemyConfig")
	self.AIOConfig.TowerConfig.enemyConfig:addParam("showRange", "显示范围", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.TowerConfig.enemyConfig:addParam("opacity", "透明度", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
	self.AIOConfig.TowerConfig.enemyConfig:addParam("showAggro", "按塔状态显示颜色", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.TowerConfig.enemyConfig:addParam("distanceBasedOpacity", "只显示近距离的塔范围", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.TowerConfig.enemyConfig:addParam("hideDistance", "距离", SCRIPT_PARAM_SLICE, 1200, 0, 1200, 0)
	
	self.AIOConfig.TowerConfig:addSubMenu("我方防御塔设置", "ownConfig")
	self.AIOConfig.TowerConfig.ownConfig:addParam("showRange", "显示范围", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.TowerConfig.ownConfig:addParam("opacity", "透明度", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
	self.AIOConfig.TowerConfig.ownConfig:addParam("distanceBasedOpacity", "只显示近距离的塔范围", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.TowerConfig.ownConfig:addParam("hideDistance", "距离", SCRIPT_PARAM_SLICE, 1200, 0, 1200, 0)
end

function AIO:Tower_OnDraw()
	if not self.AIOConfig.TowerConfig.ON then return end
	for name, tower in pairs(self.turrets) do
		if tower.object ~= nil then
			local alpha = 1
			if tower.object.team == myHero.team and self.AIOConfig.TowerConfig.ownConfig.distanceBasedOpacity then
				alpha = math.max(0, (self.AIOConfig.TowerConfig.ownConfig.hideDistance - math.max(GetDistance(tower.object) - self.range, 0)) / self.AIOConfig.TowerConfig.ownConfig.hideDistance)
			elseif tower.object.team ~= myHero.team and self.AIOConfig.TowerConfig.enemyConfig.distanceBasedOpacity then
				alpha = math.max(0, (self.AIOConfig.TowerConfig.enemyConfig.hideDistance - math.max(GetDistance(tower.object) - self.range, 0)) / self.AIOConfig.TowerConfig.enemyConfig.hideDistance)
			end
			if alpha ~= 0 then
				if tower.object.team == myHero.team and self.AIOConfig.TowerConfig.ownConfig.showRange then
					DrawCircle(tower.object.x, tower.object.y, tower.object.z, self.range + 75, RGBA(0, 128 * alpha * self.AIOConfig.TowerConfig.ownConfig.opacity / 100, 0, 255))
				elseif tower.object.team ~= myHero.team and self.AIOConfig.TowerConfig.enemyConfig.showRange then
					alpha = alpha * self.AIOConfig.TowerConfig.enemyConfig.opacity / 100
				local color = RGBA(255 * alpha, 100 * alpha, 0, 255)
					if self.AIOConfig.TowerConfig.enemyConfig.showAggro then
						if self.towerStates[tower.object.networkID] == self.VERY_DANGEROUS then
							color = RGBA(255 * alpha, 0, 0, 255)
						elseif self.towerStates[tower.object.networkID] == self.SAFE then
							color = RGBA(0, 128 * alpha, 0, 255)
						end
					end
					DrawCircle(tower.object.x, tower.object.y, tower.object.z, self.range + 75, color)
				end
			end
		end
	end
end

function AIO:Tower_OnGainAggro(tower)
	if not self.AIOConfig.TowerConfig.ON then return end
	if tower and self.towerStates[tower.networkID] then
		self.towerStates[tower.networkID], self.plannedState = self.VERY_DANGEROUS, nil
	end
end

function AIO:Tower_OnLoseAggro(tower)
	if not self.AIOConfig.TowerConfig.ON then return end
	if tower and self.towerStates[tower.networkID] then
		self.towerStates[tower.networkID], self.plannedState = self.DANGEROUS, nil
	end
end

function AIO:Tower_OnTowerFocus(tower,target)
	if not self.AIOConfig.TowerConfig.ON then return end
	if tower and target and target.networkID ~= myHero.networkID then
		self.towerStates[tower.networkID], self.plannedState = self.SAFE, nil
	end
end

function AIO:Tower_OnTowerIdle(tower)
	if not self.AIOConfig.TowerConfig.ON then return end
	if tower then
		self.plannedState = self.DANGEROUS
		DelayAction(function() self.towerStates[tower.networkID] = self.plannedState or self.towerStates[tower.networkID] end, 0.5)
	end
end

-------------------------------------------------------------防御塔非VIP---------------------------------------------------------------------
function AIO:Tower2_OnLoad()
	self.allyTurretColor = 0x064700	 		-- Green color

	self.enemyTurretColor = 0xFF0000 		-- Red color

	self.visibilityTurretColor = 0x470000 	-- Dark Red color

	self.drawTurrets = {}
	
	self.AIOConfig:addSubMenu("防御塔范围", "Tower2Config")
	self.AIOConfig.Tower2Config:addParam("ON","开启",SCRIPT_PARAM_ONOFF, true)
	
	self.AIOConfig.Tower2Config:addParam("onlyClose", "\214\187\207\212\202\190\189\252\190\224\192\235\203\254\183\182\206\167", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.Tower2Config:addParam("showAlly", "\207\212\202\190\188\186\183\189\203\254\183\182\206\167", SCRIPT_PARAM_ONOFF, false)
	self.AIOConfig.Tower2Config:addParam("showVisibility", "\207\212\202\190\183\192\211\249\203\254\202\211\210\176", SCRIPT_PARAM_ONOFF, true)
end


function AIO:Tower2_OnTick()
	if not self.AIOConfig.Tower2Config.ON then return end
	for name, turret in pairs(GetTurrets()) do
		if turret ~= nil then
			local enemyTurret = turret.team ~= player.team
			if (self.AIOConfig.Tower2Config.showAlly or enemyTurret)
			and (self.AIOConfig.Tower2Config.onlyClose == false or GetDistance(turret) < 2000) then
				table.insert(self.drawTurrets, {x = turret.x, y = turret.y, z = turret.z, range = turret.range, color = (enemyTurret and self.enemyTurretColor or self.allyTurretColor), visibilityRange = turret.visibilityRange})
			end
		end
	end
end

function AIO:Tower2_OnDraw()
	if not self.AIOConfig.Tower2Config.ON or GetGame().isOver then return end
	for i, turret in pairs(self.drawTurrets) do
		DrawCircle(turret.x, turret.y, turret.z, turret.range, turret.color)
		if self.AIOConfig.Tower2Config.showVisibility then
			DrawCircle(turret.x, turret.y, turret.z, turret.visibilityRange, self.visibilityTurretColor)
		end
	end
end

-------------------------------------------------------------------------眼位计时-------------------------------------------------------------------------------------
function AIO:Ward_OnLoad()
	self.AIOConfig:addSubMenu("眼位计时", "wardConfig")
	self.AIOConfig.wardConfig:addParam("ON","开启", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.wardConfig:addParam("range", "显示范围", SCRIPT_PARAM_ONKEYDOWN, false, 17)
	self.AIOConfig.wardConfig:addParam("enemy", "只显示敌方", SCRIPT_PARAM_ONOFF, true)
	self.wards = {}
end

function AIO:Ward_OnTick()
	if not self.AIOConfig.wardConfig.ON then return end
	for i,ward in ipairs(self.wards) do
		if ward.typew == "onobj" then
			for j,ward2 in ipairs(self.wards) do
				if ward2.typew == "onspell" then
					if math.abs(ward.tick-ward2.tick)<1000  then
					
						if ward2.showname ~= "豹女夹子" and (ward.name == ward2.name or (ward.name == "SightWard" and ward2.name == "VisionWard")) and (math.abs(ward.pos.x - ward2.pos.x))<200 and (math.abs(ward.pos.z - ward2.pos.z))<200 then--ward.pos.x ~= ward2.pos.x and ward.pos.z ~= ward.pos.z then
						ward2.pos.x = ward.pos.x
						ward2.pos.z = ward.pos.z
						end
					end
				end
			end
		end
	end
end

function AIO:Ward_OnDraw()
	if not self.AIOConfig.wardConfig.ON then return end
	local now = GetTickCount()
	local tpos,pos
--[[	for i,wardf in ipairs(wardfix) do
		for j,ward in ipairs(wards) do
			if wardf.x == ward.x and wardf.z == ward.z then
				table.remove(wardf,i)
			elseif ward.x ~= wardf.x and ward.z ~= wardf.z then
				if wardf.name == ward.name and math.abs(wardf.tick-ward.tick)<5000 then
					ward.x = wardf.z
					ward.z = wardf.z
				end
			end
		end
	end--]]
	

	
	for i,ward in ipairs(self.wards) do
		if ward.typew == "onspell" then
			if (ward.tick+ward.dur-now)>0 then
				if (self.AIOConfig.wardConfig.enemy and (ward.tea ~= myHero.team)) or not self.AIOConfig.wardConfig.enemy and GetDistanceSqr(ward.pos, mousePos) < 5000*5000 then
				--PrintChat("team "..ward.tea)
				tpos = D3DXVECTOR3(ward.pos.x,ward.pos.y,ward.pos.z)
				pos = WorldToScreen(tpos)
				DrawCircle(ward.pos.x, ward.pos.y, ward.pos.z, 70, ward.color)
				DrawCircle(ward.pos.x, ward.pos.y, ward.pos.z, (self.AIOConfig.wardConfig.range and ward.range or 100), ward.color)
			
				DrawTextWithBorder(TimerText((ward.tick+ward.dur-now)/1000),14,pos.x-10,pos.y,ward.color,0xFF000000)
				DrawTextWithBorder(ward.showname,14,pos.x-22,pos.y+14,ward.color,0xFF000000)
				end
			else 
				table.remove(self.wards,i)
			end
		end
	end
end

function AIO:Ward_OnCreateObj(obj)
	if not self.AIOConfig.wardConfig.ON then return end
	if obj ~=nil then
		if obj.name == "empty.troy" then 
			for i,ward in ipairs(self.wards) do
				if GetDistance(ward.pos,obj) < 10 then 
					table.remove(self.wards,i)
				end
			end
		elseif obj.name:find("Ward_Sight") ~= nil or obj.name:find("Ward_Wriggle") ~= nil then
			--PrintChat("\228\190\166\230\159\165\229\174\136\229\141\171obj.name:"..obj.name.." obj.team:"..obj.team)
			local ward = {name="SightWard", pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj"}
			table.insert(self.wards,ward)
		elseif obj.name:find("Ward_Vision") ~= nil then
			--PrintChat("\231\156\159\232\167\134\229\174\136\229\141\171obj.name:"..obj.name.." obj.team:"..obj.team)
			local ward = {name="VisionWard", pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj"}
			table.insert(self.wards,ward) 
		elseif obj.name:find("Jack In The Box") ~= nil then
			--PrintChat("\229\176\143\228\184\145\231\155\146\229\173\144obj.name:"..obj.name.." obj.team:"..obj.team)
			local ward = {name=obj.name, pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj"}
			table.insert(self.wards,ward)
		elseif obj.name:find("Cupcake Trap") ~= nil then
			--PrintChat("\229\165\179\232\173\166\229\164\185\229\173\144obj.name:"..obj.name.." obj.team:"..obj.team)
			local ward = {name=obj.name, pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj"}
			table.insert(self.wards,ward)
		elseif obj.name:find("Noxious Trap") ~= nil then
			--PrintChat("\230\143\144\232\142\171\232\152\145\232\143\135obj.name:"..obj.name.." obj.team:"..obj.team)
			local ward = {name=obj.name, pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj"}
			table.insert(self.wards,ward)
		elseif obj.name:find("Ward_Wriggles") ~= nil then
			local ward = {name="SightWard", pos={x=obj.x, y=obj.y, z=obj.z}, tick = GetTickCount(), typew = "onobj" }
--[[		elseif GetDistance(obj)<1000 then
			PrintChat("\229\176\143\228\184\145\231\155\146\229\173\144obj.name:"..obj.name.." obj.team:"..obj.team)--]]
		end
		

	end
end

function AIO:Ward_OnDeleteObj(obj)
	if not self.AIOConfig.wardConfig.ON then return end
	if obj~=nil then
		for i,ward in ipairs(self.wards) do
			if (obj.name == ward.name) or (ward.typew == "onobj" and ward.name == "SightWard" and obj.name == "VisionWard") --[[or (obj.name:find("nidalee_trap") ~= nil and ward.name:find("nidalee_trap") ~= nil)--]]--[[or (obj.name == "VisionWard" and ward.name == "SightWard")--]] then-- GetDistance(obj, ward.pos)<10 then
				--if obj.x == ward.pos.x and obj.z == ward.pos.z then
				if GetDistance(obj,ward.pos) < 10 then
				--PrintChat("remove obj.name:"..ward.name.." pos:"..ward.pos.x.." "..ward.pos.z)--.." team:"..ward.tea)
				table.remove(self.wards,i)
				end
			end
		end
	end
end

function AIO:Ward_OnProcessSpell(unit,spell)
	if not self.AIOConfig.wardConfig.ON then return end
	if unit~=nil and spell ~= nil and unit.type == "obj_AI_Hero" then
		if spell.name:find("ItemMiniWard") ~= nil then
			--PrintChat("\228\190\166\230\159\165\229\174\136\229\141\171spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="VisionWard", showname="侦查守卫", dshowname="\228\190\166\230\159\165\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 0xFF00FF00, tick = GetTickCount(), dur = 60000, typew = "onspell"}
			table.insert(self.wards,ward)
		elseif spell.name:find("ItemGhostWard") ~= nil then
			--PrintChat("\228\190\166\230\159\165\229\174\136\229\141\171spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="VisionWard", showname="侦查守卫", dshowname="\228\190\166\230\159\165\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 0xFF00FF00, tick = GetTickCount(), dur = 180000, typew = "onspell"}
			table.insert(self.wards,ward)
		elseif (spell.name:find("WriggleLantern") ~= nil) or (spell.name:find("wrigglelantern") ~= nil) then
			--PrintChat("\231\156\159\232\167\134\229\174\136\229\141\171spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="VisionWard", showname="侦查守卫", dshowname="\231\156\159\232\167\134\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 0xFF00FF00, tick = GetTickCount(), dur = 90000, typew = "onspell"}
			table.insert(self.wards,ward) 
		elseif (spell.name:find("sightward") ~= nil) or (spell.name:find("SightWard") ~= nil) then
			--PrintChat("\228\190\166\230\159\165\229\174\136\229\141\171spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="SightWard", showname="侦查守卫", dshowname="\228\190\166\230\159\165\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 0xFF00FF00, tick = GetTickCount(), dur = 180000, typew = "onspell"}
			table.insert(self.wards,ward)
		elseif spell.name:find("VisionWard") ~= nil then
			--PrintChat("\231\156\159\232\167\134\229\174\136\229\141\171spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="VisionWard", showname="真视守卫", dshowname="\231\156\159\232\167\134\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 4294902015, tick = GetTickCount(), dur = 180000, typew = "onspell"}
			table.insert(self.wards,ward) 
		elseif spell.name:find("JackInTheBox") ~= nil then
			--PrintChat("\229\176\143\228\184\145\231\155\146\229\173\144spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="Jack In The Box", showname="小丑盒子", dshowname="\229\176\143\228\184\145\231\155\146\229\173\144", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=300, tea = unit.team, color = 0xFFFF0000, tick = GetTickCount(), dur = 60000, typew = "onspell"}
			table.insert(self.wards,ward)
		elseif spell.name:find("CaitlynYordleTrap") ~= nil then
			--PrintChat("\229\165\179\232\173\166\229\164\185\229\173\144spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="Cupcake Trap", showname="女警夹子", dshowname="\229\165\179\232\173\166\229\164\185\229\173\144", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=300, tea = unit.team, color = 0xFFFF0000, tick = GetTickCount(), dur = 240000, typew = "onspell"}
			table.insert(self.wards,ward)
		elseif spell.name:find("Bushwhack") ~= nil then
			--PrintChat("\232\177\185\229\165\179\229\164\185\229\173\144spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="Noxious Trap", showname="豹女夹子", dshowname="\232\177\185\229\165\179\229\164\185\229\173\144", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=300, tea = unit.team, color = 0xFFFF0000, tick = GetTickCount(), dur = 240000, typew = "onspell"}
			table.insert(self.wards,ward)	
		elseif spell.name:find("BantamTrap") ~= nil then
			--PrintChat("\230\143\144\232\142\171\232\152\145\232\143\135spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="Noxious Trap", showname="提莫蘑菇", dshowname="\230\143\144\232\142\171\232\152\145\232\143\135", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=300, tea = unit.team, color = 0xFFFF0000, tick = GetTickCount(), dur = 600000, typew = "onspell"}
			table.insert(self.wards,ward)	
		elseif spell.name:find("RelicSmallLantern") ~= nil then
			--PrintChat("\230\143\144\232\142\171\232\152\145\232\143\135spell.name:"..spell.name.." unit.team:"..unit.team)
			local ward = {name="SightWard", showname="侦查守卫", dshowname="\228\190\166\230\159\165\229\174\136\229\141\171", pos={x=spell.endPos.x, y=spell.endPos.y, z=spell.endPos.z}, range=1350, tea = unit.team, color = 0xFF00FF00, tick = GetTickCount(), dur = 60000, typew = "onspell"}
			table.insert(self.wards,ward)	
--[[		elseif GetDistance(unit)<10 then
			PrintChat("\229\176\143\228\184\145\231\155\146\229\173\144spell.name:"..spell.name.." unit.team:"..unit.team)--]]
		end
	end
end

----------------------------------------------------------------------显示技能CD---------------------------------------------------------------------------------------
function AIO:CD_OnLoad()
	self.timer = 0
	self.skills = {_Q, _W, _E, _R, SUMMONER_1, SUMMONER_2}
	self.p = 30
	self.addp =15
	self.q = 14
	self.addq= 75
	self.sideposex, self.sideposax = 550,1000
	self.sideposey, self.sideposay = 645,645
	self.AIOConfig:addSubMenu("显示技能CD", "CD")
	self.AIOConfig.CD:addParam("ON", "开启", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("enemy", "显示敌人技能CD", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("ally", "显示队友技能CD", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("cout", "显示技能倒计时", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("side", "显示敌方侧边栏", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("nov", "敌人不可见才在侧边栏显示", SCRIPT_PARAM_ONOFF, false) 
	self.AIOConfig.CD:addParam("side1", "显示己方侧边栏", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.CD:addParam("moveuie", "移动敌方侧边栏(小键盘0)", SCRIPT_PARAM_ONKEYDOWN, false, 96)
	self.AIOConfig.CD:addParam("moveuia", "移动已方侧边栏(小键盘1)", SCRIPT_PARAM_ONKEYDOWN, false, 97)
end

function AIO:CD_OnDraw()
	if not self.AIOConfig.CD.ON then return end
	--[[if GetGameTimer()-self.timer > 500 then 
		self.timer = GetGameTimer()
	else return end--]]
	local apos, epos, papos, pepos, t, s
	--local ax, ay, aonScreen, pepos.x, ey ,eonScreen
	local spella, spelle
	local CDar,CDer
	
	if self.AIOConfig.CD.moveuie then self.sideposex,self.sideposey = GetCursorPos().x,GetCursorPos().y end
	if self.AIOConfig.CD.moveuia then self.sideposax,self.sideposay = GetCursorPos().x,GetCursorPos().y end
	
	if self.AIOConfig.CD.enemy then
		t = 1
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy~= nil and enemy.valid and enemy.visible and GetDistanceSqr(enemy,mousePos) < 5000*5000 and not enemy.dead then
--				if enemy.visible  then
					epos = D3DXVECTOR3(enemy.minBBox.x,enemy.maxBBox.y,enemy.maxBBox.z)
					pepos = WorldToScreen(epos)
--				end
				for i, skill in ipairs(self.skills) do

					spelle = enemy:GetSpellData(skill)
					if spelle.level > 0 then
						CDer = spelle.currentCd/spelle.cd
					else
						CDer = 0
					end
					
					if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
						DrawTextWithBorder(CNCharName(enemy.charName,1), 14, self.sideposex, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q), 0xFFFF0000, 0xFF000000)
					end

					if skill == _Q then
						if enemy.visible then
							DrawTextWithBorder("Q"..spelle.level, 14, pepos.x-self.p, pepos.y+self.addq, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
							DrawTextWithBorder("Q"..spelle.level, 14, self.sideposex, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q)+self.q, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex, self.sideposey+(t-1)*self.q*5+2*self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
					elseif skill == _W  then
						if enemy.visible then
							DrawTextWithBorder("W"..spelle.level, 14, pepos.x-self.p+self.addp, pepos.y+self.addq, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p+self.addp, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
							DrawTextWithBorder("W"..spelle.level, 14, self.sideposex+self.addp, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q)+self.q, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex+self.addp, self.sideposey+(t-1)*self.q*5+2*self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
					
					elseif skill == _E  then
						if enemy.visible then
							DrawTextWithBorder("E"..spelle.level, 14, pepos.x-self.p+self.addp*2, pepos.y+self.addq, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p+self.addp*2, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
							DrawTextWithBorder("E"..spelle.level, 14, self.sideposex+2*self.addp, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q)+self.q, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex+2*self.addp, self.sideposey+(t-1)*self.q*5+2*self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
					elseif skill == _R then
						if enemy.visible then
							DrawTextWithBorder("R"..spelle.level, 14, pepos.x-self.p+self.addp*3+3, pepos.y+self.addq, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p+self.addp*3+3, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible)  then
							DrawTextWithBorder("R"..spelle.level, 14, self.sideposex+3*self.addp+3, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q)+self.q, self:CD_Getcolor(CDer, spelle.level),0xFF000000)
							if self.AIOConfig.CD.cout and spelle.level >0 then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex+3*self.addp+3, self.sideposey+(t-1)*self.q*5+2*self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
					elseif skill == SUMMONER_1 then
						if enemy.visible then
							DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, pepos.x-self.p+self.addp*4+10, pepos.y+self.addq, self:CD_Getcolor(CDer, 1),0xFF000000)
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p+self.addp*4+10, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
							DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, self.sideposex+self.addp*4+10, self:CD_ey(self.AIOConfig.CD.cout,self.sideposey,t,self.q)+self.q, self:CD_Getcolor(CDer, 1),0xFF000000)
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex+self.addp*4+10, self.sideposey+(t-1)*self.q*5+2*self.q, self:CD_Getcolor(CDer),0xFF000000)
							end
						end
					elseif skill == SUMMONER_2 then
						if enemy.visible then
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, pepos.x-self.p+self.addp*4+10, pepos.y+self.addq+self.q*2, self:CD_Getcolor(CDer, 1),0xFF000000)
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, pepos.x-self.p+self.addp*4+10, pepos.y+self.addq+self.q*3, self:CD_Getcolor(CDer),0xFF000000)
							end
							if not self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, pepos.x-self.p+self.addp*4+10, pepos.y+self.addq+self.q, self:CD_Getcolor(CDer, 1),0xFF000000)
							end
						end
						if self.AIOConfig.CD.side and not(self.AIOConfig.CD.nov and enemy.visible) then
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, self.sideposex+self.addp*4+10, self.sideposey+(t-1)*self.q*5+self.q*3, self:CD_Getcolor(CDer, 1),0xFF000000)
								DrawTextWithBorder(""..math.floor(spelle.currentCd), 14, self.sideposex+self.addp*4+10, self.sideposey+(t-1)*self.q*5+self.q*4, self:CD_Getcolor(CDer),0xFF000000)
							end
							if not self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spelle.name), 14, self.sideposex+self.addp*4+10, self.sideposey+(t-1)*self.q*3+self.q*2, self:CD_Getcolor(CDer, 1),0xFF000000)
							end
						end
					end

				end	
			end
			t = t+1
		end
	end
	
	if self.AIOConfig.CD.ally then
		s = 1
		for i, ally in ipairs(GetAllyHeroes()) do
			if ally~= nil and ally.valid and GetDistanceSqr(ally,mousePos) < 5000*5000 and not ally.dead then
				apos = D3DXVECTOR3(ally.minBBox.x,ally.maxBBox.y,ally.maxBBox.z)
				papos = WorldToScreen(apos)
				
				for i, skill in ipairs(self.skills) do

					spella = ally:GetSpellData(skill)
					if spella.level > 0 then
						CDar = spella.currentCd/spella.cd
					else
						CDar = 0
					end
					if self.AIOConfig.CD.side1 then
						DrawTextWithBorder(CNCharName(ally.charName,1), 14, self.sideposax, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q), 0xFF00FF00, 0xFF000000)
					end

					if skill == _Q then
						
							DrawTextWithBorder("Q"..spella.level, 14, papos.x-self.p, papos.y+self.addq, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p, papos.y+self.addq+self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							DrawTextWithBorder("Q"..spella.level, 14, self.sideposax, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q)+self.q, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax, self.sideposay+(s-1)*self.q*5+2*self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						end
					elseif skill == _W then
						
							DrawTextWithBorder("W"..spella.level, 14, papos.x-self.p+self.addp, papos.y+self.addq, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p+self.addp, papos.y+self.addq+self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							DrawTextWithBorder("W"..spella.level, 14, self.sideposax+self.addp, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q)+self.q, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax+self.addp, self.sideposay+(s-1)*self.q*5+2*self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						end
					
					elseif skill == _E then
						
							DrawTextWithBorder("E"..spella.level, 14, papos.x-self.p+self.addp*2, papos.y+self.addq, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p+self.addp*2, papos.y+self.addq+self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							DrawTextWithBorder("E"..spella.level, 14, self.sideposax+2*self.addp, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q)+self.q, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax+2*self.addp, self.sideposay+(s-1)*self.q*5+2*self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						end
					elseif skill == _R then
						
							DrawTextWithBorder("R"..spella.level, 14, papos.x-self.p+self.addp*3+3, papos.y+self.addq, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p+self.addp*3+3, papos.y+self.addq+self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							DrawTextWithBorder("R"..spella.level, 14, self.sideposax+3*self.addp+3, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q)+self.q, self:CD_Getcolor(CDar, spella.level),0xFF000000)
							if self.AIOConfig.CD.cout and spella.level >0 then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax+3*self.addp+3, self.sideposay+(s-1)*self.q*5+2*self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						end
					elseif skill == SUMMONER_1 then
						
							DrawTextWithBorder(self:CD_SCNname(spella.name), 14, papos.x-self.p+self.addp*4+10, papos.y+self.addq, self:CD_Getcolor(CDar, 1),0xFF000000)
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p+self.addp*4+10, papos.y+self.addq+self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							DrawTextWithBorder(self:CD_SCNname(spella.name), 14, self.sideposax+self.addp*4+10, self:CD_ey(self.AIOConfig.CD.cout,self.sideposay,s,self.q)+self.q, self:CD_Getcolor(CDar, 1),0xFF000000)
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax+self.addp*4+10, self.sideposay+(s-1)*self.q*5+2*self.q, self:CD_Getcolor(CDar),0xFF000000)
							end
						end
					elseif skill == SUMMONER_2 then
						
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spella.name), 14, papos.x-self.p+self.addp*4+10, papos.y+self.addq+self.q*2, self:CD_Getcolor(CDar, 1),0xFF000000)
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, papos.x-self.p+self.addp*4+10, papos.y+self.addq+self.q*3, self:CD_Getcolor(CDar),0xFF000000)
							end
							if not self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spella.name), 14, papos.x-self.p+self.addp*4+10, papos.y+self.addq+self.q, self:CD_Getcolor(CDar, 1),0xFF000000)
							end
						
						if self.AIOConfig.CD.side1 then
							if self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spella.name), 14, self.sideposax+self.addp*4+10, self.sideposay+(s-1)*self.q*5+self.q*3, self:CD_Getcolor(CDar, 1),0xFF000000)
								DrawTextWithBorder(""..math.floor(spella.currentCd), 14, self.sideposax+self.addp*4+10, self.sideposay+(s-1)*self.q*5+self.q*4, self:CD_Getcolor(CDar),0xFF000000)
							end
							if not self.AIOConfig.CD.cout then
								DrawTextWithBorder(self:CD_SCNname(spella.name), 14, self.sideposax+self.addp*4+10, self.sideposay+(s-1)*self.q*3+self.q*2, self:CD_Getcolor(CDar, 1),0xFF000000)
							end
						end
					end

				end	
			end
			s = s+1
		end
	end
end

function AIO:CD_Getcolor(CDr, level)
	if level == 0 then return 0x80808FF0 end
	if  CDr > 0.75 then 
		return 0xFFFF0000
	elseif CDr > 0.5 and CDr <= 0.75 then
		return 0xFFF08700 
	elseif CDr > 0.25 and CDr <= 0.5 then
		return 0xFFFFFF00
	elseif CDr > 0 and CDr <= 0.25 then
		return 0x97FFFF00
	else
		return 0xFF00FF00
	end
end

function AIO:CD_SCNname(name)
	if name == "SummonerOdinGarrison" then return "卫戍部队"
	elseif name == "SummonerHaste" then return "幽灵疾步"
	elseif name == "SummonerSmite" then return "惩戒"
	elseif name == "SummonerHeal" then return "治疗术"
	elseif name == "SummonerMana" then return "清晰术"
	elseif name == "SummonerRevive" then return "重生"
	elseif name == "SummonerTeleport" then return "传送"
	elseif name == "SummonerBoost" then return "净化"
	elseif name == "SummonerBarrier" then return "屏障"
	elseif name == "SummonerDot" then return "引燃"
	elseif name == "SummonerExhaust" then return "虚弱"
	elseif name == "SummonerClairvoyance" then return "洞察"
	elseif name == "SummonerFlash" then return "闪现"
	end
end

function AIO:CD_ey(cout,sideposy,t,q)
	if cout then
		return sideposy+(t-1)*q*5
	else return sideposy+(t-1)*q*3
	end
end

------------------------------------------------------------------------------打野计时-----------------------------------------------------------------------------------
function AIO:Timer_OnLoad()
	self.monsters = {
		summonerRift = {
			{	-- baron
				name = "\229\164\167\233\190\153",
				spawn = 900,
				respawn = 420,
				advise = true,
				camps = {
					{
						pos = { x = 4600, y = 60, z = 10250 },
						name = "monsterCamp_12",
						creeps = { { { name = "Worm12.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
			{	-- dragon
				name = "\229\176\143\233\190\153",
				spawn = 150,
				respawn = 360,
				advise = true,
				camps = {
					{
						pos = { x = 9459, y = 60, z = 4193 },
						name = "monsterCamp_6",
						creeps = { { { name = "Dragon6.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
			{	-- blue
				name = "\232\147\157BUFF",
				spawn = 115,
				respawn = 300,
				advise = true,
				camps = {
					{
						pos = { x = 3632, y = 60, z = 7600 },
						name = "monsterCamp_1",
						creeps = { { { name = "AncientGolem1.1.1" }, { name = "YoungLizard1.1.2" }, { name = "YoungLizard1.1.3" }, }, },
						team = TEAM_BLUE,
					},
					{
						pos = { x = 10386, y = 60, z = 6811 },
						name = "monsterCamp_7",
						creeps = { { { name = "AncientGolem7.1.1" }, { name = "YoungLizard7.1.2" }, { name = "YoungLizard7.1.3" }, }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- red
				name = "\231\186\162BUFF",
				spawn = 115,
				respawn = 300,
				advise = true,
				camps = {
					{
						pos = { x = 7455, y = 60, z = 3890 },
						name = "monsterCamp_4",
						creeps = { { { name = "LizardElder4.1.1" }, { name = "YoungLizard4.1.2" }, { name = "YoungLizard4.1.3" }, }, },
						team = TEAM_BLUE,
					},
					{
						pos = { x = 6504, y = 60, z = 10584 },
						name = "monsterCamp_10",
						creeps = { { { name = "LizardElder10.1.1" }, { name = "YoungLizard10.1.2" }, { name = "YoungLizard10.1.3" }, }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- wolves
				name = "\228\184\137\231\139\188",
				spawn = 115,
				respawn = 50,
				advise = false,
				camps = {
					{
						name = "monsterCamp_2",
						creeps = { { { name = "GiantWolf2.1.1" }, { name = "wolf2.1.2" }, { name = "wolf2.1.3" }, }, },
						team = TEAM_BLUE,
					},
					{
						name = "monsterCamp_8",
						creeps = { { { name = "GiantWolf8.1.1" }, { name = "wolf8.1.2" }, { name = "wolf8.1.3" }, }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- wraiths
				name = "\229\155\155\233\172\188",
				spawn = 115,
				respawn = 50,
				advise = false,
				camps = {
					{
						name = "monsterCamp_3",
						creeps = { { { name = "Wraith3.1.1" }, { name = "LesserWraith3.1.2" }, { name = "LesserWraith3.1.3" }, { name = "LesserWraith3.1.4" }, }, },
						team = TEAM_BLUE,
					},
					{
						name = "monsterCamp_9",
						creeps = { { { name = "Wraith9.1.1" }, { name = "LesserWraith9.1.2" }, { name = "LesserWraith9.1.3" }, { name = "LesserWraith9.1.4" }, }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- GreatWraiths
				name = "\232\161\140\229\176\184",
				spawn = 115,
				respawn = 50,
				advise = false,
				camps = {
					{
						name = "monsterCamp_13",
						creeps = { { { name = "GreatWraith13.1.1" }, }, },
						team = TEAM_BLUE,
					},
					{
						name = "monsterCamp_14",
						creeps = { { { name = "GreatWraith14.1.1" }, }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- Golems
				name = "\231\186\162",
				spawn = 115,
				respawn = 50,
				advise = false,
				camps = {
					{
						name = "monsterCamp_5",
						creeps = { { { name = "Golem5.1.2" }, { name = "SmallGolem5.1.1" }, }, },
						team = TEAM_BLUE,
					},
					{
						name = "monsterCamp_11",
						creeps = { { { name = "Golem11.1.2" }, { name = "SmallGolem11.1.1" }, }, },
						team = TEAM_RED,
					},
				},
			},
		},
		twistedTreeline = {
			{	-- Wraith
				name = "\229\185\189\231\129\181",
				spawn = 100,
				respawn = 50,
				advise = false,
				camps = {
					{
						--pos = { x = 4414, y = 60, z = 5774 },
						name = "monsterCamp_1",
						creeps = {
							{ { name = "TT_NWraith1.1.1" }, { name = "TT_NWraith21.1.2" }, { name = "TT_NWraith21.1.3" }, },
						},
						team = TEAM_BLUE,
					},
					{
						--pos = { x = 11008, y = 60, z = 5775 },
						name = "monsterCamp_4",
						creeps = {
							{ { name = "TT_NWraith4.1.1" }, { name = "TT_NWraith24.1.2" }, { name = "TT_NWraith24.1.3" }, },
						},
						team = TEAM_RED,
					},
				},
			},
			{	-- Golems
				name = "\231\159\179\229\164\180\228\186\186",
				respawn = 50,
				spawn = 100,
				advise = false,
				camps = {
					{
						--pos = { x = 5088, y = 60, z = 8065 },
						name = "monsterCamp_2",
						creeps = {
							{ { name = "TT_NGolem2.1.1" }, { name = "TT_NGolem22.1.2" } },
						},
						team = TEAM_BLUE,
					},
					{
						--pos = { x = 10341, y = 60, z = 8084 },
						name = "monsterCamp_5",
						creeps = {
							{ { name = "TT_NGolem5.1.1" }, { name = "TT_NGolem25.1.2" } },
						},
						team = TEAM_RED,
					},
				},
			},
			{	-- Wolves
				name = "\228\184\137\231\139\188",
				respawn = 50,
				spawn = 100,
				advise = false,
				camps = {
					{
						--pos = { x = 6148, y = 60, z = 5993 },
						name = "monsterCamp_3",
						creeps = { { { name = "TT_NWolf3.1.1" }, { name = "TT_NWolf23.1.2" }, { name = "TT_NWolf23.1.3" } }, },
						team = TEAM_BLUE,
					},
					{
						--pos = { x = 9239, y = 60, z = 6022 },
						name = "monsterCamp_6",
						creeps = { { { name = "TT_NWolf6.1.1" }, { name = "TT_NWolf26.1.2" }, { name = "TT_NWolf26.1.3" } }, },
						team = TEAM_RED,
					},
				},
			},
			{	-- Heal
				name = "\229\155\158\229\164\141\231\130\185",
				spawn = 115,
				respawn = 90,
				advise = true,
				camps = {
					{
						pos = { x = 7711, y = 60, z = 6722 },
						name = "monsterCamp_7",
						creeps = { { { name = "TT_Relic7.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
			{	-- Vilemaw
				name = "\229\141\145\233\132\153\228\185\139\229\150\137",
				spawn = 600,
				respawn = 300,
				advise = true,
				camps = {
					{
						pos = { x = 7711, y = 60, z = 10080 },
						name = "monsterCamp_8",
						creeps = { { { name = "TT_Spiderboss8.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
		},
		crystalScar = {},
		provingGrounds = {
			{	-- Heal
				name = "\229\155\158\229\164\141\231\130\185",
				spawn = 190,
				respawn = 40,
				advise = false,
				camps = {
					{
						pos = { x = 8922, y = 60, z = 7868 },
						name = "monsterCamp_1",
						creeps = { { { name = "OdinShieldRelic1.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 7473, y = 60, z = 6617 },
						name = "monsterCamp_2",
						creeps = { { { name = "OdinShieldRelic2.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 5929, y = 60, z = 5190 },
						name = "monsterCamp_3",
						creeps = { { { name = "OdinShieldRelic3.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 4751, y = 60, z = 3901 },
						name = "monsterCamp_4",
						creeps = { { { name = "OdinShieldRelic4.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
		},
		howlingAbyss = {
			{	-- Heal
				name = "\229\155\158\229\164\141\231\130\185",
				spawn = 190,
				respawn = 40,
				advise = false,
				camps = {
					{
						pos = { x = 8922, y = 60, z = 7868 },
						name = "monsterCamp_1",
						creeps = { { { name = "HA_AP_HealthRelic1.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 7473, y = 60, z = 6617 },
						name = "monsterCamp_2",
						creeps = { { { name = "HA_AP_HealthRelic2.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 5929, y = 60, z = 5190 },
						name = "monsterCamp_3",
						creeps = { { { name = "HA_AP_HealthRelic3.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
					{
						pos = { x = 4751, y = 60, z = 3901 },
						name = "monsterCamp_4",
						creeps = { { { name = "HA_AP_HealthRelic4.1.1" }, }, },
						team = TEAM_NEUTRAL,
					},
				},
			},
		},
	}

	self.altars = {
		summonerRift = {},
		twistedTreeline = {
			{
				name = "\229\183\166\230\150\185\231\165\173\229\157\155",
				spawn = 180,
				respawn = 85,
				advise = true,
				objectName = "TT_Buffplat_L",
				locked = false,
				lockNames = {"TT_Lock_Blue_L.troy", "TT_Lock_Purple_L.troy", "TT_Lock_Neutral_L.troy", },
				unlockNames = {"TT_Unlock_Blue_L.troy", "TT_Unlock_purple_L.troy", "TT_Unlock_Neutral_L.troy", },
			},
			{
				name = "\229\143\179\230\150\185\231\165\173\229\157\155",
				spawn = 180,
				respawn = 85,
				advise = true,
				objectName = "TT_Buffplat_R",
				locked = false,
				lockNames = {"TT_Lock_Blue_R.troy", "TT_Lock_Purple_R.troy", "TT_Lock_Neutral_R.troy", },
				unlockNames = {"TT_Unlock_Blue_R.troy", "TT_Unlock_purple_R.troy", "TT_Unlock_Neutral_R.troy", },
			},
		},
		crystalScar = {},
		provingGrounds = {},
		howlingAbyss = {},
	}

	self.relics = {
		summonerRift = {},
		twistedTreeline = {},
		crystalScar = {
			{
				pos = { x = 5500, y = 60, z = 6500 },
				name = "\230\141\174\231\130\185",
				team = TEAM_BLUE,
				spawn = 180,
				respawn = 180,
				advise = true,
				locked = false,
				precenceObject = (player.team == TEAM_BLUE and "Odin_Prism_Green.troy" or "Odin_Prism_Red.troy"),
			},
			{
				pos = { x = 7550, y = 60, z = 6500 },
				name = "\230\141\174\231\130\185",
				team = TEAM_RED,
				spawn = 180,
				respawn = 180,
				advise = true,
				locked = false,
				precenceObject = (player.team == TEAM_RED and "Odin_Prism_Green.troy" or "Odin_Prism_Red.troy"),
			},
		},
		provingGrounds = {},
		howlingAbyss = {},
	}

	self.heals = {
		summonerRift = {},
		twistedTreeline = {},
		provingGrounds = {},
		crystalScar = {
			{
				name = "\229\155\158\229\164\141\231\130\185",
				objectName = "OdinShieldRelic",
				respawn = 30,
				objects = {},
			},
		},
		howlingAbyss = {},
	}

	self.inhibitors = {}
	
		self.mapName = GetGame().map.shortName
		if self.monsters[self.mapName] == nil then
			self.mapName = nil
			self.monsters = nil
			self.addCampCreepAltar = nil
--			self:Timer_removeCreep = nil
			self.addAltarObject = nil
			return
		else
			self.startTick = GetGame().tick
			-- CONFIG
			self.AIOConfig:addSubMenu("打野计时", "TimerConfig")
			self.AIOConfig.TimerConfig:addParam("ON", "开启", SCRIPT_PARAM_ONOFF, true) 
			self.AIOConfig.TimerConfig:addParam("pingOnRespawn", "刷新时ping地图", SCRIPT_PARAM_ONOFF, true) -- ping location on respawn
			self.AIOConfig.TimerConfig:addParam("pingOnRespawnBefore", "刷新前ping地图", SCRIPT_PARAM_ONOFF, true) -- ping location before respawn
			self.AIOConfig.TimerConfig:addParam("textOnRespawn", "刷新时显示文字(仅自己看)", SCRIPT_PARAM_ONOFF, true) -- print chat text on respawn
			self.AIOConfig.TimerConfig:addParam("sendOnRespawn", "刷新时显示文字(提示队友)", SCRIPT_PARAM_ONOFF, false) -- send chat text on respawn
			self.AIOConfig.TimerConfig:addParam("textOnRespawnBefore", "刷新前显示文字(仅自己看)", SCRIPT_PARAM_ONOFF, true) -- print chat text before respawn
			self.AIOConfig.TimerConfig:addParam("sendOnRespawnBefore", "刷新前显示文字(提示队友)", SCRIPT_PARAM_ONOFF, false) -- send chat text before respawn
			self.AIOConfig.TimerConfig:addParam("adviceTheirMonsters", "敌方野怪也提示", SCRIPT_PARAM_ONOFF, true) -- advice enemy monster, or just our monsters
			self.AIOConfig.TimerConfig:addParam("adviceBefore", "在多少秒前通知:", SCRIPT_PARAM_SLICE, 20, 1, 40, 0) -- time in second to advice before monster respawn
			self.AIOConfig.TimerConfig:addParam("textOnMap", "大地图显示倒计时", SCRIPT_PARAM_ONOFF, true) -- time in second on map
			self.AIOConfig.TimerConfig:addParam("font", "小地图字体大小", SCRIPT_PARAM_SLICE, 14, 5, 25, 0)
			for i,monster in pairs(self.monsters[self.mapName]) do
				monster.isSeen = false
				for j,camp in pairs(monster.camps) do
					camp.enemyTeam = (camp.team == TEAM_ENEMY)
					camp.textTick = 0
					camp.status = 0
					camp.drawText = ""
					camp.drawColor = 0xFF00FF00
				end
			end
			for i = 1, objManager.maxObjects do
				local object = objManager:getObject(i)
				if object ~= nil then
					self:Timer_addCampCreepAltar(object)
				end
			end
			AddCreateObjCallback(function(obj) self:Timer_addCampCreepAltar(obj) end)
			AddDeleteObjCallback(function(obj) self:Timer_removeCreep(obj) end)
		end
end

	function AIO:Timer_addCampCreepAltar(object)
		if not self.AIOConfig.TimerConfig.ON then return end
		if object ~= nil and object.name ~= nil then
			if object.name == "Order_Inhibit_Gem.troy" or object.name == "Chaos_Inhibit_Gem.troy" then
				table.insert(self.inhibitors, { object = object, destroyed = false, lefttime = 0, x = object.x, y = object.y, z = object.z, minimap = GetMinimap(object), textTick = 0 })
				return
			elseif object.name == "Order_Inhibit_Crystal_Shatter.troy" or object.name == "Chaos_Inhibit_Crystal_Shatter.troy" then
				for i,inhibitor in pairs(self.inhibitors) do
					if GetDistance(inhibitor, object) < 200 then
						local tick = GetTickCount()
						inhibitor.dtime = tick
						inhibitor.rtime = tick + 240000
						inhibitor.ltime = 240000
						inhibitor.destroyed = true
					end
				end
				return
			end
			for i,monster in pairs(self.monsters[self.mapName]) do
				for j,camp in pairs(monster.camps) do
					if camp.name == object.name then
						camp.object = object
						return
					end
					if object.type == "obj_AI_Minion" then
						for k,creepPack in ipairs(camp.creeps) do
							for l,creep in ipairs(creepPack) do
								if object.name == creep.name then
									creep.object = object
									return
								end
							end
						end
					end
				end
			end
			for i,altar in pairs(self.altars[self.mapName]) do
				if altar.objectName == object.name then
					altar.object = object
					altar.textTick = 0
					altar.minimap = GetMinimap(object)
				end
				if altar.locked then
					for j,lockName in pairs(altar.unlockNames) do
						if lockName == object.name then
							altar.locked = false
							return
						end
					end
				else
					for j,lockName in pairs(altar.lockNames) do
						if lockName == object.name then
							altar.drawColor = 0
							altar.drawText = ""
							altar.locked = true
							altar.advised = false
							altar.advisedBefore = false
							return
						end
					end
				end
			end
			for i,relic in pairs(self.relics[self.mapName]) do
				if relic.precenceObject == object.name then
					relic.object = object
					relic.textTick = 0
					relic.locked = false
					return
				end
			end
			for i,heal in pairs(self.heals[self.mapName]) do
				if heal.objectName == object.name then
					for j,healObject in pairs(heal.objects) do
						if (GetDistance(healObject, object) < 50) then
							healObject.object = object
							healObject.found = true
							healObject.locked = false
							return
						end
					end
					local k = #heal.objects + 1
					self.heals[self.mapName][i].objects[k] = {found = true, locked = false, object = object, x = object.x, y = object.y, z = object.z, minimap = GetMinimap(object), textTick = 0,}
					return
				end
			end
		end
	end

	function AIO:Timer_removeCreep(object)
		if not self.AIOConfig.TimerConfig.ON then return end
		if object ~= nil and object.type == "obj_AI_Minion" and object.name ~= nil then
			for i,monster in pairs(self.monsters[self.mapName]) do
				for j,camp in pairs(monster.camps) do
					for k,creepPack in ipairs(camp.creeps) do
						for l,creep in ipairs(creepPack) do
							if object.name == creep.name then
								creep.object = nil
								return
							end
						end
					end
				end
			end
		end
	end


	function AIO:Timer_OnTick()
		if GetGame().isOver or not self.AIOConfig.TimerConfig.ON then return end
		local GameTime = (GetTickCount()-self.startTick) / 1000
		local monsterCount = 0
		for i,monster in pairs(self.monsters[self.mapName]) do
			for j,camp in pairs(monster.camps) do
				local campStatus = 0
				for k,creepPack in ipairs(camp.creeps) do
					for l,creep in ipairs(creepPack) do
						if creep.object ~= nil and creep.object.valid and creep.object.dead == false then
							if l == 1 then
								campStatus = 1
							elseif campStatus ~= 1 then
								campStatus = 2
							end
						end
					end
				end
				--[[  Not used until camp.showOnMinimap work
				if (camp.object and camp.object.showOnMinimap == 1) then
				-- camp is here
				if campStatus == 0 then campStatus = 3 end
				elseif camp.status == 3 then 						-- empty not seen when killed
				campStatus = 5
				elseif campStatus == 0 and (camp.status == 1 or camp.status == 2) then
				campStatus = 4
				camp.deathTick = tick
				end
				]]
				-- temp fix until camp.showOnMinimap work
				-- not so good
				if camp.object ~= nil and camp.object.valid then
					camp.minimap = GetMinimap(camp.object)
					if campStatus == 0 then
						if (camp.status == 1 or camp.status == 2) then
							campStatus = 4
							camp.advisedBefore = false
							camp.advised = false
							camp.respawnTime = math.floor(GameTime) + monster.respawn
							if monster.name == "\229\176\143\233\190\153" or monster.name == "\229\164\167\233\190\153" then
								camp.respawnText = TimerText(camp.respawnTime)..monster.name
--								(monster.name == "baron" and " baron" or " d")
							elseif monster.name == "\232\147\157BUFF" or monster.name == "\231\186\162BUFF" then
								camp.respawnText = TimerText(camp.respawnTime)..
								(camp.enemyTeam and " \230\149\140\230\150\185" or " \230\136\145\230\150\185")..monster.name--[[(monster.name == "red" and "r" or "b")--]]
							else
								camp.respawnText = (camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..
								monster.name.." \229\176\134\229\156\168 "..TimerText(camp.respawnTime).." \229\136\183\230\150\176"
							end
						elseif (camp.status == 4) then
							campStatus = 4
						else
							campStatus = 3
						end
					end
				elseif camp.pos ~= nil then
					camp.minimap = GetMinimap(camp.pos)
					if (GameTime < monster.spawn) then
						campStatus = 4
						camp.advisedBefore = true
						camp.advised = true
						camp.respawnTime = monster.spawn
						camp.respawnText = (camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..monster.name.." \229\176\134\229\156\168 "..TimerText(camp.respawnTime).." \229\136\183\230\150\176"
					end
				end
				if camp.status ~= campStatus or campStatus == 4 then
					if campStatus ~= 0 then
						if monster.isSeen == false then monster.isSeen = true end
						camp.status = campStatus
					end
					if camp.status == 1 then				-- ready
						camp.drawText = "\229\183\178\229\136\183\230\150\176"
						camp.drawColor = 0xFF00FF00
					elseif camp.status == 2 then			-- ready, master creeps dead
						camp.drawText = "\232\162\171\229\129\183"
						camp.drawColor = 0xFFFF0000
					elseif camp.status == 3 then			-- ready, not creeps shown
						camp.drawText = "   ?"
						camp.drawColor = 0xFF00FF00
					elseif camp.status == 4 then			-- empty from creeps kill
						local secondLeft = math.ceil(math.max(0, camp.respawnTime - GameTime))
						if monster.advise == true and (self.AIOConfig.TimerConfig.adviceTheirMonsters == true or camp.enemyTeam == false) then
							if secondLeft == 0 and camp.advised == false then
								camp.advised = true
								if self.AIOConfig.TimerConfig.textOnRespawn then PrintChat("<font color='#00FFCC'>"..(camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..monster.name.."</font><font color='#FFAA00'> \229\183\178\231\187\143\229\136\183\230\150\176</font>") end
								if self.AIOConfig.TimerConfig.sendOnRespawn then SendChat((camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..monster.name.." \229\183\178\231\187\143\229\136\183\230\150\176") end
								if self.AIOConfig.TimerConfig.pingOnRespawn then PingSignal(PING_FALLBACK,camp.object.x,camp.object.y,camp.object.z,2) end
							elseif secondLeft <= self.AIOConfig.TimerConfig.adviceBefore and camp.advisedBefore == false then
								camp.advisedBefore = true
								if self.AIOConfig.TimerConfig.textOnRespawnBefore then PrintChat("<font color='#00FFCC'>"..(camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..monster.name.."</font><font color='#FFAA00'> \229\176\134\229\156\168 </font><font color='#00FFCC'>"..secondLeft.." \231\167\146\229\144\142\229\136\183\230\150\176</font>") end
								if self.AIOConfig.TimerConfig.sendOnRespawnBefore then SendChat((camp.enemyTeam and "\230\149\140\230\150\185 " or "\230\136\145\230\150\185 ")..monster.name.." \229\176\134\229\156\168 "..secondLeft.." \231\167\146\229\144\142\229\136\183\230\150\176") end
								if self.AIOConfig.TimerConfig.pingOnRespawnBefore then PingSignal(PING_FALLBACK,camp.object.x,camp.object.y,camp.object.z,2) end
							end
						end
						-- temp fix until camp.showOnMinimap work
						if secondLeft == 0 then
							camp.status = 0
						end
						camp.drawText = " "..TimerText(secondLeft)
						camp.drawColor = 0xFFFFFF00
					elseif camp.status == 5 then			-- camp found empty (not using yet)
						camp.drawText = "   -"
						camp.drawColor = 0xFFFF0000
					end
				end
				-- shift click
				if IsKeyDown(16) and camp.status == 4 then
					camp.drawText = " "..(camp.respawnTime ~= nil and TimerText(camp.respawnTime) or "")
					camp.textUnder = (CursorIsUnder(camp.minimap.x - 9, camp.minimap.y - 5, 20, 8))
				else
					camp.textUnder = false
				end
				if self.AIOConfig.TimerConfig.textOnMap and camp.status == 4 and camp.object and camp.object.valid and camp.textTick < GetTickCount() and camp.floatText ~= camp.drawText then
					camp.floatText = camp.drawText
					camp.textTick = GetTickCount() + 1000
					PrintFloatText(camp.object,6,camp.floatText)
				end
			end
		end

		-- altars
		for i,altar in pairs(self.altars[self.mapName]) do
			if altar.object and altar.object.valid then
				if altar.locked then
					if GameTime < altar.spawn then
						altar.secondLeft = math.ceil(math.max(0, altar.spawn - GameTime))
					else
						local tmpTime = ((altar.object.mana > 39600) and (altar.object.mana - 39900) / 20100 or (39600 - altar.object.mana) / 20100)
						altar.secondLeft = math.ceil(math.max(0, tmpTime * altar.respawn))
					end
					altar.unlockTime = math.ceil(GameTime + altar.secondLeft)
					altar.unlockText = altar.name.." \229\176\134\229\156\168 "..TimerText(altar.unlockTime).." \232\167\163\233\148\129"
					altar.drawColor = 0xFFFFFF00
					if altar.advise == true then
						if altar.secondLeft == 0 and altar.advised == false then
							altar.advised = true
							if self.AIOConfig.TimerConfig.textOnRespawn then PrintChat("<font color='#00FFCC'>"..altar.name.."</font><font color='#FFAA00'> \229\183\178\231\187\143\232\167\163\233\148\129</font>") end
							if self.AIOConfig.TimerConfig.sendOnRespawn then SendChat(altar.name.."\229\183\178\231\187\143\229\136\183\230\150\176") end
							if self.AIOConfig.TimerConfig.pingOnRespawn then PingSignal(PING_FALLBACK,altar.object.x,altar.object.y,altar.object.z,2) end
						elseif altar.secondLeft <= self.AIOConfig.TimerConfig.adviceBefore and altar.advisedBefore == false then
							altar.advisedBefore = true
							if self.AIOConfig.TimerConfig.textOnRespawnBefore then PrintChat("<font color='#00FFCC'>"..altar.name.."</font><font color='#FFAA00'> \229\176\134\229\156\168 </font><font color='#00FFCC'>"..altar.secondLeft.." \231\167\146\229\144\142\232\167\163\233\148\129</font>") end
							if self.AIOConfig.TimerConfig.sendOnRespawnBefore then SendChat(altar.name.."\229\176\134\229\156\168"..altar.secondLeft.." \231\167\146\229\144\142\229\136\183\230\150\176") end
							if self.AIOConfig.TimerConfig.pingOnRespawnBefore then PingSignal(PING_FALLBACK,altar.object.x,altar.object.y,altar.object.z,2) end
						end
					end
					-- shift click
					if IsKeyDown(16) then
						altar.drawText = " "..(altar.unlockTime ~= nil and TimerText(altar.unlockTime) or "")
						altar.textUnder = (CursorIsUnder(altar.minimap.x - 9, altar.minimap.y - 5, 20, 8))
					else
						altar.drawText = " "..(altar.secondLeft ~= nil and TimerText(altar.secondLeft) or "")
						altar.textUnder = false
					end
					if self.AIOConfig.TimerConfig.textOnMap and altar.object and altar.object.valid and altar.textTick < GetTickCount() and altar.floatText ~= altar.drawText then
						altar.floatText = altar.drawText
						altar.textTick = GetTickCount() + 1000
						PrintFloatText(altar.object,6,altar.floatText)
					end
				end
			end
		end

		-- relics
		for i,relic in pairs(self.relics[self.mapName]) do
			if (not relic.locked and (not relic.object or not relic.object.valid or relic.dead)) then
				if GameTime < relic.spawn then
					relic.unlockTime = relic.spawn - GameTime
				else
					relic.unlockTime = math.ceil(GameTime + relic.respawn)
				end
				relic.advised = false
				relic.advisedBefore = false
				relic.drawText = ""
				relic.unlockText = relic.name.." \229\176\134\229\156\168 "..TimerText(relic.unlockTime).." \229\136\183\230\150\176"
				relic.drawColor = 4288610048
				--FF9EFF00
				relic.minimap = GetMinimap(relic.pos)
				relic.locked = true
			end
			if relic.locked then
				relic.secondLeft = math.ceil(math.max(0, relic.unlockTime - GameTime))
				if relic.advise == true then
					if relic.secondLeft == 0 and relic.advised == false then
						relic.advised = true
						if self.AIOConfig.TimerConfig.textOnRespawn then PrintChat("<font color='#00FFCC'>"..relic.name.."</font><font color='#FFAA00'> \229\183\178\231\187\143\229\136\183\230\150\176</font>") end
						if self.AIOConfig.TimerConfig.sendOnRespawn then SendChat(relic.name.."\229\183\178\231\187\143\229\136\183\230\150\176") end
						if self.AIOConfig.TimerConfig.pingOnRespawn then PingSignal(PING_FALLBACK,relic.pos.x,relic.pos.y,relic.pos.z,2) end
					elseif relic.secondLeft <= self.AIOConfig.TimerConfig.adviceBefore and relic.advisedBefore == false then
						relic.advisedBefore = true
						if self.AIOConfig.TimerConfig.textOnRespawnBefore then PrintChat("<font color='#00FFCC'>"..relic.name.."</font><font color='#FFAA00'> \229\176\134\229\156\168 </font><font color='#00FFCC'>"..relic.secondLeft.." \231\167\146\229\144\142\229\136\183\230\150\176</font>") end
						if self.AIOConfig.TimerConfig.sendOnRespawnBefore then SendChat(relic.name.."\229\176\134\229\156\168"..relic.secondLeft.." \231\167\146\229\144\142\229\136\183\230\150\176") end
						if self.AIOConfig.TimerConfig.pingOnRespawnBefore then PingSignal(PING_FALLBACK,relic.pos.x,relic.pos.y,relic.pos.z,2) end
					end
				end
				-- shift click
				if IsKeyDown(16) then
					relic.drawText = " "..(relic.unlockTime ~= nil and TimerText(relic.unlockTime) or "")
					relic.textUnder = (CursorIsUnder(relic.minimap.x - 9, relic.minimap.y - 5, 20, 8))
				else
					relic.drawText = " "..(relic.secondLeft ~= nil and TimerText(relic.secondLeft) or "")
					relic.textUnder = false
				end
			end
		end

		for i,heal in pairs(self.heals[self.mapName]) do
			for j,healObject in pairs(heal.objects) do
				if (not healObject.locked and healObject.found and (not healObject.object or not healObject.object.valid or healObject.object.dead)) then
					healObject.drawColor = 0xFF00FF04
					healObject.unlockTime = math.ceil(GameTime + heal.respawn)
					healObject.drawText = ""
					healObject.found = false
					healObject.locked = true
				end
				if healObject.locked then
					-- shift click
					local secondLeft = math.ceil(math.max(0, healObject.unlockTime - GameTime))
					if IsKeyDown(16) then
						healObject.drawText = " "..(healObject.unlockTime ~= nil and TimerText(healObject.unlockTime) or "")
						healObject.textUnder = (CursorIsUnder(healObject.minimap.x - 9, healObject.minimap.y - 5, 20, 8))
					else
						healObject.drawText = " "..(secondLeft ~= nil and TimerText(secondLeft) or "")
						healObject.textUnder = false
					end
					if secondLeft == 0 then healObject.locked = false end
				end
			end
		end
		-- inhib
		for i,inhibitor in pairs(self.inhibitors) do
			if inhibitor.destroyed then
				local tick = GetTickCount()
				if inhibitor.rtime < tick then
					inhibitor.destroyed = false
				else
					inhibitor.ltime = (inhibitor.rtime - GetTickCount()) / 1000;
					inhibitor.drawText = TimerText(inhibitor.ltime)
					--inhibitor.drawText = (IsKeyDown(16) and TimerText(inhibitor.rtime) or TimerText(inhibitor.rtime))
					if self.AIOConfig.TimerConfig.textOnMap and inhibitor.textTick < tick then
						inhibitor.textTick = tick + 1000
						PrintFloatText(inhibitor.object,6,inhibitor.drawText)
					end
				end
			end
		end
	end

	function AIO:Timer_OnDraw()
		if GetGame().isOver or not self.AIOConfig.TimerConfig.ON then return end
		for i,monster in pairs(self.monsters[self.mapName]) do
			if monster.isSeen == true then
				for j,camp in pairs(monster.camps) do
					if camp.status == 2 then
						DrawText("X",self.AIOConfig.TimerConfig.font,camp.minimap.x - 4, camp.minimap.y - 5, camp.drawColor)
					elseif camp.status == 4 then
						DrawText(camp.drawText,self.AIOConfig.TimerConfig.font,camp.minimap.x - 9, camp.minimap.y - 5, camp.drawColor)
					end
				end
			end
		end
		for i,altar in pairs(self.altars[self.mapName]) do
			if altar.locked then
				DrawText(altar.drawText,self.AIOConfig.TimerConfig.font,altar.minimap.x - 9, altar.minimap.y - 5, altar.drawColor)
			end
		end
		for i,relic in pairs(self.relics[self.mapName]) do
			if relic.locked then
				DrawText(relic.drawText,self.AIOConfig.TimerConfig.font,relic.minimap.x - 9, relic.minimap.y - 5, relic.drawColor)
			end
		end
		for i,heal in pairs(self.heals[self.mapName]) do
			for j,healObject in pairs(heal.objects) do
				if healObject.locked then
					DrawText(healObject.drawText,self.AIOConfig.TimerConfig.font,healObject.minimap.x - 9, healObject.minimap.y - 5, healObject.drawColor)
				end
			end
		end
		for i,inhibitor in pairs(self.inhibitors) do
			if inhibitor.destroyed == true then
				DrawText(inhibitor.drawText,self.AIOConfig.TimerConfig.font,inhibitor.minimap.x - 9, inhibitor.minimap.y - 5, 0xFFFFFF00)
			end
		end
	end

	function AIO:Timer_OnWndMsg(msg,key)
		if not self.AIOConfig.TimerConfig.ON then return end
		if msg == WM_LBUTTONDOWN and IsKeyDown(16) then
			for i,monster in pairs(self.monsters[self.mapName]) do
				if monster.isSeen == true then
					if monster.iconUnder then
						monster.advise = not monster.advise
						break
					else
						for j,camp in pairs(monster.camps) do
							if camp.textUnder then
								if camp.respawnText ~= nil then SendChat(""..camp.respawnText) end
								break
							end
						end
					end
				end
			end
			for i,altar in pairs(self.altars[self.mapName]) do
				if altar.locked and altar.textUnder then
					if altar.unlockText ~= nil then SendChat(""..altar.unlockText) end
					break
				end
			end
		end
	end

---------------------------------------------------------------------自动喝药--------------------------------------------------------------------------------------------
function AIO:AutoPotion_OnLoad()
	self.PotionTable = {
		fortitude = {
			tick = 0,
			slot = nil,
			itemID = 2037,
			compareValue = function() return (myHero.health / myHero.maxHealth) end,
			buff = "PotionOfGiantStrengt",
			name = "fortitude"
		},
		flask = {
			tick = 0,
			slot = nil,
			itemID	= 2041,		-- item ID of Crystaline Flask (2041)
			compareValue = function() return math.min(myHero.mana / myHero.maxMana,myHero.health / myHero.maxHealth) end,
			buff = "ItemCrystalFlask",
			name = "flask"
		},
		biscuit = {	
			tick = 0,
			slot = nil, 
			itemID	= 2009,		-- item ID of Total Biscuit of Rejuvenation (2009)
			compareValue = function() return math.min(myHero.mana / myHero.maxMana,myHero.health / myHero.maxHealth) end,
			buff = "ItemMiniRegenPotion",
			name = "biscuit"
		},
		hp = {
			tick = 0,
			slot = nil, 
			itemID	= 2003,		-- item ID of health potion (2003)
			compareValue = function() return (myHero.health / myHero.maxHealth) end,
			buff = "RegenerationPotion",
			name = "hp"
		},
		mp = {
			tick = 0,
			slot = nil,
			itemID	= 2004,		-- item ID of mana potion (2004)
			compareValue = function() return (myHero.mana / myHero.maxMana) end,
			buff = "FlaskOfCrystalWater",
			name = "mp"
		}
	}
	self.AIOConfig:addSubMenu("自动喝药", "PotionConfig")
	self.AIOConfig.PotionConfig:addParam("ON", "开启", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.PotionConfig:addParam("hp", "红药", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.PotionConfig:addParam("mp", "蓝药", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.PotionConfig:addParam("flask", "水晶瓶", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.PotionConfig:addParam("biscuit", "饼干", SCRIPT_PARAM_ONOFF, true)
	self.AIOConfig.PotionConfig:addParam("fortitude", "大红药", SCRIPT_PARAM_ONOFF, true)
	
	self.AIOConfig.PotionConfig:addParam("hpValue", "\215\212\182\175\186\200\186\236\210\169\209\170\193\191\176\217\183\214\177\200", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
	self.AIOConfig.PotionConfig:addParam("mpValue", "\215\212\182\175\186\200\192\182\210\169\192\182\193\191\176\217\183\214\177\200", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
	self.AIOConfig.PotionConfig:addParam("flaskValue", "\215\212\182\175\186\200\203\174\190\167\198\191\209\170\193\191\176\217\183\214\177\200", SCRIPT_PARAM_SLICE, 75, 0, 100, 0)
	self.AIOConfig.PotionConfig:addParam("biscuitValue", "\215\212\182\175\179\212\177\253\184\201\209\170\193\191\176\217\183\214\177\200", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
	self.AIOConfig.PotionConfig:addParam("fortitudeValue", "\215\212\182\175\186\200\180\243\186\236\210\169\209\170\193\191\176\217\183\214\177\200", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
	
end 

function AIO:AutoPotion_OnTick() 
	if not self.AIOConfig.PotionConfig.ON or myHero.dead or ((GetGame().map.index ~= 7 and GetGame().map.index ~= 12) and InFountain()) then return end 
	for name,potion in pairs(self.PotionTable) do 
		if potion.tick == 0 or (GetTickCount() - potion.tick > 1000) then 
			
			potion.slot = GetInventorySlotItem(potion.itemID) 
			if self.AIOConfig.PotionConfig[potion.name] == true and potion.slot ~= nil and self:AutoPotion_CheckBuffs() then
				if potion.compareValue() < (self.AIOConfig.PotionConfig[potion.name.."Value"] / 100) then
					CastSpell(potion.slot)
					potion.tick = GetTickCount()
				end 
			end 
		end 
	end 
end 

function AIO:AutoPotion_CheckBuffs() 
	for name, potion in pairs(self.PotionTable) do 
		if TargetHaveBuff(potion.buff) then
			return false
		end 
	end 
	return true 
end 

-------------------------------------------------------------------自动召唤师技能----------------------------------------------------------------------------------------
function AIO:Summonerspell_OnLoad()
	self.spells = {	"SummonerHeal",
					"SummonerMana",
					"SummonerRevive",
					"SummonerBarrier",
					"SummonerSmite"
					}
	self.slot = {}
	for i,spell in pairs(self.spells) do
		self:Summonerspell_Slot(spell)
	end
	if not self:Summonerspell_Checkslot() then return end
	
	self.AIOConfig:addSubMenu("自动召唤师技能", "SummonerConfig")
	self.AIOConfig.SummonerConfig:addParam("ON", "开启", SCRIPT_PARAM_ONOFF, true)
	
	if self.slot["SummonerHeal"] ~= nil then
		self.AIOConfig.SummonerConfig:addParam("Heal", "自动治疗术", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("Healper", "血量%低于此值自动治疗:", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)
	end
	
	if self.slot["SummonerMana"] ~= nil then
		self.AIOConfig.SummonerConfig:addParam("Mana", "自动清晰术", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("Manaper", "蓝量%低于此值自动清晰术:", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)
	end
	
	if self.slot["SummonerRevive"] ~= nil then
		self.AIOConfig.SummonerConfig:addParam("Revive", "自动重生", SCRIPT_PARAM_ONOFF, true)
		AddTickCallback(function() self:Summonerspell_OnTick() end)
	end
	if self.slot["SummonerBarrier"] ~= nil then
		self.AIOConfig.SummonerConfig:addParam("Barrier", "自动屏障", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("Barper", "血量%低于此值自动屏障:", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)
	end
	
	if self.slot["SummonerHeal"] ~= nil or self.slot["SummonerBarrier"] ~= nil then
		AddProcessSpellCallback(function(unit,spell) self:Summonerspell_OnProcessSpell(unit,spell) end)
	end
	
	
	
	if self.slot["SummonerSmite"] ~= nil or myHero.charName == "Nunu" or myHero.charName == "Chogath" then
		self.range = 800		-- Range of smite (~800)
		--[[            Globals         ]]
		self.smiteDamage, self.qDamage, self.mixDamage, self.rDamage, self.mixdDamage = 0, 0, 0, 0, 0
		self.canuseQ,self.canuseR,self.canusesmite = false,false,false
		self.Smiteison = false
		self.Vilemaw,self.Nashor,self.Dragon,self.Golem1,self.Golem2,self.Lizard1,self.Lizard2 = nil,nil,nil,nil,nil,nil,nil
		self.textNO = 0
		self.active = true
		self.AIOConfig.SummonerConfig:addParam("switcher", "自动惩戒开关(切换)", SCRIPT_PARAM_ONKEYTOGGLE, (self.slot["SummonerSmite"] ~= nil), 78)
		self.AIOConfig.SummonerConfig:addParam("hold", "自动惩戒开关(按住)", SCRIPT_PARAM_ONKEYDOWN, false, 17)
		self.AIOConfig.SummonerConfig:addParam("active", "自动惩戒已开启", SCRIPT_PARAM_INFO, "")
		self.AIOConfig.SummonerConfig:addParam("smitenashor", "不管开关都自动惩戒大龙", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("drawrange", "显示惩戒范围", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("drawcircles", "显示惩戒目标圈", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:addParam("drawtext", "显示文字提示", SCRIPT_PARAM_ONOFF, true)
		self.AIOConfig.SummonerConfig:permaShow("active")
		for t,txt in pairs(self.AIOConfig.SummonerConfig._param) do
			if txt.text == "自动惩戒已开启" then
				self.textNO = t
			end
		end
		self:Summonerspell_ASLoadMinions()
		self.Smiteison = true
		AddTickCallback(function() self:Summonerspell_Smite() end)
		AddDrawCallback(function() self:Summonerspell_OnDraw() end)
		AddCreateObjCallback(function(obj) self:Summonerspell_OnCreateObj(obj) end)
		AddDeleteObjCallback(function(obj) self:Summonerspell_OnDeleteObj(obj) end)
	end
end

function AIO:Summonerspell_Checkslot()
	for i,slot in pairs(self.slot) do
		if slot ~= nil then
			return true
		end
	end
	return false
end

function AIO:Summonerspell_Slot(name)
	if myHero:GetSpellData(SUMMONER_1).name == name then self.slot[name] = SUMMONER_1
    elseif myHero:GetSpellData(SUMMONER_2).name == name then self.slot[name] = SUMMONER_2
    end
end

function AIO:Summonerspell_OnTick()
	if not self.AIOConfig.SummonerConfig.ON then return end
	if self.AIOConfig.SummonerConfig.Revive then
		self:Summonerspell_Revive()
	end
end

function AIO:Summonerspell_OnProcessSpell(unit,spell)
	if not self.AIOConfig.SummonerConfig.ON or myHero.dead or unit == nil or spell == nil then return end
	if unit.team ~= myHero.team and spell.target == myHero or GetDistance(spell.endPos) < 500 then
		
		if self.AIOConfig.SummonerConfig.Heal and myHero:CanUseSpell(self.slot["SummonerHeal"]) == READY 
		and myHero.health/myHero.maxHealth < self.AIOConfig.SummonerConfig.Healper/100 then
			CastSpell(self.slot["SummonerHeal"])
		end
						
		if self.AIOConfig.SummonerConfig.Barrier and myHero:CanUseSpell(self.slot["SummonerBarrier"]) == READY 
		and myHero.health/myHero.maxHealth < self.AIOConfig.SummonerConfig.Barper/100 then
			CastSpell(self.slot["SummonerBarrier"])
		end
	end
end


function AIO:Summonerspell_Mana()
	if not myHero.dead and myHero:CanUseSpell(self.slot["SummonerMana"]) == READY and myHero.mana/myHero.maxMana < self.AIOConfig.SummonerConfig.Manaper/100 then
		CastSpell(self.slot["SummonerMana"])
	end
end

function AIO:Summonerspell_Revive()
	if myHero:CanUseSpell(self.slot["SummonerRevive"]) == READY and myHero.dead then
		CastSpell(self.slot["SummonerRevive"])
	end
end

function AIO:Summonerspell_ASLoadMinions()
	for i = 1, objManager.maxObjects do
		local obj = objManager:getObject(i)
		if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
			if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = obj
			elseif obj.name == "Worm12.1.1" then self.Nashor = obj
			elseif obj.name == "Dragon6.1.1" then self.Dragon = obj
			elseif obj.name == "AncientGolem1.1.1" then self.Golem1 = obj
			elseif obj.name == "AncientGolem7.1.1" then self.Golem2 = obj
			elseif obj.name == "LizardElder4.1.1" then self.Lizard1 = obj
			elseif obj.name == "LizardElder10.1.1" then self.Lizard2 = obj end
		end
	end
end

function AIO:Summonerspell_Smite()
	if not self.Smiteison or not self.AIOConfig.SummonerConfig.ON then return end
	self:Summonerspell_checkDeadMonsters()
	
	self.active = ((self.AIOConfig.SummonerConfig.hold and not self.AIOConfig.SummonerConfig.switcher) or (not self.AIOConfig.SummonerConfig.hold and self.AIOConfig.SummonerConfig.switcher))
	
	if self.active then
		self.AIOConfig.SummonerConfig._param[self.textNO].text = "自动惩戒已开启"
	else
		self.AIOConfig.SummonerConfig._param[self.textNO].text = "自动惩戒已关闭"
	end
	if not self.active and self.AIOConfig.SummonerConfig.smitenashor and self.Nashor ~= nil and self.Nashor.x ~= nil then
		if GetDistance(self.Nashor)<=self.range+200 then self.active = true end
	end
	if not self.active and self.AIOConfig.SummonerConfig.smitenashor and self.Vilemaw ~= nil then self.active = true end
	self.smiteDamage = math.max(20*myHero.level+370,30*myHero.level+330,40*myHero.level+240,50*myHero.level+100)
	self.qDamage = 250+150*myHero:GetSpellData(_Q).level
	self.mixDamage = self.qDamage+self.smiteDamage
	self.rDamage = 1000+.7*myHero.ap
	self.mixdDamage = self.rDamage+self.smiteDamage
	self.canuseQ = (myHero.charName == "Nunu" and myHero:CanUseSpell(_Q) == READY)
	self.canuseR = (myHero.charName == "Chogath" and myHero:CanUseSpell(_R) == READY)
	if self.slot["SummonerSmite"] ~= nil then self.canusesmite = (myHero:CanUseSpell(self.slot["SummonerSmite"]) == READY) end
	if self.active and not myHero.dead and (self.canusesmite or self.canuseQ or self.canuseR) then
		if self.Vilemaw ~= nil then self:Summonerspell_checkMonster(self.Vilemaw) end
		if self.Nashor ~= nil then self:Summonerspell_checkMonster(self.Nashor) end
		if self.Dragon ~= nil then self:Summonerspell_checkMonster(self.Dragon) end
		if self.Golem1 ~= nil then self:Summonerspell_checkMonster(self.Golem1) end
		if self.Golem2 ~= nil then self:Summonerspell_checkMonster(self.Golem2) end
		if self.Lizard1 ~= nil then self:Summonerspell_checkMonster(self.Lizard1) end
		if self.Lizard2 ~= nil then self:Summonerspell_checkMonster(self.Lizard2) end
	end
end

function AIO:Summonerspell_checkMonster(object)
	if object ~= nil and not object.dead and object.visible and object.x ~= nil then
		local DistanceMonster = GetDistance(object)
		if self.canusesmite and DistanceMonster <= self.range and object.health <= self.smiteDamage then
			CastSpell(self.slot["SummonerSmite"], object)
		elseif self.canuseQ and DistanceMonster <= 125+200 then
			if self.canusesmite and object.health <= self.mixDamage then
				CastSpell(_Q, object)
				--CastSpell(self.slot["SummonerSmite"], object)
			elseif object.health <= self.qDamage then
				CastSpell(_Q, object)
			end
		elseif self.canuseR and DistanceMonster <= 150+200 then
			if self.canusesmite and object.health <= self.mixdDamage then
				CastSpell(_R, object)
				--CastSpell(self.slot["SummonerSmite"], object)
			elseif object.health <= self.rDamage then
				CastSpell(_R, object)
			end
		end
	end
end

function AIO:Summonerspell_OnDraw()
	if not self.Smiteison or not self.AIOConfig.SummonerConfig.ON then return end
	self:Summonerspell_checkDeadMonsters()
	if self.slot["SummonerSmite"] ~= nil and self.active and self.AIOConfig.SummonerConfig.drawrange and not myHero.dead then
		DrawCircle(myHero.x, myHero.y, myHero.z, self.range, 0x992D3D)
	end
	if not myHero.dead and (self.AIOConfig.SummonerConfig.drawtext or self.AIOConfig.SummonerConfig.drawcircles) then
		if self.Vilemaw ~= nil then self:Summonerspell_MonsterDraw(self.Vilemaw) end
		if self.Nashor ~= nil then self:Summonerspell_MonsterDraw(self.Nashor) end
		if self.Dragon ~= nil then self:Summonerspell_MonsterDraw(self.Dragon) end
		if self.Golem1 ~= nil then self:Summonerspell_MonsterDraw(self.Golem1) end
		if self.Golem2 ~= nil then self:Summonerspell_MonsterDraw(self.Golem2) end
		if self.Lizard1 ~= nil then self:Summonerspell_MonsterDraw(self.Lizard1) end
		if self.Lizard2 ~= nil then self:Summonerspell_MonsterDraw(self.Lizard2) end
	end
end

function AIO:Summonerspell_MonsterDraw(object)
	if object ~= nil and not object.dead and object.visible and object.x ~= nil then
		local DistanceMonster = GetDistance(object)
		if self.active and self.AIOConfig.SummonerConfig.drawcircles and (self.canusesmite or self.canuseQ or self.canuseR) and DistanceMonster <= self.range then
			local healthradius = object.health*100/object.maxHealth
			DrawCircle(object.x, object.y, object.z, healthradius+100, 0x00FF00)
			if self.canusesmite then
				local smitehealthradius = self.smiteDamage*100/object.maxHealth
				DrawCircle(object.x, object.y, object.z, smitehealthradius+100, 0x00FFFF)
			end
			if self.canuseQ and self.canusesmite then
				local Qsmitehealthradius = self.mixDamage*100/object.maxHealth
				DrawCircle(object.x, object.y, object.z, Qsmitehealthradius+100, 0x00FFFF)
			elseif self.canuseQ then
				local Qhealthradius = self.qDamage*100/object.maxHealth
				DrawCircle(object.x, object.y, object.z, Qhealthradius+100, 0x00FFFF)
			end
			if self.canuseR and self.canusesmite then
				local Rsmitehealthradius = self.mixdDamage*100/object.maxHealth
				DrawCircle(object.x, object.y, object.z, Rsmitehealthradius+100, 0x00FFFF)
			elseif self.canuseR then
				local Rhealthradius = self.rDamage*100/object.maxHealth
				DrawCircle(object.x, object.y, object.z, Rhealthradius+100, 0x00FFFF)
			end
		end
		if self.AIOConfig.SummonerConfig.drawtext and DistanceMonster <= self.range*2 then
			local wtsobject = WorldToScreen(D3DXVECTOR3(object.x,object.y,object.z))
			local objectX, objectY = wtsobject.x, wtsobject.y
			local onScreen = OnScreen(wtsobject.x, wtsobject.y)
			if onScreen then
				local statusdmgS = self.smiteDamage*100/object.health
				local statuscolorS = (self.canusesmite and 0xFF00FF00 or 0xFFFF0000)
				local textsizeS = statusdmgS < 100 and math.floor((statusdmgS/100)^2*20+8) or 28
				textsizeS = textsizeS > 16 and textsizeS or 16
				DrawText(string.format("%.1f", statusdmgS).."% - Smite", textsizeS, objectX-40, objectY+38, statuscolorS)
				if myHero.charName == "Nunu" and myHero:GetSpellData(_Q).level>0 then
					local statusdmgQ = self.qDamage*100/object.health
					local statuscolorQ = (self.canuseQ and 0xFF00FF00 or 0xFFFF0000)
					local textsizeQ = statusdmgQ < 100 and math.floor((statusdmgQ/100)^2*20+8) or 28
					textsizeQ = textsizeQ > 16 and textsizeQ or 16
					DrawText(string.format("%.1f", statusdmgQ).."% - Q", textsizeQ, objectX-40, objectY+56, statuscolorQ)
					if self.slot["SummonerSmite"] ~= nil then
						local statusdmgSQ = self.mixDamage*100/object.health
						local statuscolorSQ = ((self.canusesmite and self.canuseQ) and 0xFF00FF00 or 0xFFFF0000)
						local textsizeSQ = statusdmgSQ < 100 and math.floor((statusdmgSQ/100)^2*20+8) or 28
						textsizeSQ = textsizeSQ > 16 and textsizeSQ or 16
						DrawText(string.format("%.1f", statusdmgSQ).."% - Smite+Q", textsizeSQ, objectX-40, objectY+74, statuscolorSQ)
					end
				end
				if myHero.charName == "Chogath" and myHero:GetSpellData(_R).level>0 then
					local statusdmgR = self.rDamage*100/object.health
					local statuscolorR = (self.canuseR and 0xFF00FF00 or 0xFFFF0000)
					local textsizeR = statusdmgR < 100 and math.floor((statusdmgR/100)^2*20+8) or 28
					textsizeR = textsizeR > 16 and textsizeR or 16
					DrawText(string.format("%.1f", statusdmgR).."% - R", textsizeR, objectX-40, objectY+56, statuscolorR)
					if self.slot["SummonerSmite"] ~= nil then
						local statusdmgSR = self.mixdDamage*100/object.health
						local statuscolorSR = ((self.canusesmite and self.canuseR) and 0xFF00FF00 or 0xFFFF0000)
						local textsizeSR = statusdmgSR < 100 and math.floor((statusdmgSR/100)^2*20+8) or 28
						textsizeSR = textsizeSR > 16 and textsizeSR or 16
						DrawText(string.format("%.1f", statusdmgSR).."% - Smite+R", textsizeSR, objectX-40, objectY+74, statuscolorSR)
					end
				end	
			end
		end
	end
end

function AIO:Summonerspell_OnCreateObj(obj)
	if not self.Smiteison or not self.AIOConfig.SummonerConfig.ON then return end
	if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
		if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = obj
		elseif obj.name == "Worm12.1.1" then self.Nashor = obj
		elseif obj.name == "Dragon6.1.1" then self.Dragon = obj
		elseif obj.name == "AncientGolem1.1.1" then self.Golem1 = obj
		elseif obj.name == "AncientGolem7.1.1" then self.Golem2 = obj
		elseif obj.name == "LizardElder4.1.1" then self.Lizard1 = obj
		elseif obj.name == "LizardElder10.1.1" then self.Lizard2 = obj end
	end
end
function AIO:Summonerspell_OnDeleteObj(obj)
	if not self.Smiteison or not self.AIOConfig.SummonerConfig.ON then return end
	if obj ~= nil and obj.name ~= nil then
		if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = nil
		elseif obj.name == "Worm12.1.1" then self.Nashor = nil
		elseif obj.name == "Dragon6.1.1" then self.Dragon = nil
		elseif obj.name == "AncientGolem1.1.1" then self.Golem1 = nil
		elseif obj.name == "AncientGolem7.1.1" then self.Golem2 = nil
		elseif obj.name == "LizardElder4.1.1" then self.Lizard1 = nil
		elseif obj.name == "LizardElder10.1.1" then self.Lizard2 = nil end
	end
end


function AIO:Summonerspell_checkDeadMonsters()
	if self.Vilemaw ~= nil then if not self.Vilemaw.valid or self.Vilemaw.dead or self.Vilemaw.health <= 0 then self.Vilemaw = nil end end
	if self.Nashor ~= nil then if not self.Nashor.valid or self.Nashor.dead or self.Nashor.health <= 0 then self.Nashor = nil end end
	if self.Dragon ~= nil then if not self.Dragon.valid or self.Dragon.dead or self.Dragon.health <= 0 then self.Dragon = nil end end
	if self.Golem1 ~= nil then if not self.Golem1.valid or self.Golem1.dead or self.Golem1.health <= 0 then self.Golem1 = nil end end
	if self.Golem2 ~= nil then if not self.Golem2.valid or self.Golem2.dead or self.Golem2.health <= 0 then self.Golem2 = nil end end
	if self.Lizard1 ~= nil then if not self.Lizard1.valid or self.Lizard1.dead or self.Lizard1.health <= 0 then self.Lizard1 = nil end end
	if self.Lizard2 ~= nil then if not self.Lizard2.valid or self.Lizard2.dead or self.Lizard2.health <= 0 then self.Lizard2 = nil end end
end