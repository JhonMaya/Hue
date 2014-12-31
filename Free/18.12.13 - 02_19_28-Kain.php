<?php exit() ?>--by Kain 97.90.203.108
--[[ Leblanc - The Deceiver by Skeem & Kain]]--

-- Name Check --  
if myHero.charName ~= "Leblanc" then return end

curVersion = "1.0"

if VIP_USER then
	require "Prodiction"
	require "Collision"
end 

-- Loading Function --
function OnLoad()
	Variables()
	--evadeLoad()
	LeblancMenu()
	PrintChat("<font color='#FFFF00'> >> Leblanc - The Deceiver 1.0 Loaded!! <<</font>")
end

-- Tick Function --
function OnTick()
	tick = GetTickCount()

	if (webTrialVersion ~= nil and scriptTrialVersion ~= webTrialVersion) or webTrialVersion == nil then
		if webTrialVersion ~= nil then
			print("<font color='#c22e13'>This version of the script has been disabled, go to forums for update</font>")
		end

		return
	end

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
--	if LeblancMenu.combo.smartW then smartW() end
	if LeblancMenu.ks.killSteal then KillSteal() end
	if LeblancMenu.ks.autoIgnite then AutoIgnite() end
	if FarmingKey and not (ComboKey or HarassKey) then FarmMinions() end
end

function Variables()
	qRange, wRange, eRange = 700, 700, 1000
	qName, wName, eName, rName = "Sigil of Silence", "Distortion", "Ethereal Chains", "Mimic"
	qReady, wReady, eReady, rReady = false, false, false, false
	wSpeed, wDelay, wWidth = 2000, .25, 100
	eSpeed, eDelay, eWidth = 1600, .25, 95
	wPos, ePos = nil, nil
	lastQ, lastW, lastE = false, false, false
	leblancW = nil
	if VIP_USER then
		Prodict = ProdictManager.GetInstance()
		ProdictW = Prodict:AddProdictionObject(_W, wRange, wSpeed, wDelay, wWidth, myHero)
		ProdictE = Prodict:AddProdictionObject(_E, eRange, eSpeed, eDelay, eWidth, myHero)
	end
	hpReady, mpReady, fskReady, Recalling = false, false, false, false
	TextList = {"Harass him!!", "Q KILL!!", "Q + W Kill!", "Q+W+QP Kill!", "Q+W+E+QP Kill!", "Full Combo Kill!", "Need Mana or CD!"}
	KillText = {}
	waittxt = {} -- prevents UI lags, all credits to Dekaron
	usingHPot, usingMPot = false, false
	for i=1, heroManager.iCount do waittxt[i] = i*3 end
	enemyMinions = minionManager(MINION_ENEMY, qRange, player, MINION_SORT_HEALTH_ASC)
	lastAnimation = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
	JungleMobs = {}
	JungleFocusMobs = {}
	comboFinished = false
	debugMode = false

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
		LeblancMenu.combo:permaShow("comboKey") 
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Harass Settings]", "harass")
		LeblancMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		LeblancMenu.harass:addParam("harassType", "Harass Type", SCRIPT_PARAM_SLICE, 2, 1, 2,0)
		LeblancMenu.harass:addParam("waitQ", "Wait for "..qName.." (Q) CD", SCRIPT_PARAM_ONOFF, true)
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

	LeblancMenu:addSubMenu("["..myHero.charName.." - Evade Settings]", "evade")
		LeblancMenu.evade:addParam("evadeSkills", "Dodge Skills with  "..wName.." (W)", SCRIPT_PARAM_ONOFF, false)

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
		
	TargetSelector = TargetSelector(TARGET_LOW_HP, 2000,DAMAGE_MAGIC)
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
		if Target ~= nil then
			OrbWalking(Target)
		else
			moveToCursor()
		end
	end
	if ValidTarget(Target) then
		if (LeblancMenu.harass.harassType == 1) then
			if player.level < 2 then
				if qReady and GetDistance(Target) <= qRange then
					CastQ(Target)
				end
			else
				local wqRange = (wRange + qRange)
				if qReady and wReady and GetDistance(Target) <= wqRange and not wUsed() then 
					CastW(Target)
				end
				if qReady and GetDistance(Target) <= qRange and wUsed() then
					CastQ(Target)
				end			
				if wUsed() and not qReady then
					if LeblancMenu.harass.secW then
						CastSpell(_W)
					end
				end
			end
		elseif (LeblancMenu.harass.harassType == 2) then
			if player.level < 2 then
				if qReady and GetDistance(Target) <= qRange then
					CastQ(Target)
				end
			else
				if qReady and wReady and GetDistance(Target) <= wRange then
					CastQ(Target)
					CastW(Target)
				end
				if wUsed() and not qReady then
					if LeblancMenu.harass.secW then
						CastSpell(_W)
					end
				end
			end
		end
		if not LeblancMenu.harass.waitQ then
			CastQ(Target)
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
		enemy = Target
	end
	if ValidTarget(enemy) then 
		Packet("S_CAST", {spellId = _Q, targetNetworkId = enemy.networkID}):send()
		lastQ, lastW, lastE = true, false, false
	end
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
		enemy = Target 
	end
	if ValidTarget(enemy) and not wUsed() then
		if VIP_USER then
			if wPos ~= nil then
				CastSpell(_W, wPos.x, wPos.z)
				lastQ, lastW, lastE = false, true, false
			end
		else
			CastSpell(_W, enemy.x, enemy.z)
			lastQ, lastW, lastE = false, true, false
		end
	end
