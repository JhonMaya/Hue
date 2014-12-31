<?php exit() ?>--by pqmailer 217.82.23.23
if not VIP_USER or myHero.charName ~= "Lux" then return end

---> Klokje Orbwalk End

class 'Orbwalking'

    Orbwalking.instance = ""

    function Orbwalking:__init()
        self.attacking = false
        self.nextAttack = 0
        self.windUp = 0
        self.windUpTime = 0
        self.disable = false
        self.target = nil
        self.spelldis = false

        self.lastPacket = nil
        
        AddTickCallback(function() self:OnTick() end)
        AddProcessSpellCallback(function(obj, spell) self:OnProcessSpell(obj, spell) end)
        AddSendPacketCallback(function(obj) self:OnSendPacket(obj) end)
    end

    function Orbwalking.Instance()
        if Orbwalking.instance == "" then Orbwalking.instance = Orbwalking() end return Orbwalking.instance 
    end

    function Orbwalking:OnTick()
        if self.attacking and self.windUp <= GetGameTimer() then 
            self.attacking = false

            if self.lastPacket ~= nil then
                SendPacket(tempPacket)
                self.lastPacket = nil
            end
        end
    end

    function Orbwalking:OnSendPacket(p)
        if self.attacking and not self.disable and not spelldis and (p.header == 0x71 or p.header == 0x9A) then
            self.lastPacket =  copyPacket(p)
            p.pos = 1
            p:Block()
        end
    end

    function Orbwalking:OnProcessSpell(object,spell)
        if object== nil or spell == nil then return end 

        if object.isMe and spell.name:find("Attack") then
            self.target = spell.target
            self.windUp = GetGameTimer() + spell.windUpTime
            self.windUpTime = spell.windUpTime
            self.nextAttack =  GetGameTimer() + spell.animationTime
            self.attacking = true
        end 
    end

    function Orbwalking.Enable(bool)
        Orbwalking.Instance().disable = not bool
    end

    function Orbwalking.CanAttack()
        return Orbwalking.Instance():PrivateCanAttack()
    end

    function Orbwalking.NextAttack()
        return Orbwalking.Instance().nextAttack
    end

    function Orbwalking.WindUp()
        return Orbwalking.Instance().windUp
    end

    function Orbwalking.WindUpTime()
        return Orbwalking.Instance().windUpTime
    end

    function Orbwalking.ResetAA()
        Orbwalking.Instance().nextAttack = 0
    end

    function Orbwalking.Attack(target)
        if _G.evade and _G.evade == true then return end
        return Orbwalking.Instance():PrivateAttack(target)
    end

    function Orbwalking:PrivateAttack(target)
        if self:PrivateCanAttack() then
            player:Attack(target)
            self.attacking = true
            return true 
        end
        return false
    end 

    function Orbwalking:PrivateCanAttack()
        return self.nextAttack <= GetGameTimer()
    end

function copyPacket(packet)
      packet.pos = 1
      p = CLoLPacket(packet.header)
      for i=1,packet.size-1,1 do
        p:Encode1(packet:Decode1())
      end
      p.dwArg1 = packet.dwArg1
      p.dwArg2 = packet.dwArg2
      return p
end

---> Klokje Orbwalk End

---> AoE_Skillshot_Position Start

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

-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
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
	
	return position
end

---> AoE_Skillshot_Position End

local RangeQ, RangeE, RangeR, RangeAD = 1300, 1100, 3500, 550
local EParticle = nil
local ETriggerHero = false
local ETriggerInstant = false
local ETriggerRadius, EDelay = 275, 0.15
local IsRecalling = false
local EnemyTable = {}
local MinionTable = {}
local JungleTable = {}
local ts
local moveTick = nil
local QReady, WReady, EReady, RReady, IGNITEReady = false, false, false, false, false
local IGNITESlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
local ItemTable = {
	["DFG"] = {id = 3128, range = 750},
	["BWC"] = {id = 3144, range = 450},
	["HXG"] = {id = 3146, range = 700}
}
local JungleCamps = {
	["Worm12.1.1"] = true,
	["Dragon6.1.1"] = true,
	["AncientGolem1.1.1"] = true,
	["AncientGolem7.1.1"] =  true,
	["LizardElder4.1.1"] =  true,
	["LizardElder10.1.1"] = true
}
local pd, qp, ep, rp, qCol

