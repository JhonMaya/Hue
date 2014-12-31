<?php exit() ?>--by UglyOldGuy 64.17.247.66
-- Name Check --  
if myHero.charName ~= "Leblanc" then return end

-- Loading Function --
function OnLoad()
	Variables()
	--evadeLoad()
	LeblancMenu()
	PrintChat("<font color='#FFFF00'> >> Leblanc - The Deceiver 1.2 Loaded!! <<</font>")
end

-- Tick Function --
function OnTick()
	Checks()
	UseConsumables()
	DamageCalculation()
	--evadeTick()

	-- Menu Vars --
	ComboKey =   LeblancMenu.combo.comboKey
	FarmingKey = LeblancMenu.farming.farmKey
	HarassKey =  LeblancMenu.harass.harassKey
	JungleKey =  LeblancMenu.jungle.jungleKey
		
	if ComboKey then FullCombo() end
	if HarassKey then HarassCombo() end
	if JungleKey then JungleClear() end
	if LeblancMenu.combo.smartW then smartW() end
	if LeblancMenu.ks.killSteal then KillSteal() end
	if LeblancMenu.ks.autoIgnite then AutoIgnite() end
	--if LeblancMenu.clone.cloneSlice ~= 1 then CloneLogic() end
	if FarmingKey and not (ComboKey or HarassKey) then FarmMinions() end
end