end

-- Casting E --
function CastE(enemy)
	if not enemy then
		enemy = Target
	end
	if ValidTarget(enemy) then
		if VIP_USER then
			if ePos ~= nil then
				if not CollisionE:GetMinionCollision(ePos, myHero) then
					CastSpell(_E, ePos.x, ePos.z)
					lastQ, lastW, lastE = false, false, true
				end
			end
		else
			CastSpell(_E, enemy.x, enemy.z)
			lastQ, lastW, lastE = false, false, true
		end
	end
end

-- Dynamic R Casting --
function CastR(enemy)
	if not enemy then
		enemy = Target
	end
	if ValidTarget(enemy) then
		if lastQ then
			Packet("S_CAST", {spellId = _R, targetNetworkId = enemy.networkID}):send()
		elseif lastW then
			CastSpell(_R, wPos.x, wPos.z)
		elseif lastE then
			if not CollisionE:GetMinionCollision(ePos, myHero) then
				CastSpell(_R, ePos.x, ePos.z)
			end
		end
	end
end

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
	if not Recalling and Target ~= nil then
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

-- Damage Calculations --
function DamageCalculation()
	for i=1, heroManager.iCount do
	local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			dfgDmg, hxgDmg, bwcDmg, iDmg  = 0, 0, 0, 0
			qDmg, qpDmg, wDmg, eDmg = 0, 0, 0, 0
			qrDmg, wrDmg, erDmg = 0, 0, 0
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
			elseif enemy.health <= qDmg and qReady then
				if myMana > qMana then
					KillText[i] = 2
				end
			elseif enemy.health <= (qDmg + wDmg) and qReady and wReady then
				if myMana > (qMana + wMana) and enemy.health > qDmg then
					KillText[i] = 3
				end
			elseif enemy.health <= (qDmg + wDmg + qpDmg) and qReady and wReady then 
				if myMana > (qMana + wMana) and enemy.health > (qDmg + wDmg) then
					KillText[i] = 4
				end
			elseif enemy.health <= (qDmg + wDmg + eDmg + qpDmg) and qReady and wReady and eReady then
				if myMana > (qMana + eMana + wMana) and enemy.health > (qDmg + wDmg + eDmg) then
					KillText[i] = 5
				end
			elseif enemy.health <= (qDmg + qpDmg + wDmg + qrDmg + eDmg + itemsDmg) then
				if myMana > (qMana + eMana + wMana) and enemy.health > (qDmg + wDmg + eDmg + qpDmg) then
					KillText[i] = 6
				end
			else
				KillText[i] = 7
			end
		end
	end
																																														end

-- Count enemies near obj --
function countEnemiesNearObj(obj, range)
	local enemyInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local hero = heroManager:getHero(i)
        if ValidTargetNear(obj, range, hero) then
            enemyInRange = enemyInRange + 1
        end
    end
    return enemyInRange