function OnLoad()		
    Config = scriptConfig("Lux - Shine for us", "luxshineforus")

	Config:addSubMenu("General", "general")
	Config.general:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))
	Config.general:addParam("combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.general:addParam("comboR", "Use R in Combo", SCRIPT_PARAM_ONOFF, true)
	Config.general:addParam("comboRalways", "Force R in Combo", SCRIPT_PARAM_ONOFF, true)
	Config.general:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
	Config.general:addParam("ignite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
	Config.general:addParam("farmAA", "Farm with AA", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("J"))
	Config.general:addParam("farmE", "Farm with E", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("K"))
	Config.general:addParam("triggerEAuto", "Auto Trigger E", SCRIPT_PARAM_ONOFF, true)
	Config.general:addParam("triggerECount", "E Trigger Enemy Count", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	Config.general:addParam("stealBuffs", "Try to steal Buffs with R", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("A"))
	Config.general:addParam("moveToCursor", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Draw", "draw")
	Config.draw:addParam("disableDraw", "Disable OnDraw", SCRIPT_PARAM_ONOFF, false)
	Config.draw:addParam("drawTarget", "Draw Line to Target", SCRIPT_PARAM_ONOFF, true)
	Config.draw:addParam("drawRangeQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
	Config.draw:addParam("drawRangeR", "Draw R Range (Minimap)", SCRIPT_PARAM_ONOFF, true)
	Config.draw:addParam("drawRQuality", "Circle Quality", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
	Config.draw:addParam("drawDMGCalc", "Draw DMG Calculation", SCRIPT_PARAM_ONOFF, true)

	Config.general:permaShow("farmE")

	LoadEnemies()

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, RangeR, DAMAGE_MAGIC)
	ts.name = "Lux"
	Config.general:addTS(ts)

	MinionTable = minionManager(MINION_ENEMY, RangeE, myHero, MINION_SORT_HEALTH_ASC)

	for i=1, objManager.maxObjects do
		local obj = objManager:getObject(i)

		if obj and obj.type == "obj_AI_Minion" and obj.name and JungleCamps[obj.name] then
			table.insert(JungleTable, obj)
		end
	 end

	Orbwalking.Instance()

	pd = ProdictManager.GetInstance()
    qp = pd:AddProdictionObject(_Q, RangeQ, 1200, 0.245, 50)
    ep = pd:AddProdictionObject(_E, RangeE, 1400, 0.245, 0)
    rp = pd:AddProdictionObject(_R, RangeR, math.huge, 0.245, 50)

    qCol = Collision(RangeQ, 1200, 0.245, 50)

	PrintChat("<font color='#CCCCCC'>>> Lux by PQMailer loaded <<</font>")
	PrintChat("<font color='#CCCCCC'>>> THIS IS A BETA; REPORT ALL BUGS <<</font>")
end

function OnTick()
	--if _G.evade and _G.evade == true then return end
	--if IsRecalling then return end
    if Config.general.combo then Config.general.farmAA = false end
	if Config.general.combo or Config.general.harass or Config.general.farmAA then Orbwalking.Enable(true) else Orbwalking.Enable(false) end

	ts:update()
	MinionTable:update()
	CDHandler()

	if Config.general.ksR then
		KSR()
	end

	if Config.general.ignite then
		AutoIgnite()
	end

	if EParticle ~= nil and not EParticle.valid then
		EParticle = nil
	end

	AutoTriggerE()

	if Config.general.combo then
		Combo()
	end

	if Config.general.harass then
		Harass()
	end

	if Config.general.stealBuffs then
		StealBuffsR()
	end

	if Config.general.farmAA and not (Config.general.combo or Config.general.harass) then
		FarmAA()
	end

	if Config.general.farmE and not (Config.general.combo or Config.general.harass) then
		FarmE()
	end

	if Config.general.moveToCursor and (Config.general.combo or Config.general.harass or Config.general.farmAA) then
		MoveToCursor()
	end
end

function OnCreateObj(obj)
	if obj.valid and obj.name:find("LuxLightstrike_tar") then
		EParticle = obj
	elseif obj.valid and obj.name:find("LuxBlitz_nova") then
		EParticle = nil
		ETriggerHero = false
		ETriggerInstant = false
	elseif obj and obj.type == "obj_AI_Minion" and obj.name and JungleCamps[obj.name] then
		table.insert(JungleTable, obj)
	end
	return
end

function OnDeleteObj(obj)
	if obj.name:find("LuxBlitz_nova") then
		EParticle = nil
		ETriggerHero = false
		ETriggerInstant = false
	elseif obj and obj.type == "obj_AI_Minion" and obj.name and JungleCamps[obj.name] then
		for i=1, #JungleTable do
			local Monster = JungleTable[i]

			if Monster and Monster.name == obj.name then
				table.remove(JungleTable, i)
			end
		end
	end
end

function OnTowerFocus(tower, unit)
	if unit.type == "obj_AI_Hero" and unit.team ~= myHero.team and ValidTarget(unit, RangeQ) and QReady then
        qp:GetPredictionCallBack(unit, CastQ)
	end
	if unit.type == "obj_AI_Minion" and unit.team ~= myHero.team and ValidTarget(unit, RangeAD) and Config.general.farmAA and Orbwalking.CanAttack() and GetDistance(unit) <= RangeAD and unit.health <= (getDmg("AD", unit, myHero) + 4) * 1.05 then
		Orbwalking.Attack(target)
	end
end

function OnRecall(hero, channelTimeInMs)
	if hero.networkID == player.networkID then
		IsRecalling = true
	end
end

function OnAbortRecall(hero)
	if hero.networkID == player.networkID then
		IsRecalling = false
	end	
end

function OnFinishRecall(hero)
	if hero.networkID == player.networkID then
		IsRecalling = false
	end
end

function OnDraw()
	if Config.draw.disableDraw then return end

	if Config.draw.drawDMGCalc then
		for i=1, #EnemyTable do
            local Enemy = EnemyTable[i]["object"]

			if ValidTarget(Enemy) then
				if EnemyTable[i]["killable"] == true then
					DrawText3D(tostring("Kill him"), Enemy.x, Enemy.y, Enemy.z, 16, ARGB(255, 255, 10, 20), true)
				else
					DrawText3D(tostring("Harass him"), Enemy.x, Enemy.y, Enemy.z, 16, ARGB(255, 255, 10, 20), true)
				end
			end
		end
	end

    if ts.target and Config.draw.drawTarget then
    	DrawLine3D(myHero.x, myHero.y, myHero.z, ts.target.x, ts.target.y, ts.target.z, 3, 0xFFFF0000)
    end
	if Config.draw.drawRangeQ and QReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, RangeQ, 0xCCCCCC)
	end
	if Config.draw.drawRangeR and RReady then
		DrawCircleMinimap(myHero.x, myHero.y, myHero.z, RangeR, 1, CCCCCC, Config.draw.drawRQuality*10)
	end
end

function Harass()
	if ValidTarget(ts.target) then
		local Distance = myHero:GetDistance(ts.target)

		if Distance <= RangeE and EReady then
			ep:GetPredictionCallBack(ts.target, CastQ)
			ETriggerHero = true
		end
		if Distance <= RangeAD and Orbwalking.CanAttack() and (TargetHaveBuff("luxilluminatingfraulein", ts.target) or ts.target.armor < 80 or ((getDmg("AD", ts.target, myHero)*2) >= ts.target.health)) then
			Orbwalking.Attack(ts.target)
		end
	end
	return
end

function Combo()
	if ValidTarget(ts.target) then
		local Distance = myHero:GetDistance(ts.target)
		local EnemyCount = CountEnemyHeroInRange(RangeE)
		local index = 1
		for i=1, #EnemyTable do
			if EnemyTable[i]["object"].networkID == ts.target.networkID then
				index = i
				break
			end
		end

		if EnemyTable[index]["killable"] == true then
			CastItems(ts.target)
		end
		if Distance <= RangeQ and QReady then
			qp:GetPredictionCallBack(ts.target, CastQ)
		end
		if Distance <= RangeE and EReady then
			if EnemyCount >= 3 then
				local spellPos = GetAoESpellPosition(ETriggerRadius, ts.target, EDelay)
				CastSpell(_E, spellPos.x, spellPos.z)
                ETriggerHero = true
			else
				ep:GetPredictionCallBack(ts.target, CastE)
                ETriggerInstant = true
			end
		end
		if Distance <= RangeR and RReady and (EnemyTable[index]["killable"] == true or Config.general.comboRalways) and Config.general.comboR then
			rp:GetPredictionCallBack(ts.target, CastR)
		end
		if Distance <= RangeAD and Orbwalking.CanAttack() and (TargetHaveBuff("luxilluminatingfraulein", ts.target) or ts.target.armor < 80 or ((getDmg("AD", ts.target, myHero)*2) >= ts.target.health)) then
			Orbwalking.Attack(ts.target)
		end
	end
	return
end

function AutoTriggerE()
	if EParticle and EParticle.valid then
		if ETriggerHero or Config.general.triggerEAuto then
			local Count = 0

			for i=1, #EnemyTable do
				local Enemy = EnemyTable[i][object]

				if ValidTarget(Enemy) and GetDistance(Enemy, EParticle) <= ETriggerRadius then
					if ETriggerHero then
						CastSpell(_E)
						return
					elseif Config.general.triggerEAuto then
						Count = Count + 1
					end
				end
			end
			if Count >= Config.general.triggerECount then
				CastSpell(_E)
				return
			end
		elseif ETriggerInstant then
			CastSpell(_E)
			return
		end
	end
end

function CastQ(unit, pos, spell)
	local MinionCol = qCol:GetMinionCollision(myHero, unit)

	if not MinionCol and RangeQ >= GetDistance(unit) and QReady then
		CastSpell(_Q, pos.x, pos.z)
	end
end

function CastE(unit, pos, spell)
	if RangeE >= GetDistance(unit) and EReady then
		CastSpell(_E, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	if RangeR >= GetDistance(unit) and RReady then
		CastSpell(_R, pos.x, pos.z)
	end
end

function FarmE()
	local Minions = {}
	local MinionsPoints = {}

	if myHero.mana < (myHero:GetSpellData(_Q).mana + myHero:GetSpellData(_W).mana + 2*myHero:GetSpellData(_E).mana + myHero:GetSpellData(_R).mana) then
		return
	end

	for _, minion in pairs(MinionTable.objects) do
		if minion and ValidTarget(minion, RangeE) then
			if minion.health <= (getDmg("E", minion, myHero) + (TargetHaveBuff("luxilluminatingfraulein", minion) and getDmg("P", minion, myHero) or 0)) then
				table.insert(Minions, minion)
				table.insert(MinionsPoints, Vector(minion))
			end
		end
	end

	if #Minions > 0 then
		local spellPos = GetCenter(MinionsPoints)
		local Count = 0

		for i=1, #Minions do
			if GetDistance(Minions[i], spellPos) <= ETriggerRadius then
				Count = Count + 1
			end
		end

		if Count >= 2 then
			CastSpell(_E, spellPos.x, spellPos.z)
			ETriggerInstant = true
			return
		else
			return
		end
	else
		return
	end
end

function FarmAA()
	local killableMinions = {}
    local lowestMinion = nil

	for _, minion in pairs(MinionTable.objects) do
		if ValidTarget(minion, RangeAD) and Orbwalking.CanAttack() then
			if minion.health <= (getDmg("AD", minion, myHero) + (TargetHaveBuff("luxilluminatingfraulein", minion) and getDmg("P", minion, myHero) or 0)) then
				table.insert(killableMinions, minion)
			end
		end
	end

	if #killableMinions > 0 then
		for i=1, #killableMinions do
			local minion = killableMinions[i]
			
            if lowestMinion and lowestMinion.valid and minion and minion.valid then
                if minion.health < lowestMinion.health then
                    lowestMinion = minion
                end
            else
                lowestMinion = minion
            end
		end

        if lowestMinion then
            Orbwalking.Attack(lowestMinion)
            return
        end
	else
        return
    end
end

function KSR()
	if not RReady then return end

	for i=1, #EnemyTable do
		local Enemy = EnemyTable[i]["object"]

		if ValidTarget(Enemy, RangeR) and getDmg("R", Enemy, myHero) >= Enemy.health then
            rp:GetPredictionCallBack(Enemy, CastR)
			return
		end
	end
end

function StealBuffsR()
	if not RReady then return end

	for i=1, #JungleTable do
		local Monster = JungleTable[i]

		if Monster and Monster.valid and Monster.visible and not Monster.dead and GetDistance(Monster) <= RangeR and GetDistance(Monster) > RangeE and getDmg("R", Monster, myHero) >= Monster.health then
			CastSpell(_R, Monster.x, Monster.z)
			return
		end
	end
end

function LoadEnemies()
    for i = 1, heroManager.iCount do
        local hero = heroManager:GetHero(i)
        if hero.team ~= player.team then
            table.insert(EnemyTable, {["object"] = hero, ["killable"] = false})
        end
    end
end

function CDHandler()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
	IGNITEReady = (IGNITESlot ~= nil and myHero:CanUseSpell(IGNITESlot) == READY)
end

function DMGCalculation()
	for i=1, #EnemyTable do
		local Enemy = EnemyTable[i]["object"]

		if ValidTarget(Enemy) then
			local pdmg = getDmg("P", Enemy, myHero, 3)
			local qdmg = getDmg("Q", Enemy, myHero, 3)
			local wdmg = getDmg("W", Enemy, myHero, 3)
			local edmg = getDmg("E", Enemy, myHero, 3)
			local rdmg = getDmg("R", Enemy, myHero, 3)
			local ADdmg = getDmg("AD", Enemy, myHero, 3)

			local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmg("DFG", Enemy, myHero) or 0)
			local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmg("HXG", Enemy, myHero) or 0)
			local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmg("BWC", Enemy, myHero) or 0)

			local onhitdmg = (GetInventoryHaveItem(3057) and getDmg("SHEEN", Enemy, myHero) or 0) + (GetInventoryHaveItem(3078) and getDmg("TRINITY", Enemy, myHero) or 0) + (GetInventoryHaveItem(3100) and getDmg("LICHBANE", Enemy, myHero) or 0) + (GetInventoryHaveItem(3025) and getDmg("ICEBORN", Enemy, myHero) or 0) + (GetInventoryHaveItem(3087) and getDmg("STATIKK", Enemy, myHero) or 0) + (GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD", Enemy, myHero) or 0)
			local onspelldamage = (GetInventoryHaveItem(3151) and getDmg("LIANDRYS", Enemy, myHero) or 0) + (GetInventoryHaveItem(3188) and getDmg("BLACKFIRE", Enemy, myHero) or 0)

			local IGNITEDamage = (IGNITESlot and getDmg("IGNITE", Enemy, myHero) or 0)

			local totaldmg = ADdmg + onhitdmg + onspelldamage
			local mana = 0

			if TargetHaveBuff("luxilluminatingfraulein", Enemy) then
				totaldmg = totaldmg + pdmg
			end
			if QReady then
				totaldmg = totaldmg + qdmg
				mana = mana + myHero:GetSpellData(_Q).mana
			end
			if WReady then
				totaldmg = totaldmg + wdmg
				mana = mana + myHero:GetSpellData(_W).mana
			end
			if EReady then
				totaldmg = totaldmg + edmg
				mana = mana + myHero:GetSpellData(_E).mana
			end
			if RReady then
				totaldmg = totaldmg + rdmg
				mana = mana + myHero:GetSpellData(_R).mana
			end
			if GetInventoryItemIsCastable(3128) then
				totaldmg = totaldmg + dfgdamage
			end
			if GetInventoryItemIsCastable(3146) then
				totaldmg = totaldmg + hxgdamage
			end
			if GetInventoryItemIsCastable(3144) then
				totaldmg = totaldmg + bwcdamage
			end
			if IGNITEReady then
				totaldmg = totaldmg + IGNITEDamage
			end

			Enemy[i]["killable"] = ((totaldmg >= Enemy.health and myHero.mana >= mana) and true or false)
		end
	end
end

function AutoIgnite()
	if not IGNITEReady then return end

	for i=1, #EnemyTable do
		local Enemy = EnemyTable[i]["object"]

		if ValidTarget(Enemy, 600) then
			if getDmg("IGNITE", Enemy, myHero) >= Enemy.health then
				CastSpell(IGNITESlot, Enemy)
			end
		end
	end
end

function CastItems(Target)
	if not Target then return end
	for i=1, #ItemTable do
		if GetInventoryItemIsCastable(ItemTable[i][id]) then
			if ValidTarget(Target, ItemTable[i][range]) then
				CastSpell(GetInventorySlotItem(ItemTable[i][id]), Target)
			end
		end
	end
end

function MoveToCursor(range)
    if moveTick == nil or GetTickCount()-moveTick >= 71 then
        moveTick = GetTickCount()
        local moveDist = 480 + (GetLatency()/10)
        if not range then
            if GetDistance(mousePos) < moveDist and GetDistance(mousePos) > 100 then
                moveDist = GetDistance(mousePos)
            end
        end
        local moveSqr = math.sqrt((mousePos.x - player.x)^2+(mousePos.z - player.z)^2)
        local moveX = player.x + (range and range or moveDist)*((mousePos.x - player.x)/moveSqr)
        local moveZ = player.z + (range and range or moveDist)*((mousePos.z - player.z)/moveSqr)
        player:MoveTo(moveX, moveZ)
    end 
end

--UPDATEURL=
--HASH=33C7D65A0F3B8DEFDDD97F604F0A3440