function Variables()
	qRange, wRange, eRange = 700, 720, 1000
	qName, wName, eName, rName = "Sigil of Silence", "Distortion", "Ethereal Chains", "Mimic"
	qReady, wReady, eReady, rReady = false, false, false, false
	wSpeed, wDelay, wWidth = 2000, .25, 100
	eSpeed, eDelay, eWidth = 1600, .25, 95
	wPos, ePos = nil, nil
	lastQ, lastW, lastE = false, false, false
	leblancW, leblancImage = nil, nil
	if VIP_USER then
		Prodict = ProdictManager.GetInstance()
		ProdictW = Prodict:AddProdictionObject(_W, wRange, wSpeed, wDelay, wWidth, myHero)
		ProdictE = Prodict:AddProdictionObject(_E, eRange, eSpeed, eDelay, eWidth, myHero)
		if vPredictionExists then
			vPred = VPrediction()
		end
	end
	hpReady, mpReady, fskReady, Recalling = false, false, false, false
	TextList = {"Harass him!!", "Q KILL!!", "Q + W Kill!", "Q+W+QP Kill!", "Q+W+E+QP Kill!", "Full Combo Kill!", "Need Mana or CD!"}
	KillText = {}
	colorText = ARGB(255,0,0,255)
	usingHPot, usingMPot = false, false
	enemyMinions = minionManager(MINION_ENEMY, qRange, player, MINION_SORT_HEALTH_ASC)
	lastAnimation = nil
	focusedtarget = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
	JungleMobs = {}
	JungleFocusMobs = {}
	comboFinished = false
	debugMode = false
	dfgDmg, hxgDmg, bwcDmg, iDmg  = 0, 0, 0, 0
	qDmg, qpDmg, wDmg, eDmg = 0, 0, 0, 0
	qrDmg, wrDmg, erDmg = 0, 0, 0

	-- Stolen from Apple who Stole it from Sida --
	JungleMobNames = { -- List stolen from SAC Revamped. Sorry, Sida!
        ["wolf8.1.1"] = true,
        ["wolf8.1.2"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.1"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["wolf2.1.1"] = true,
        ["wolf2.1.2"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.1"] = true,
        ["LesserWraith3.1.2"] = true,
        ["LesserWraith3.1.4"] = true,
        ["YoungLizard4.1.2"] = true,
        ["YoungLizard4.1.3"] = true,
        ["SmallGolem5.1.1"] = true,
}

	FocusJungleNames = {
        ["Dragon6.1.1"] = true,
        ["Worm12.1.1"] = true,
        ["GiantWolf8.1.1"] = true,
        ["AncientGolem7.1.1"] = true,
        ["Wraith9.1.1"] = true,
        ["LizardElder10.1.1"] = true,
        ["Golem11.1.2"] = true,
        ["GiantWolf2.1.1"] = true,
        ["AncientGolem1.1.1"] = true,
        ["Wraith3.1.1"] = true,
        ["LizardElder4.1.1"] = true,
        ["Golem5.1.2"] = true,
		["GreatWraith13.1.1"] = true,
		["GreatWraith14.1.1"] = true,
}

	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(JungleFocusMobs, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobs, object)
			end
		end
	end
end

-- Our Menu --
function LeblancMenu()
	LeblancMenu = scriptConfig("Leblanc - The Deceiver", "Leblanc")
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Combo Settings]", "combo")
		LeblancMenu.combo:addParam("comboKey", "Smart Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 88)
		LeblancMenu.combo:addParam("comboItems", "Use Items with Burst", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.combo:addParam("comboOrbwalk", "OrbWalk on Combo", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.combo:addParam("smartW", "Use Smart W", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.combo:addSubMenu("Targets", "Targets")
		for i, enemy in ipairs(GetEnemyHeroes()) do
			LeblancMenu.combo.Targets:addParam("DontBurst"..enemy.charName, "Don't Burst "..enemy.charName, SCRIPT_PARAM_ONOFF, false)
		end	
		LeblancMenu.combo:permaShow("comboKey") 
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Harass Settings]", "harass")
		LeblancMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		LeblancMenu.harass:addParam("harassType", "Harass Type", SCRIPT_PARAM_SLICE, 2, 1, 2,0)
		LeblancMenu.harass:addParam("HarassInfo","1 - W Gapclose -> Q -> W", SCRIPT_PARAM_INFO, "")
		LeblancMenu.harass:addParam("HarassInfo2","2 - Q -> W -> W", SCRIPT_PARAM_INFO, "")
		LeblancMenu.harass:addParam("secW", "Use 2nd W in Harass", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.harass:addParam("harassOrbwalk", "OrbWalk on Harass", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.harass:permaShow("harassKey") 
		
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Farming Settings]", "farming")
		LeblancMenu.farming:addParam("farmKey", "Farming ON/Off (Z)", SCRIPT_PARAM_ONKEYTOGGLE, false, 90)
		LeblancMenu.farming:addParam("qFarm", "Farm with "..qName.." (Q)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.farming:addParam("qFarmMana", "Min Mana % for Farming", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
		LeblancMenu.farming:permaShow("farmKey") 
		
	LeblancMenu:addSubMenu("["..myHero.charName.." - Clear Settings]", "jungle")
		LeblancMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86)
		LeblancMenu.jungle:addParam("jungleQ", "Clear with "..qName.." (Q)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleW", "Clear with "..wName.." (W)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleE", "Clear with "..eName.." (E)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleOrbwalk", "Orbwalk the Jungle", SCRIPT_PARAM_ONOFF, true)

	--[[LeblancMenu:addSubMenu("["..myHero.charName.." - Evade Settings]", "evade")
		LeblancMenu.evade:addParam("evadeSkills", "Dodge Skills with  "..wName.." (W)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.evade:addParam("evadeSkillsR", "Dodge Skills with  "..rName.." (R)", SCRIPT_PARAM_ONOFF, true)

	LeblancMenu:addSubMenu("["..myHero.charName.." - Clone Settings]", "clone")
		LeblancMenu.clone:addParam("cloneSlice", "Clone Mode", SCRIPT_PARAM_SLICE, 2, 1, 3, 0)
		LeblancMenu.clone:addParam("Option1Info","1 - No Logic", SCRIPT_PARAM_INFO, "")
		LeblancMenu.clone:addParam("Option2Info","2 - Opposite Direction of Hero", SCRIPT_PARAM_INFO, "")
		LeblancMenu.clone:addParam("Option3Info","3 - Towards the taget", SCRIPT_PARAM_INFO, "")]]--
		

	LeblancMenu:addSubMenu("["..myHero.charName.." - KillSteal Settings]", "ks")
		LeblancMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.ks:permaShow("killSteal")
			
	LeblancMenu:addSubMenu("["..myHero.charName.." - Drawing Settings]", "drawing")	
		LeblancMenu.drawing:addParam("mDraw", "Disable All Ranges Drawing", SCRIPT_PARAM_ONOFF, false)
		LeblancMenu.drawing:addParam("cDraw", "Draw Enemy Text", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.drawing:addParam("qDraw", "Draw "..qName.." (Q) Range", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.drawing:addParam("wDraw", "Draw "..wName.." (W) Range", SCRIPT_PARAM_ONOFF, false)
		LeblancMenu.drawing:addParam("eDraw", "Draw "..eName.." (E) Range", SCRIPT_PARAM_ONOFF, false)
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Misc Settings]", "misc")
		LeblancMenu.misc:addParam("ZWItems", "Auto Zhonyas/Wooglets", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("ZWHealth", "Min Health % for Zhonyas/Wooglets", SCRIPT_PARAM_SLICE, 15, 0, 100, -1)
		LeblancMenu.misc:addParam("aMP", "Auto Mana Pots", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("aHP", "Auto Health Pots", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("HPHealth", "Min % for Health Pots", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
	LeblancMenu:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 1, { "Prodiction", "VPrediction" })
	LeblancMenu:addParam("tsType", "Selector Use", SCRIPT_PARAM_LIST, 1, { "HondaTS", "DefaultTS" })
	TargetSelector = TargetSelector(TARGET_LOW_HP, wRange,DAMAGE_MAGIC)
	TargetSelector.name = "Leblanc"
	LeblancMenu:addTS(TargetSelector)
end

-- Our Full Combo --
function FullCombo()
	if LeblancMenu.combo.comboOrbwalk then
		if Target ~= nil then
			OrbWalking(Target)
		else
			moveToCursor()
		end
	end
	if ValidTarget(Target) then
		if LeblancMenu.combo.comboItems then
				UseItems(Target)
		end
		comboFinished = false
		if GetDistance(Target) <= wRange and Target.health > (qDmg + qpDmg + qrDmg + eDmg) then
			if wReady then
				CastW(Target) 
			end
			if qReady and GetDistance(Target) <= qRange then
				CastQ(Target)
			end			
			if rReady and lastQ and GetDistance(Target) <= qRange then
				CastR(Target)
			end
			if eReady and GetDistance(Target) <= eRange then
				CastE(Target)
			end
		elseif GetDistance(Target) <= (wRange + qRange) and Target.health < (qDmg + qpDmg + qrDmg + eDmg) then
			if wReady then
				CastW(Target) 
			end
			if qReady and GetDistance(Target) <= qRange then
				CastQ(Target)
			end			
			if rReady and lastQ and GetDistance(Target) <= qRange then
				CastR(Target)
			end
			if eReady and GetDistance(Target) <= eRange then
				CastE(Target)
			end
			comboFinished = true
		end
	end
end

function HarassCombo()
	if LeblancMenu.harass.harassOrbwalk then
		if ValidTarget() then
			OrbWalking(Target)
		else
			moveToCursor()
		end
	end
	if ValidTarget(Target) then
		if (LeblancMenu.harass.harassType == 1) then
			if GetDistance(Target) <= qRange then
				if qReady then CastQ(Target) end
				if wReady and not wUsed() and qPassive then
					CastW(Target)
				end
			elseif GetDistance(Target) <= wRange and not qReady then
				if wReady then CastW(Target) end
			elseif GetDistance(Target) <= (wRange + qRange) then
				if wReady and not wUsed() then
					CastW(Target)
				end
				if qReady then CastQ(Target) end
			end
			if wUsed() and not qReady then
				if LeblancMenu.harass.secW then CastSpell(_W) end
			end
		elseif (LeblancMenu.harass.harassType == 2) then
			if GetDistance(Target) <= qRange then
				if qReady then CastQ(Target) end
				if wReady and not wUsed() and qPassive then
					CastW(Target)
				end
			end
			if wUsed() and not qReady then
				if LeblancMenu.harass.secW then
					CastSpell(_W)
				end
			end
		end
	end
end

-- Farming Function --
function FarmMinions()
	if not myManaLow() then
		for _, minion in pairs(enemyMinions.objects) do
			local qMinionDmg = getDmg("Q", minion, myHero)
			if ValidTarget(minion) then
				if LeblancMenu.farming.qFarm and qReady and GetDistance(minion) <= qRange and minion.health <= qMinionDmg then
					CastSpell(_Q, minion)
					lastQ, lastW, lastE = true, false, false
				end
			end
		end
	end
end

-- Farming Mana Function --
function myManaLow()
	if myHero.mana < (myHero.maxMana * (LeblancMenu.farming.qFarmMana / 100)) then
		return true
	else
		return false
	end
end

-- Jungle Farming --
function JungleClear()
	JungleMob = GetJungleMob()
	if LeblancMenu.jungle.jungleOrbwalk then
		if JungleMob ~= nil then
			OrbWalking(JungleMob)
		else
			moveToCursor()
		end
	end
	if JungleMob ~= nil then
		if LeblancMenu.jungle.jungleQ and GetDistance(JungleMob) <= qRange then CastSpell(_Q, JungleMob) end
		if not wUsed() and LeblancMenu.jungle.jungleW and GetDistance(JungleMob) <= wRange then CastSpell(_W, JungleMob.x, JungleMob.z) end
		if LeblancMenu.jungle.jungleE and GetDistance(JungleMob) <= eRange then CastSpell(_E, JungleMob.x, JungleMob.z) end
	end
end

-- Get Jungle Mob --
function GetJungleMob()
        for _, Mob in pairs(JungleFocusMobs) do
                if ValidTarget(Mob, qRange) then return Mob end
        end
        for _, Mob in pairs(JungleMobs) do
                if ValidTarget(Mob, qRange) then return Mob end
        end
end

-- Casting our Q into enemies --
function CastQ(enemy)
	if not enemy then 
		if Target then
			enemy = Target
		end
	end
	if not qReady or (GetDistance(enemy) > qRange) then
		return false
	end
	if ValidTarget(enemy) then 
		if VIP_USER then
			Packet("S_CAST", {spellId = _Q, targetNetworkId = enemy.networkID}):send()
			lastQ, lastW, lastE = true, false, false
			return true
		else
			CastSpell(_Q, enemy)
			lastQ, lastW, lastE = true, false, false
			return true
		end
	end
	return false
end

-- Check if W was used once --
function wUsed() 
	local leblancW = myHero:GetSpellData(_W)
	if leblancW.name == "leblancslidereturn" then 
		return true 
	else 
		return false
	end
end

-- Casting W into Enemies --
function CastW(enemy)
	if not enemy then 
		if Target then
			enemy = Target
		end
	end
	if not wReady then
		return false
	end
	if ValidTarget(enemy) and not wUsed() then
		if VIP_USER then
			if LeblancMenu.predType == 1 then
				local wPos = ProdictW:GetPrediction(enemy)
				if wPos and not IsWall(D3DXVECTOR3(wPos.x, wPos.y, wPos.z)) then
					CastSpell(_W, wPos.x, wPos.z)
					lastQ, lastW, lastE = false, true, false
					return true
				end
			else
				if vPredictionExists then
					local CastPosition,  HitChance,  Position = vPred:GetCircularCastPosition(enemy, wDelay, wWidth, wRange)
					if HitChance >= 2 then
						if not IsWall(D3DXVECTOR3(CastPosition.x, CastPosition.y, CastPosition.z)) then
							CastSpell(_W, CastPosition.x, CastPosition.z)
						end
					end
				end
			end
		else
			local wPred = TargetPrediction(wRange, wSpeed, wDelay, wWidth)
           	local wPrediction = wPred:GetPrediction(enemy)
           	if wPrediction then
        		CastSpell(_W, wPrediction.x, wPrediction.z)
        		lastQ, lastW, lastE = false, true, false
				return true
			end
		end
		return false
	end
	return false
end

-- Casting E --
function CastE(enemy)
	if not enemy then 
		if Target then
			enemy = Target
		end
	end
	if not eReady or (GetDistance(enemy) > eRange) then
		return false
	end
	if ValidTarget(enemy) then
		if VIP_USER then
			if LeblancMenu.predType == 1 then
				local ePos = ProdictE:GetPrediction(Target)
				local CollisionE =  Collision(eRange, eSpeed, eDelay, eWidth)
				if ePos then
					if not CollisionE:GetMinionCollision(myHero, ePos) then
						CastSpell(_E, ePos.x, ePos.z)
						lastQ, lastW, lastE = false, false, true
						return true
					end
				end
			else
				if vPredictionExists then
					local CastPosition, HitChance, Pos = vPred:GetLineCastPosition(enemy, eDelay, eWidth, eRange, eSpeed, myHero, true)
					if HitChance >= 2 then
						CastSpell(_E, CastPosition.x, CastPosition.z)
					end
				end
			end
		else
			local ePred = TargetPrediction(eRange, eSpeed, eDelay, eWidth)
            local ePrediction = ePred:GetPrediction(enemy)
            if ePrediction and not willHitMinion(ePrediction, eWidth) then
				CastSpell(_E, ePrediction.x, ePrediction.z)
				lastQ, lastW, lastE = false, false, true
				return true
			end
		end
	end
	return false
end

-- Dynamic R Casting --
function CastR(enemy)
	if myHero:GetSpellData(_R).name == "leblancslidereturnm" then
		return
	end
	if ValidTarget(enemy) then
		if lastQ then
			if VIP_USER then
				Packet("S_CAST", {spellId = _R, targetNetworkId = enemy.networkID}):send()
				return true
			else
				CastSpell(_R, enemy)
				return true
			end
		elseif lastW then
			if VIP_USER then
				if LeblancMenu.predType == 1 then
					local rwPos = ProdictW:GetPrediction(enemy)
					if rwPos and not IsWall(D3DXVECTOR3(rwPos.x, rwPos.y, rwPos.z)) then
						CastSpell(_R, rwPos.x, rwPos.z)
						return true
					end
				else
					if vPredictionExists then
						local CastPosition,  HitChance,  Position = vPred:GetCircularCastPosition(enemy, wDelay, wWidth, wRange)
						if HitChance >= 2 and not IsWall(D3DXVECTOR3(CastPosition.x, CastPosition.y, CastPosition.z)) then 
							CastSpell(_R, CastPosition.x, CastPosition.z)
						end
					end
				end
			else
				local wrPred = TargetPrediction(wRange, wSpeed, wDelay, wWidth)
            	local wrPrediction = wrPred:GetPrediction(enemy)
            	if wrPrediction then
					CastSpell(_W, wrPrediction.x, wrPrediction.z)
					return true
				end
			end
		elseif lastE then
			if VIP_USER then
				if LeblancMenu.predType == 1 then
					local erPos = ProdictE:GetPrediction(Target)
					local CollisionER =  Collision(eRange, eSpeed, eDelay, eWidth)
					if erPos then
						if not CollisionER:GetMinionCollision(myHero, ePos) then
							CastSpell(_E, erPos.x, erPos.z)
							return true
						end
					end
				else
					if vPredictionExists then
						local CastPosition, HitChance, Pos = vPred:GetLineCastPosition(enemy, eDelay, eWidth, eRange, eSpeed, myHero, true)
						if HitChance >= 2 then
							CastSpell(_R, CastPosition.x, CastPosition.z)
						end
					end
				end
			else
				local erPred = TargetPrediction(eRange, eSpeed, eDelay, eWidth)
            	local erPrediction = erPred:GetPrediction(enemy)
            	if erPrediction and not willHitMinion(erPrediction, eWidth) then
					CastSpell(_E, erPrediction.x, erPrediction.z)
					return true
				end
			end
		end
	end
	return false
end

--[[function CloneLogic()
	if leblancImage and leblancImage.valid then
		if LeblancMenu.clone.cloneLogic == 2 then
			if ValidTarget(Target) then
				Packet('S_MOVE', {type = 6, x = Target.x, y = Target.z, sourceNetworkId = leblancImage.networkID, targetNetworkId = leblancImage.networkID}):send()
				return true
			end
		else
			Packet('S_MOVE', {type = 6, x = myHero.x, y = myHero.z, sourceNetworkId = leblancImage.networkID, targetNetworkId = leblancImage.networkID}):send()
			return true
		end
	else
		return false
	end
end]]--

-- Use Items on Enemy --
function UseItems(enemy)
	if not enemy then
		enemy = Target
	end
	if ValidTarget(enemy) then
		if dfgReady and GetDistance(enemy) <= 600 then CastSpell(dfgSlot, enemy) end
		if hxgReady and GetDistance(enemy) <= 600 then CastSpell(hxgSlot, enemy) end
		if bwcReady and GetDistance(enemy) <= 450 then CastSpell(bwcSlot, enemy) end
		if brkReady and GetDistance(enemy) <= 450 then CastSpell(brkSlot, enemy) end
		if tmtReady and GetDistance(enemy) <= 185 then CastSpell(tmtSlot) end
		if hdrReady and GetDistance(enemy) <= 185 then CastSpell(hdrSlot) end
	end
end

-- KillSteal function --
function KillSteal()
	if ValidTarget(Target) then
		local comboMana = 0
		local wqRange = wRange + qRange
		local wwqRange = (wRange * 2) + qRange
		if GetDistance(Target) <= wwqRange and GetDistance(Target) > wqRange then
			if wReady and rReady and qReady then
				comboMana = qMana + wMana
				if Target.health <= qDmg and myMana >= comboMana then
					CastW(Target)
					if debugMode then PrintChat("338") end
				end
			end
		elseif GetDistance(Target) <= wqRange and GetDistance(Target) > qRange then
			if Target.health <= qDmg and wReady and qReady then
				if wUsed() then
					if rReady then
						 CastR(Target)
					end
				elseif not wUsed() then
					comboMana = qMana + wMana
					if myMana > comboMana then
						CastW(Target)
						if debugMode then PrintChat("348") end
					end
				end
			end
		elseif GetDistance(Target) <= qRange and Target.health <= qDmg then
			if qReady then
				comboMana = qMana
				if myMana > comboMana then
					CastQ(Target)
					if debugMode then PrintChat("358") end
				end
			end
		elseif GetDistance(Target) <= wRange and Target.health <= wDmg then
			if wReady then
				comboMana = wMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					if debugMode then PrintChat("366") end
				elseif wUsed() and rReady then
					CastR(Target)
					if debugMode then PrintChat("369") end
				end
			end
		elseif GetDistance(Target) <= eRange and Target.health <= eDmg then
			if eReady then
				comboMana = eMana
				if myMana > comboMana then
					CastE(Target)
					if debugMode then PrintChat("377") end
				end
			end
		elseif GetDistance(Target) <= wRange and Target.health <= (wDmg + qDmg) then
			if wReady and qReady then
				comboMana = qMana + wMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					if debugMode then PrintChat("385") end
				end
			end
		elseif GetDistance(Target) <= eRange and Target.health <= (eDmg + qDmg) then
			if eReady and qReady then
				comboMana = qMana + wMana
				if myMana > comboMana then
					CastE(Target)
					if debugMode then PrintChat("393") end
				end
			end
		elseif GetDistance(Target) <= wRange and Target.health <= (wDmg + eDmg) then
			if wReady and eReady then
				comboMana = wMana + eMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					if debugMode then PrintChat("401") end
				end
			end
		elseif GetDistance(Target) <= wqRange and Target.health <= (qDmg + eDmg) then
			if wReady and qReady and eReady then
				comboMana = wMana + eMana + qMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					CastQ(Target)
					if debugMode then PrintChat("410") end
				end
			end
		elseif GetDistance(Target) <= qRange and Target.health <= qrDmg then
			if lastQ and rReady then
				CastR(Target)
				if debugMode then PrintChat("467") end
			end
		elseif GetDistance(Target) <= wRange and Target.health <= wrDmg then
			if lastW and rReady then
					CastR(Target)
					if debugMode then PrintChat("472") end
			end
		elseif GetDistance(Target) <= wqRange and GetDistance(Target) > qRange and Target.health < (qrDmg + qDmg) then
			if wReady and qReady and rReady then
				comboMana = wMana + qMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					CastQ(Target)
					if debugMode then PrintChat("420") end
				end
			end
		elseif GetDistance(Target) < qRange and Target.health < (qpDmg + qrDmg + eDmg) then
			if rReady and eReady and lastQ then
				comboMana = eMana
				if myMana > comboMana then
					CastR(Target)
					if debugMode then PrintChat("488") end
				end
			end
		elseif GetDistance(Target) < qRange and Target.health < (qDmg + qpDmg + qrDmg + eDmg) then
			if qReady and rReady and eReady then
				comboMana = qMana + eMana
				if myMana > comboMana then
					CastQ(Target)
					if debugMode then PrintChat("496") end
				end
			end
		elseif GetDistance(Target) < wRange and Target.health < (wDmg + qpDmg + qDmg + qrDmg + eDmg) then
			if qReady and wReady and eReady and rReady then
				comboMana = wMana + qMana + eMana
				if not wUsed() and myMana > comboMana then
					CastW(Target)
					if debugMode then PrintChat("505") end
				end
			end
		elseif GetDistance(Target) < wRange and Target.health < (wDmg + qpDmg + qDmg + qrDmg + eDmg + itemsDmg) then
			if qReady and wReady and eReady and rReady then
				comboMana = wMana + qMana + eMana
				if not wUsed() and myMana > comboMana then
					UseItems(Target)
					if debugMode then PrintChat("513") end
				end
			end
		end
	end
end

-- Auto Ignite --
function AutoIgnite()
	if ValidTarget(Target) then
		if Target.health <= iDmg and GetDistance(Target) <= 600 then
			if qReady and Target.health <= qDmg then
				CastQ(Target)
			elseif wReady and Target.health <= wDmg then
				if not wUsed() then 
					CastW(Target)
				end
			else
				if iReady then
					CastSpell(ignite, Target)
				end
			end
		end
	end
end

-- Using our consumables --
function UseConsumables()
	if not Recalling and ValidTarget(Target) then
		if LeblancMenu.misc.aHP and myHero.health < (myHero.maxHealth * (LeblancMenu.misc.HPHealth / 100))
			and not (usingHPot or usingFlask) and (hpReady or fskReady)	then
				CastSpell((hpSlot or fskSlot)) 
		end
		if LeblancMenu.misc.aMP and myHero.mana < (myHero.maxMana * (LeblancMenu.farming.qFarmMana / 100))
			and not (usingMPot or usingFlask) and (mpReady or fskReady) then
				CastSpell((mpSlot or fskSlot))
		end
	end
end	
--//function OnSendPacket(p)--
	--if p.header == 113 then--
		--dwArg1 = p.dwArg1--
		--dwArg2 = p.dwArg2--
		--sourceNetworkId = p:DecodeF()--
		--print("Source: "..sourceNetworkId)--
		--//type = p:Decode1()--
		--//x = p:DecodeF()--
		--//y = p:DecodeF()--
		--//targetNetworkId = p:DecodeF()--
		--//print("Target: "..targetNetworkId)--
		--//waypointCount = p:Decode1() / 2--
		--//unitNetworkId = p:DecodeF()--
		--//print("Unit: "..unitNetworkId)--
	--/end--
--end--

-- Damage Calculations --
function DamageCalculation()
	for i=1, heroManager.iCount do
	local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			myMana = (myHero.mana)
			qMana = myHero:GetSpellData(_Q).mana
			wMana = myHero:GetSpellData(_W).mana
			eMana = myHero:GetSpellData(_E).mana
			rMana = myHero:GetSpellData(_R).mana
			qrDmg = getDmg("R", enemy, myHero)
			wrDmg = getDmg("R", enemy, myHero, 2)
			erDmg = getDmg("R", enemy, myHero, 3)
			qpDmg = getDmg("Q", enemy, myHero, 2)
			if qReady then qDmg = getDmg("Q", enemy, myHero) end
            if wReady then wDmg = getDmg("W", enemy, myHero) end
			if eReady then eDmg = getDmg("E", enemy, myHero) end
			if dfgReady then dfgDmg = (dfgSlot and getDmg("DFG", enemy, myHero) or 0) end
            if hxgReady then hxgDmg = (hxgSlot and getDmg("HXG", enemy, myHero) or 0) end
            if bwcReady then bwcDmg = (bwcSlot and getDmg("BWC", enemy, myHero) or 0) end
            if iReady then iDmg = (ignite and getDmg("IGNITE", enemy, myHero) or 0) end
            onspellDmg = (liandrysSlot and getDmg("LIANDRYS", enemy, myHero) or 0)+(blackfireSlot and getDmg("BLACKFIRE",enemy,myHero) or 0)
            itemsDmg = dfgDmg + hxgDmg + bwcDmg + iDmg + onspellDmg

            -- Calculations for drawing text --
            if enemy.health > (qDmg + qpDmg + wDmg + qrDmg + eDmg + itemsDmg) then
				KillText[i] = 1
				colorText = ARGB(255,0,0,255)
			elseif enemy.health <= qDmg and qReady then
				if myMana > qMana then
					KillText[i] = 2
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (qDmg + wDmg) and qReady and wReady then
				if myMana > (qMana + wMana) and enemy.health > qDmg then
					KillText[i] = 3
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (qDmg + wDmg + qpDmg) and qReady and wReady then 
				if myMana > (qMana + wMana) and enemy.health > (qDmg + wDmg) then
					KillText[i] = 4
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (qDmg + wDmg + eDmg + qpDmg) and qReady and wReady and eReady then
				if myMana > (qMana + eMana + wMana) and enemy.health > (qDmg + wDmg + eDmg) then
					KillText[i] = 5
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (qDmg + qpDmg + wDmg + qrDmg + eDmg + itemsDmg) then
				if myMana > (qMana + eMana + wMana) and enemy.health > (qDmg + wDmg + eDmg + qpDmg) then
					KillText[i] = 6
					colorText = ARGB(255,255,0,0)
				end
			else
				KillText[i] = 7
			end
		end
	end
end

--Smart W --
function smartW()
	if wUsed() and leblancW and leblancW.valid then
		if CountEnemyHeroInRange(600, leblancW) < CountEnemyHeroInRange(600, myHero) then
			if ValidTarget(Target) then
				if Target.health > (qDmg + qpDmg + wDmg + qrDmg + eDmg + itemsDmg + 500) then
					CastSpell(_W)
				end
			end
		elseif comboFinished and lasttarget ~= nil and targetcount+3000 < os.clock() then
			if lasttarget.dead then
				CastSpell(_W)
				--PrintChat("W Back Target Dead")
			end
		end
	end
end

-- Object Handling Functions --
function OnCreateObj(obj)
	if obj ~= nil then
		if obj.name:find("LeblancChaosOrb") or obj.name:find("LeblancChaosOrbM") then
			if ValidTarget(Target) and GetDistance(obj, Target) <= 70 then
				qPassive = true
			end
		end
		if obj.name:find("leBlanc_displacement_cas.troy") then
			leblancW = obj
		end
		if obj.name:find("LeblancImage.troy") then
			leblancImage = obj
		end
		if obj.name:find("Global_Item_HealthPotion.troy") then
			if GetDistance(obj, myHero) <= 70 then
				usingHPot = true
				usingFlask = true
			end
		end
		if obj.name:find("Global_Item_ManaPotion.troy") then
			if GetDistance(obj, myHero) <= 70 then
				usingFlask = true
				usingMPot = true
			end
		end
		if obj.name:find("TeleportHome.troy") then
			if GetDistance(obj) <= 70 then
				Recalling = true
			end
		end
		if FocusJungleNames[obj.name] then
			table.insert(JungleFocusMobs, obj)
		elseif JungleMobNames[obj.name] then
            table.insert(JungleMobs, obj)
		end
	end
	--[[if LeblancMenu.evade.evadeSkills then
		if obj.team ~= myHero.team and obj.networkID ~= myHero.networkID then
			for i, skillShotChampion in pairs(champions) do
				for i, skillshot in pairs(skillShotChampion.skillshots) do
					if skillshot.projectileName == obj.name then
						for i, detectedSkillshot in ipairs(DetectedSkillshots) do
							if detectedSkillshot.skillshot.projectileName == skillshot.projectileName then
								return
							end
						end
	
						startPosition = Point(obj.x, obj.z)
						if skillshot.type == "line" then
							skillshotToAdd = {obj = obj, startPosition = startPosition, startTick = GetTickCount(), endTick = GetTickCount() + skillshot.range / skillshot.projectileSpeed * 1000, skillshot = skillshot}
						else
							endPosition = startPosition
							endTick = GetTickCount() + skillshot.spellDelay + skillshot.projectileSpeed
							table.insert(DetectedSkillshots, {startPosition = startPosition, endPosition = endPosition, skillshot = skillshot, endTick = endTick})
						end
						return
					end
				end
			end
		end
	end]]--
end

function OnDeleteObj(obj)
	if obj ~= nil then
		if obj.name:find("LeblancChaosOrb") or obj.name:find("LeblancChaosOrbM") then
			qPassive = false
		end
		if obj.name:find("Global_Item_HealthPotion.troy") then
			if GetDistance(obj) <= 70 then
				usingHPot = false
				usingFlask = false
			end
		end
		if obj.name:find("Global_Item_ManaPotion.troy") then
			if GetDistance(obj) <= 70 then
				usingMPot = false
				usingFlask = false
			end
		end
		if obj.name:find("TeleportHome.troy") then
			if GetDistance(obj) <= 70 then
				Recalling = false
			end
		end
		for i, Mob in pairs(JungleMobs) do
			if obj.name == Mob.name then
				table.remove(JungleMobs, i)
			end
		end
		for i, Mob in pairs(JungleFocusMobs) do
			if obj.name == Mob.name then
				table.remove(JungleFocusMobs, i)
			end
		end
	end
end

-- Recalling Functions --
function OnRecall(hero)
	if hero.networkID == player.networkID then
		Recalling = true
	end
end

function OnAbortRecall(hero)
	if hero.networkID == player.networkID then
		Recalling = false
	end
end

function OnFinishRecall(hero)
	if hero.networkID == player.networkID then
		Recalling = false
	end
end

function OnGainBuff(Unit, buff)
	if Unit == Target and buff.name == "LeblancChaosOrb" or "LeblancChaosOrbM" then
		qPassive = true
	end
end

function OnLoseBuff(Unit, buff)
	if Unit == Target and buff.name == "LeblancChaosOrb" or "LeblancChaosOrbM" then
		qPassive = false
	end
end

-- Function OnDraw --
function OnDraw()
	--> Ranges
	if leblancImage and leblancImage.valid then
		DrawCircle(leblancImage.x, leblancImage.y, leblancImage.z, 100, 0xFFFF20)
	end
	if not LeblancMenu.drawing.mDraw and not myHero.dead then
		if qReady and LeblancMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0xFFFF00)
		end
		if wReady and LeblancMenu.drawing.wDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFF00)
		end
	end
	if LeblancMenu.drawing.cDraw then
		for i = 1, heroManager.iCount do
        	local Unit = heroManager:GetHero(i)
        	if ValidTarget(Unit) then
        		local barPos = WorldToScreen(D3DXVECTOR3(Unit.x, Unit.y, Unit.z)) --(Credit to Zikkah)
				local PosX = barPos.x - 35
				local PosY = barPos.y - 10        
        	 	DrawText(TextList[KillText[i]], 13, PosX, PosY, colorText)
			end
		end
    end
end

-- regular minion mec taken from Sida's Auto Carry --
function willHitMinion(predic, width)
        for _, minion in pairs(enemyMinions.objects) do
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

--Based on Manciuzz Orbwalker http://pastebin.com/jufCeE0e
function OrbWalking(Target)
	if TimeToAttack() and GetDistance(Target) <= myHero.range + GetDistance(myHero.minBBox) then
		myHero:Attack(Target)
    elseif heroCanMove() then
        moveToCursor()
    end
end

function TimeToAttack()
    return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end

function moveToCursor()
	if GetDistance(mousePos) then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end

function OnProcessSpell(object, spell)
	if object == myHero then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
        end
        if spell.name == "LeblancChaosOrb" then
        	lastQ, lastW, lastE = true, false, false
        elseif spell.name == "LeblancSlide" then
        	lastQ, lastW, lastE = false, true, false
        elseif spell.name == "LeblancSoulShackle" then
        	lastQ, lastW, lastE = false, false, true
        end
    end
   --[[if LeblancMenu.evade.evadeSkills then
		if object.team ~= myHero.team and object.networkID ~= myHero.networkID and not myHero.dead and string.find(spell.name, "Basic") == nil then
			for i, skillShotChampion in pairs(champions) do
				if skillShotChampion.charName == object.charName then
					for i, skillshot in pairs(skillShotChampion.skillshots) do
						if skillshot.spellName == spell.name then
							startPosition = Point(object.x, object.z)
							endPosition = Point(spell.endPos.x, spell.endPos.z)
							if skillshot.type == "line" then
								endTick = GetTickCount() + skillshot.spellDelay + skillshot.range / skillshot.projectileSpeed * 1000
								endPosition = GetExtendedEndPos(startPosition, endPosition, skillshot.range)
							else
								endTick = GetTickCount() + skillshot.spellDelay + skillshot.projectileSpeed
							end
							table.insert(DetectedSkillshots, {startPosition = startPosition, endPosition = endPosition, skillshot = skillshot, endTick = endTick})
						end
					end
				end
			end
		end
	end]]--
end

function OnAnimation(unit, animationName)
    if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

-- Honda's Selector ---
function GetBestTarget(range)
	local LessToKill = 100
	local LessToKilli = 0
	local target = nil
	
	--	LESS_CAST	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy, Range) then
			if focusedtarget ~= nil and enemy.charName == focusedtarget.charName then return enemy end
			DamageToHero = myHero:CalcMagicDamage(enemy, 2000)
			ToKill = enemy.health / DamageToHero
			if ((ToKill < LessToKill) and not LeblancMenu.combo.Targets["DontUseCombo"..enemy.charName]) or (LessToKilli == 0) then
				if LeblancMenu.combo.Targets["DontUseCombo"..enemy.charName] then
					LessToKill = ToKill
				else
					LessToKill = 10
				end
				LessToKilli = i
			end
		end
	end
	
	if LessToKilli ~= 0 then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if i == LessToKilli then
				target = enemy
			end
		end
	end
	return target
end

function OnWndMsg(Msg, Key)
	if Msg == WM_LBUTTONDOWN then
		local minD = 0
		local starget = nil
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				if GetDistance(enemy, mousePos) <= minD or starget == nil then
					minD = GetDistance(enemy, mousePos)
					starget = enemy
				end
			end
		end
		if starget and minD < 500 then
			if focusedtarget and starget.charName == focusedtarget.charName then
				focusedtarget = nil
			else
				focusedtarget = starget
				print("<font color=\"#FF0000\">Leblanc: New target selected: "..starget.charName.."</font>")
			end
		end
	end
end

-- Spells/Items Checks --
function Checks()
	-- Updates Targets --
	if LeblancMenu.tsType == 1 then
		Target = GetBestTarget(2000)
	else
		TargetSelector:update()
		Target = TargetSelector.target
	end

	
	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	end
	
	-- Slots for Items / Pots / Wards --
	rstSlot, ssSlot, swSlot, vwSlot =    GetInventorySlotItem(2045),
									     GetInventorySlotItem(2049),
									     GetInventorySlotItem(2044),
									     GetInventorySlotItem(2043)
	dfgSlot, hxgSlot, bwcSlot, brkSlot = GetInventorySlotItem(3128),
										 GetInventorySlotItem(3146),
										 GetInventorySlotItem(3144),
										 GetInventorySlotItem(3153)
	hpSlot, mpSlot, fskSlot =            GetInventorySlotItem(2003),
							             GetInventorySlotItem(2004),
							             GetInventorySlotItem(2041)
	znaSlot, wgtSlot =                   GetInventorySlotItem(3157),
	                                     GetInventorySlotItem(3090)
	tmtSlot, hdrSlot = 					 GetInventorySlotItem(3077)
										 GetInventorySlotItem(3074)
	
	-- Spells --									 
	qReady = (myHero:CanUseSpell(_Q) == READY)
	wReady = (myHero:CanUseSpell(_W) == READY)
	eReady = (myHero:CanUseSpell(_E) == READY)
	rReady = (myHero:CanUseSpell(_R) == READY)
	iReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
	
	-- Items --
	dfgReady = (dfgSlot ~= nil and myHero:CanUseSpell(dfgSlot) == READY)
	hxgReady = (hxgSlot ~= nil and myHero:CanUseSpell(hxgSlot) == READY)
	bwcReady = (bwcSlot ~= nil and myHero:CanUseSpell(bwcSlot) == READY)
	brkReady = (brkSlot ~= nil and myHero:CanUseSpell(brkSlot) == READY)
	znaReady = (znaSlot ~= nil and myHero:CanUseSpell(znaSlot) == READY)
	wgtReady = (wgtSlot ~= nil and myHero:CanUseSpell(wgtSlot) == READY)
	tmtReady = (tmtSlot ~= nil and myHero:CanUseSpell(tmtSlot) == READY)
	hdrReady = (hdrSlot ~= nil and myHero:CanUseSpell(hdrSlot) == READY)
	
	-- Pots --
	hpReady = (hpSlot ~= nil and myHero:CanUseSpell(hpSlot) == READY)
	mpReady =(mpSlot ~= nil and myHero:CanUseSpell(mpSlot) == READY)
	fskReady = (fskSlot ~= nil and myHero:CanUseSpell(fskSlot) == READY)
	
	-- Updates Minions --
	enemyMinions:update()
end