end

--Smart W --
function smartW()
	if wUsed() then
		--[[if countEnemiesNearObj(leblancW, 600) < CountEnemyHeroInRange(600) and comboFinished then
			CastSpell(_W)
			("W Back Safety")]]--
		if comboFinished and (Target ~= nil and Target.dead or not Target) then
			CastSpell(_W)
			PrintChat("W Back Target Dead")
		end
	end
end

-- Object Handling Functions --
function OnCreateObj(obj)
	if obj ~= nil then
		if obj.name:find("leBlanc_displacement_cas.troy") then
			leblancW = obj
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
	if LeblancMenu.evade.evadeSkills then
		for i, skillShotChampion in pairs(Champions) do
			for i, skillshot in pairs(skillShotChampion.skillshots) do
				if skillshot.projectileName == obj.name then
					for i, detectedSkillshot in ipairs(DetectedSkillshots) do
						if detectedSkillshot.skillshot.projectileName == skillshot.projectileName then
							return
						end
					end
	
					startPosition = Point(obj.x, obj.z)
					if skillshot.type == LINE then
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
end

function OnDeleteObj(obj)
	if obj ~= nil then
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

-- Function OnDraw --
function OnDraw()
	--> Ranges
	if leblancW ~= nil then DrawCircle(leblancW.x, leblancW.y, leblancW.z, wWidth, 0xFFFF00) end
	if not LeblancMenu.drawing.mDraw and not myHero.dead then
		if qReady and LeblancMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0xFFFF00)
		end
		if wReady and LeblancMenu.drawing.wDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFF00)
		end
	end
	if LeblancMenu.drawing.cDraw then
		for i=1, heroManager.iCount do
			local Unit = heroManager:GetHero(i)
			if ValidTarget(Unit) then
				if waittxt[i] == 1 and (KillText[i] ~= nil or 0 or 1) then
					PrintFloatText(Unit, 0, TextList[KillText[i]])
				end
			end
			if waittxt[i] == 1 then
				waittxt[i] = 30
			else
				waittxt[i] = waittxt[i]-1
			end
		end
	end
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
        	lasqQ, lastW, lastE = true, false, false
        elseif spell.name == "LeblancSlide" then
        	lasqQ, lastW, lastE = false, true, false
        elseif spell.name == "LeblancSoulShackle" then
        	lasqQ, lastW, lastE = false, false, true
        end
    end
  --[[ if LeblancMenu.evade.evadeSkills then
		if object.team ~= player.team and not player.dead and string.find(spell.name, "Basic") == nil then
			for i, skillShotChampion in pairs(Champions) do
				if skillShotChampion.charName == object.charName then
					for i, skillshot in pairs(skillShotChampion.skillshots) do
						if skillshot.spellName == spell.name then
							startPosition = Point(object.x, object.z)
							endPosition = Point(spell.endPos.x, spell.endPos.z)
							if skillshot.type == LINE then
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

-- Spells/Items Checks --
function Checks()
	-- Updates Targets --
	TargetSelector:update()
	Target = TargetSelector.target
	
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
	
	-- Updates Predictions --
	if VIP_USER then
		if ValidTarget(Target) then
			wPos = ProdictW:GetPrediction(Target)
			ePos = ProdictE:GetPrediction(Target)
			CollisionE =  Collision(eRange, eSpeed, eDelay, eWidth)
		end
	end	
end

-- Leblanc Evade --
--[[Credits:
		List taken from Good Evade by Vadash based on Perfect Evade by Husky
]]--
function evadeLoad()
	LINE = 1
	CIRCULAR = 2
	evadingSkillShot = nil
	evadingPosition = nil
	DetectedSkillshots = {}
	skillshotToAdd = nil
	champions = {
    ["Lux"] = {charName = "Lux", skillshots = {
        ["Light Binding"] =  {name = "Light Binding", spellName = "LuxLightBinding", spellDelay = 250, projectileName = "LuxLightBinding_mis.troy", projectileSpeed = 1200, range = 1300, radius = 80, type = "line", cc = "true"},
                ["Final Spark"]    = {name = "Final Spark", spellName = "LuxMaliceCannonMis", spellDelay = 555, projectileName = "LuxMaliceCannon_mis.troy", projectileSpeed = 3000, range = 3285, radius = 55, type = "line", ccType = "soft"},
        ["Lucent Singularity"]    = {name = "Lucent Singularity", spellName = "LuxLightStrikeKugel", spellDelay = 250, projectileName = "LuxLightstrike_mis.troy", projectileSpeed = 1325, range = 1020, radius = 300, type = "circular", ccType = "soft"}
    }},
    ["Nidalee"] = {charName = "Nidalee", skillshots = {
        ["Javelin Toss"] = {name = "Javelin Toss", spellName = "JavelinToss", spellDelay = 250, projectileName = "nidalee_javelinToss_mis.troy", projectileSpeed = 1300, range = 1500, radius = 60, type = "line", cc = "true"}
    }},
    ["Kennen"] = {charName = "Kennen", skillshots = {
        ["Thundering Shuriken"] = {name = "Thundering Shuriken", spellName = "KennenShurikenHurlMissile1", spellDelay = 180, projectileName = "kennen_ts_mis.troy", projectileSpeed = 1640, range = 1050, radius = 53, type = "line", cc = "false"}
    }},
    ["Amumu"] = {charName = "Amumu", skillshots = {
        ["Bandage Toss"] = {name = "Bandage Toss", spellName = "BandageToss", spellDelay = 250, projectileName = "Bandage_beam.troy", projectileSpeed = 2000, range = 1100, radius = 80, type = "line", cc = "true"}
    }},
    ["Lee Sin"] = {charName = "LeeSin", skillshots = {
        ["Sonic Wave"] = {name = "Sonic Wave", spellName = "BlindMonkQOne", spellDelay = 250, projectileName = "blindMonk_Q_mis_01.troy", projectileSpeed = 1800, range = 1100, radius = 60, type = "line", cc = "false"}
    }},
    ["Morgana"] = {charName = "Morgana", skillshots = {
        ["Dark Binding"] = {name = "Dark Binding", spellName = "DarkBindingMissile", spellDelay = 250, projectileName = "DarkBinding_mis.troy", projectileSpeed = 1200, range = 1300, radius = 70, type = "line", cc = "true"}
    }},
    ["Ezreal"] = {charName = "Ezreal", skillshots = {
        ["Mystic Shot"]             = {name = "Mystic Shot",      spellName = "EzrealMysticShotMissile",      spellDelay = 250,  projectileName = "Ezreal_mysticshot_mis.troy",  projectileSpeed = 1975, range = 1200,  radius = 80,  type = "line", cc = "false"},
        --["Essence Flux"]            = {name = "Essence Flux",     spellName = "EzrealEssenceFluxMissile",     spellDelay = 250,  projectileName = "Ezreal_essenceflux_mis.troy", projectileSpeed = 1510, range = 1050,  radius = 60,  type = "line", cc = "false"},        
        ["Trueshot Barrage"]        = {name = "Trueshot Barrage", spellName = "EzrealTrueshotBarrage",        spellDelay = 1000, projectileName = "Ezreal_TrueShot_mis.troy",    projectileSpeed = 1990, range = 20000, radius = 250, type = "line", cc = "false"},
        ["Mystic Shot (Pulsefire)"] = {name = "Mystic Shot",      spellName = "EzrealMysticShotPulseMissile", spellDelay = 250,  projectileName = "Ezreal_mysticshot_mis.troy",  projectileSpeed = 1975, range = 1200,  radius = 80,  type = "line", cc = "false"}
    }},
    ["Ahri"] = {charName = "Ahri", skillshots = {
        ["Orb of Deception"] = {name = "Orb of Deception", spellName = "AhriOrbofDeception", spellDelay = 250, projectileName = "Ahri_Orb_mis.troy",   projectileSpeed = 1660, range = 1000, radius = 50, type = "line", cc = "false"},
        ["Charm"]            = {name = "Charm",            spellName = "AhriSeduce",         spellDelay = 250, projectileName = "Ahri_Charm_mis.troy", projectileSpeed = 1535, range = 1000, radius = 50, type = "line", cc = "true"}
    }},
    ["Leona"] = {charName = "Leona", skillshots = {
        ["Zenith Blade"] = {name = "LeonaZenithBlade", spellName = "LeonaZenithBlade", spellDelay = 250, projectileName = "Leona_ZenithBlade_mis.troy", projectileSpeed = 2000, range = 900, radius = 90, type = "line", cc = "true"},
        ["Solar Flare"] = {name = "SolarFlare", spellName = "LeonaSolarFlare", spellDelay = 250, projectileName = "TEST", projectileSpeed = 1000, range = 1200, radius = 250, type = "circular", cc = "true"}
    }},
    ["Chogath"] = {charName = "Chogath", skillshots = {
        ["Rupture"] = {name = "Rupture", spellName = "Rupture", spellDelay = 290, projectileName = "rupture_cas_01_red_team.troy", projectileSpeed = 1000, range = 950, radius = 190, type = "circular", cc = "true"}
    }},
    ["Blitzcrank"] = {charName = "Blitzcrank", skillshots = {
        ["RocketGrab"] = {name = "RocketGrab", spellName = "RocketGrabMissile", spellDelay = 250, projectileName = "FistGrab_mis.troy", projectileSpeed = 1800, range = 1050, radius = 70, type = "line", cc = "true"}
    }},
    ["Anivia"] = {charName = "Anivia", skillshots = {
        ["Flash Frost"] = {name = "Flash Frost", spellName = "FlashFrostSpell", spellDelay = 250, projectileName = "Cryo_FlashFrost_mis.troy", projectileSpeed = 850, range = 1100, radius = 110, type = "line", cc = "true"}
    }},
    ["Zyra"] = {charName = "Zyra", skillshots = {
        ["Grasping Roots"] = {name = "Grasping Roots", spellName = "ZyraGraspingRoots", spellDelay = 250, projectileName = "Zyra_E_sequence_impact.troy", projectileSpeed = 1150, range = 1150, radius = 70,  type = "line", cc = "true"},
        ["Zyra Passive Death"] = {name = "Zyra Passive", spellName = "ZyraPassiveDeathMissile", spellDelay = 250, projectileName = "zyra_passive_plant_mis_fire.troy", projectileSpeed = 1900, range = 1474, radius = 70,  type = "line", cc = "false"},
    }},
    ["Nautilus"] = {charName = "Nautilus", skillshots = {
        ["Dredge Line"] = {name = "Dredge Line", spellName = "NautilusAnchorDragMissile", spellDelay = 250, projectileName = "Nautilus_Q_mis.troy", projectileSpeed = 1965, range = 1075, radius = 60, type = "line", cc = "true"}
    }},
    ["Caitlyn"] = {charName = "Caitlyn", skillshots = {
        ["Piltover Peacemaker"] = {name = "Piltover Peacemaker", spellName = "CaitlynPiltoverPeacemaker", spellDelay = 625, projectileName = "caitlyn_Q_mis.troy", projectileSpeed = 2150, range = 1300, radius = 60, type = "line", cc = "false"}
    }},
    ["Mundo"] = {charName = "DrMundo", skillshots = {
        ["Infected Cleaver"] = {name = "Infected Cleaver", spellName = "InfectedCleaverMissile", spellDelay = 250, projectileName = "dr_mundo_infected_cleaver_mis.troy", projectileSpeed = 1975, range = 1050, radius = 70, type = "line", cc = "false"}
    }},
    ["Brand"] = {charName = "Brand", skillshots = {
        ["Brand Missile"] = {name = "BrandBlazeMissile", spellName = "BrandBlazeMissile", spellDelay = 250, projectileName = "BrandBlaze_mis.troy", projectileSpeed = 1565, range = 1100, radius = 50, type = "line", cc = "false"},
    }},
    ["Corki"] = {charName = "Corki", skillshots = {
        ["Missile Barrage small"] = {name = "Missile Barrage small", spellName = "MissileBarrageMissile", spellDelay = 175, projectileName = "corki_MissleBarrage_mis.troy", projectileSpeed = 1950, range = 1250, radius = 50, type = "line", cc = "false"},
        ["Missile Barrage big"] = {name = "Missile Barrage big", spellName = "MissileBarrageMissile2", spellDelay = 175, projectileName = "corki_MissleBarrage_DD_mis.troy", projectileSpeed = 1950, range = 1250, radius = 50, type = "line", cc = "false"}
    }},
    ["Swain"] = {charName = "Swain", skillshots = {
        ["Nevermove"] = {name = "Nevermove", spellName = "SwainShadowGrasp", spellDelay = 250, projectileName = "swain_shadowGrasp_transform.troy", projectileSpeed = 1000, range = 900, radius = 180, type = "circular", cc = "true"}
    }},
    ["Ashe"] = {charName = "Ashe", skillshots = {
        ["EnchantedArrow"] = {name = "EnchantedArrow", spellName = "EnchantedCrystalArrow", spellDelay = 125, projectileName = "EnchantedCrystalArrow_mis.troy", projectileSpeed = 1600, range = 25000, radius = 150, type="line", cc = "true"}
    }},
    ["KogMaw"] = {charName = "KogMaw", skillshots = {
        ["Living Artillery"] = {name = "Living Artillery", spellName = "KogMawLivingArtillery", spellDelay = 250, projectileName = "KogMawLivingArtillery_cas_green.troy", projectileSpeed = 1050, range = 2200, radius = 180, type="circular", cc = "false"}
    }},
    ["KhaZix"] = {charName = "KhaZix", skillshots = {
        ["KhaZix W Missile"] = {name = "KhaZix W Enhanced", spellName = "KhaZixW", spellDelay = 250, projectileName = "Khazix_W_mis.troy", projectileSpeed = 1700, range = 1025, radius = 70, type="line", cc = "false"},
    }},
    ["Zed"] = {charName = "Zed", skillshots = {
        ["ZedShuriken"] = {name = "ZedShuriken", spellName = "ZedShuriken", spellDelay = 0, projectileName = "Zed_Q_Mis.troy", projectileSpeed = 1700, range = 925, radius = 50, type="line", cc = "false"}
    }},
    ["Leblanc"] = {charName = "Leblanc", skillshots = {
        ["Ethereal Chains"] = {name = "Ethereal Chains", spellName = "LeblancSoulShackle", spellDelay = 250, projectileName = "leBlanc_shackle_mis.troy", projectileSpeed = 1585, range = 960, radius = 50, type = "line", cc = "true"},
        ["Ethereal Chains R"] = {name = "Ethereal Chains R", spellName = "LeblancSoulShackleM", spellDelay = 250, projectileName = "leBlanc_shackle_mis_ult.troy", projectileSpeed = 1585, range = 960, radius = 50, type = "line", cc = "true"},
    }},
    ["Elise"] = {charName = "Elise", skillshots = {
        ["Cocoon"] = {name = "Cocoon", spellName = "EliseHumanE", spellDelay = 250, projectileName = "Elise_human_E_mis.troy", projectileSpeed = 1450, range = 1100, radius = 70, type="line", cc = "true"}
    }},
    ["Lulu"] = {charName = "Lulu", skillshots = {
        ["luluQ1"] = {name = "luluQ1", spellName = "LuluQMissile", spellDelay = 100, projectileName = "Lulu_Q_Mis.troy", projectileSpeed = 1450, range = 1000, radius = 50, type="line", cc = "false"},
        ["luluQ2"] = {name = "luluQ2", spellName = "LuluQ", spellDelay = 250, projectileName = "Lulu_Q_Mis.troy", projectileSpeed = 1375, range = 1000, radius = 50, type="line", cc = "false"}
    }},
    ["Thresh"] = {charName = "Thresh", skillshots = {
        ["ThreshQ"] = {name = "ThreshQ", spellName = "ThreshQ", spellDelay = 500, projectileName = "Thresh_Q_whip_beam.troy", projectileSpeed = 1900, range = 1100, radius = 70, type="line", cc = "true"}
    }},
    ["Shen"] = {charName = "Shen", skillshots = {
        ["ShadowDash"] = {name = "ShadowDash", spellName = "ShenShadowDash", spellDelay = 125, projectileName = "shen_shadowDash_mis.troy", projectileSpeed = 2000, range = 575, radius = 50, type="line", cc = "true"}
    }},
    ["Quinn"] = {charName = "Quinn", skillshots = {
        ["QuinnQ"] = {name = "QuinnQ", spellName = "QuinnQ", spellDelay = 100, projectileName = "Quinn_Q_missile.troy", projectileSpeed = 1550, range = 1050, radius = 80, type="line", cc = "true"}
    }},
    ["Nami"] = {charName = "Nami", skillshots = {
        ["NamiQ"] = {name = "NamiQ", spellName = "NamiQ", spellDelay = 700, projectileName = "Nami_Q_mis.troy", projectileSpeed = 800, range = 875, radius = 225, type="circular", cc = "true"},    
    }},
    ["Malphite"] = {charName = "Malphite", skillshots = {
        ["UFSlash"] = {name = "UFSlash", spellName = "UFSlash", spellDelay = 250, projectileName = "TEST", projectileSpeed = 1800, range = 1000, radius = 160, type="line", cc = "true"},    
    }},
    ["Sejuani"] = {charName = "Sejuani", skillshots = {
        ["SejuaniR"] = {name = "SejuaniR", spellName = "SejuaniGlacialPrisonCast", spellDelay = 250, projectileName = "Sejuani_R_mis.troy", projectileSpeed = 1600, range = 1200, radius = 110, type="line", cc = "true"},    
    }},
    ["Varus"] = {charName = "Varus", skillshots = {
        ["VarusR"] = {name = "VarusR", spellName = "VarusR", spellDelay = 250, projectileName = "VarusRMissile.troy", projectileSpeed = 2000, range = 1250, radius = 100, type="line", cc = "true"},
    }},
    ["Fizz"] = {charName = "Fizz", skillshots = {
        ["FizzR1"] = {name = "FizzR1", spellName = "FizzMarinerDoom", spellDelay = 250, projectileName = "Fizz_UltimateMissile.troy", projectileSpeed = 1375, range = 1300, radius = 80, type = "line", cc = "true"}, -- line part
    }},
    ["Karthus"] = {charName = "Karthus", skillshots = {
        ["Lay Waste"] = {name = "Lay Waste", spellName = "LayWaste", spellDelay = 390, projectileName = "LayWaste_point.troy", projectileSpeed = 500, range = 875, radius = 140, type = "circular", cc = "false"}
    }},
    ["Cassiopeia"] = {charName = "Cassiopeia", skillshots = {
        ["Noxious Blast"] = {name = "Noxious Blast", spellName = "CassiopeiaNoxiousBlast", spellDelay = 200, projectileName = "CassNoxiousSnakePlane_green.troy", projectileSpeed = 460, range = 850, radius = 150, type = "circular", cc = "false"},
    }},
        ["TwistedFate"] = {charName = "TwistedFate", skillshots = {
        ["Loaded Dice"] = {name = "Loaded Dice", spellName = "WildCards", spellDelay = 250, projectileName = "Roulette_mis.troy", projectileSpeed = 1000, range = 1450, radius = 40, type = "line", cc = "false"},             
    }},
    ["Sona"] = {charName = "Sona", skillshots = {
        ["Crescendo"] = {name = "Crescendo", spellName = "SonaCrescendo", spellDelay = 240, projectileName = "SonaCrescendo_mis.troy", projectileSpeed = 2400, range = 1000, radius = 160, type = "line", cc = "true"},        
    }},
        ["Gragas"] = {charName = "Gragas", skillshots = {
        ["Barrel Roll"] = {name = "Barrel Roll", spellName = "GragasBarrelRollMissile", spellDelay = 250, projectileName = "gragas_barrelroll_mis.troy", projectileSpeed = 1000, range = 1100, radius = 175, type = "circular", cc = "false"},
    }},
        ["Karma"] = {charName = "Karma", skillshots = {
        ["Inner Flame"] = {name = "Inner Flame", spellName = "KarmaQ", spellDelay = 218, projectileName = "TEMP_KarmaQMis.troy", projectileSpeed = 1575, range = 950, radius = 80, type = "line", cc = "false"},    
        ["Inner Flame"] = {name = "Inner Flame", spellName = "KarmaQ", spellDelay = 218, projectileName = "TEMP_KarmaQMMis.troy", projectileSpeed = 1575, range = 950, radius = 80, type = "line", cc = "false"}
    }},
}
end

-- Our Evade --
function evadeTick()
	if LeblancMenu.evade.evadeSkills then
		EvadeSkillShots()
		if skillshotToAdd then AddSkillShot(skillshotToAdd) end
		CleanSkillShots()
	end
end

function IsInEnemyTeam(charName)
	local is_in_enemy_team = false
	local hero
	local i = 1
	
	while i <= heroManager.iCount and not is_in_enemy_team do
		hero = heroManager:GetHero(i)
		if hero.team ~= player.team and hero.charName == charName then is_in_enemy_team = true end
		i = i + 1
	end
	
	return is_in_enemy_team
end

function CleanTable()
	for i, champion in pairs(Champions) do
		if not IsInEnemyTeam(champion.charName) then Champions[i] = nil end
	end
end

function GetExtendedEndPos(startPos, endPos, distance)
	direction = endPos - startPos
	direction:normalize()
	direction = direction * distance
	return startPos + direction
end

function inDangerousArea(skillshot, unit)
	playerPoint = Point(unit.x, unit.z)
	if skillshot and skillshot.skillshot.type == LINE then
		skillshotLine = Line(skillshot.startPosition, skillshot.endPosition)
		projection = playerPoint:perpendicularFoot(skillshotLine)
		return playerPoint:distance(projection) <= skillshot.skillshot.radius and skillshotLine:contains(projection)
	elseif skillshot and skillshot.skillshot.type == CIRCULAR then
		return playerPoint:distance(skillshot.endPosition) <= skillshot.skillshot.radius
	end
end

function GetLinealPosition(skillshot)
	playerPoint = Point(player.x, player.z)
	projection = playerPoint:perpendicularFoot(Line(skillshot.startPosition, skillshot.endPosition))
	return GetExtendedEndPos(projection, playerPoint, skillshot.skillshot.radius)
end

function GetAOEPosition(skillshot)
	playerPoint = Point(player.x, player.z)
	return GetExtendedEndPos(skillshot.endPosition, playerPoint, skillshot.skillshot.radius)
end

function CleanSkillShots()
	for i, detectedSkillshot in ipairs(DetectedSkillshots) do
		if detectedSkillshot.endTick < GetTickCount() then
			table.remove(DetectedSkillshots, i)
			i = i - 1
		end
	end
end

function AddSkillShot(skillshotToAdd)
	if not skillshotToAdd.obj.valid then
		skillshotToAdd = nil
		return
	end
	
	if GetTickCount() - skillshotToAdd.startTick >= GetLatency() then
		actualPosition = Point(skillshotToAdd.obj.x, skillshotToAdd.obj.z)
		endPosition = GetExtendedEndPos(skillshotToAdd.startPosition, actualPosition, skillshotToAdd.skillshot.range)
		table.insert(DetectedSkillshots, {startPosition = skillshotToAdd.startPosition, endPosition = endPosition, skillshot = skillshotToAdd.skillshot, endTick = skillshotToAdd.endTick})
		skillshotToAdd = nil
	end
end

function EvadeSkillShots()
	for i, skillshot in ipairs(DetectedSkillshots) do
		if skillshot and inDangerousArea(skillshot, player) then
			if not _G.evade and not _G.evading then
				if skillshot.skillshot.danger == true then
					if skillshot.skillshot.type == LINE then
						local MyPos = Vector(myHero.x, myHero.y, myHero.z)
						local evadingPos = GetLinealPosition(skillshot)
						evadingPosition = MyPos - (MyPos - evadingPos):normalized() * 300
					else
						local MyPos = Vector(myHero.x, myHero.y, myHero.z)
						local evadingPos = GetAOEPosition(skillshot)
						evadingPosition = MyPos - (MyPos - evadingPos):normalized() * 300
					end
					if evadingPosition and not wUsed() and not IsWall(D3DXVECTOR3(evadingPosition.x, myHero.y, evadingPosition.y)) then
						CastSpell(_W, evadingPosition.x, evadingPosition.y) 
					end
				end
			end
		end
	end
end

webTrialVersion = nil
scriptTrialVersion = "g102"
GetAsyncWebResult("bolscripts.com","scriptauth/script_date_check.php?s=leblanc&v="..curVersion, function(result) webTrialVersion = result end)
PrintChat("test: ".."script_date_check.php?s=leblanc&v="..curVersion)