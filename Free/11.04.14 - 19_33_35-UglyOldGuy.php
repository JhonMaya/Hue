<?php exit() ?>--by UglyOldGuy 64.17.247.66
-- Name Check --  
if myHero.charName ~= "Leblanc" then return end

-- Loading Function --
function OnLoad()
	Variables()
	LeblancMenu()
	PrintChat("<font color='#FFFF00'> >> Leblanc - The Deceiver 1.2 Loaded!! <<</font>")
end

-- Tick Function --
function OnTick()
	Checks()
	UseConsumables()
	DamageCalculation()

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
	if LeblancMenu.cloneSlice ~= 1 then CloneLogic() end
	if FarmingKey and not (ComboKey or HarassKey) then FarmMinions() end
end

function Variables()
	Spells = {
		
		["Q"] = {key = _Q, name = "Sigil of Silence", range = 700,  ready = false, mana = 0, dmg = 0, last = false, data = myHero:GetSpellData(_Q)},
		["W"] = {key = _W, name = "Distortion",       range = 720,  ready = false, mana = 0, dmg = 0, last = false, data = myHero:GetSpellData(_W), speed = 2000, delay = .25, width = 100, pos = nil},
		["E"] = {key = _E, name = "Ethereal Chains",  range = 1000, ready = false, mana = 0, dmg = 0, last = false, data = myHero:GetSpellData(_E), speed = 1600, delay = .25, width = 95, pos = nil},
		["R"] = {key = _R, name = "Mimic", ready = false, dmg = 0, data = myHero:GetSpellData(_R), pos = nil}
	}

	leblancW, leblancImage, cloneId = nil, nil, nil

	if VIP_USER then
		Prodict = ProdictManager.GetInstance()
		ProdictW = Prodict:AddProdictionObject(_W, Spells.W.range, Spells.W.speed, Spells.W.delay, Spells.W.width, myHero)
		ProdictE = Prodict:AddProdictionObject(_E, Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width, myHero)
		if vPredictionExists then
			vPred = VPrediction()
		end
	end
	hpReady, mpReady, fskReady, Recalling = false, false, false, false
	TextList = {"Harass him!!", "Q KILL!!", "Q + W Kill!", "Q+W+QP Kill!", "Q+W+E+QP Kill!", "Full Combo Kill!", "Need Mana or CD!"}
	KillText = {}
	colorText = ARGB(255,0,0,255)
	usingHPot, usingMPot = false, false
	enemyMinions = minionManager(MINION_ENEMY, Spells.Q.range, player, MINION_SORT_HEALTH_ASC)
	lastAnimation = nil
	focusedtarget = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
	JungleMobs = {}
	JungleFocusMobs = {}
	debugMode = false

	Items = {
		["BLACKFIRE"]	= { id = 3188, range = 750, ready = false, dmg = 0 },
		["BRK"]			= { id = 3153, range = 500, ready = false, dmg = 0 },
		["BWC"]			= { id = 3144, range = 450, ready = false, dmg = 0 },
		["DFG"]			= { id = 3128, range = 750, ready = false, dmg = 0 },
		["HXG"]			= { id = 3146, range = 700, ready = false, dmg = 0 },
		["ODYNVEIL"]	= { id = 3180, range = 525, ready = false, dmg = 0 },
		["DVN"]			= { id = 3131, range = 200, ready = false, dmg = 0 },
		["ENT"]			= { id = 3184, range = 350, ready = false, dmg = 0 },
		["HYDRA"]		= { id = 3074, range = 350, ready = false, dmg = 0 },
		["TIAMAT"]		= { id = 3077, range = 350, ready = false, dmg = 0 },
		["YGB"]			= { id = 3142, range = 350, ready = false, dmg = 0 }
	}

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
		LeblancMenu.farming:addParam("qFarm", "Farm with "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.farming:addParam("qFarmMana", "Min Mana % for Farming", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
		LeblancMenu.farming:permaShow("farmKey") 
		
	LeblancMenu:addSubMenu("["..myHero.charName.." - Clear Settings]", "jungle")
		LeblancMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86)
		LeblancMenu.jungle:addParam("jungleQ", "Clear with "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleW", "Clear with "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleE", "Clear with "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.jungle:addParam("jungleOrbwalk", "Orbwalk the Jungle", SCRIPT_PARAM_ONOFF, true)

	LeblancMenu:addSubMenu("["..myHero.charName.." - KillSteal Settings]", "ks")
		LeblancMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.ks:permaShow("killSteal")
			
	LeblancMenu:addSubMenu("["..myHero.charName.." - Drawing Settings]", "drawing")	
		LeblancMenu.drawing:addParam("mDraw", "Disable All Ranges Drawing", SCRIPT_PARAM_ONOFF, false)
		LeblancMenu.drawing:addParam("cDraw", "Draw Enemy Text", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.drawing:addParam("qDraw", "Draw "..Spells.Q.name.." (Q) Range", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.drawing:addParam("wDraw", "Draw "..Spells.W.name.." (W) Range", SCRIPT_PARAM_ONOFF, false)
		LeblancMenu.drawing:addParam("eDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, false)
	
	LeblancMenu:addSubMenu("["..myHero.charName.." - Misc Settings]", "misc")
		LeblancMenu.misc:addParam("ZWItems", "Auto Zhonyas/Wooglets", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("ZWHealth", "Min Health % for Zhonyas/Wooglets", SCRIPT_PARAM_SLICE, 15, 0, 100, -1)
		LeblancMenu.misc:addParam("aMP", "Auto Mana Pots", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("aHP", "Auto Health Pots", SCRIPT_PARAM_ONOFF, true)
		LeblancMenu.misc:addParam("HPHealth", "Min % for Health Pots", SCRIPT_PARAM_SLICE, 50, 0, 100, -1)
	LeblancMenu:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 1, { "Prodiction", "VPrediction" })
	LeblancMenu:addParam("cloneSlic", "Clone Logic", SCRIPT_PARAM_LIST, 4, { "None", "Towards Enemy", "Random Location", "Try To Escape", "Towards Mouse" })
	TargetSelector = TargetSelector(TARGET_LOW_HP, Spells.W.range,DAMAGE_MAGIC)
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
		local Distance = GetDistanceSqr(Target)
		if Distance <= (Spells.W.range^2) and Target.health > (Spells.Q.dmg + qpDmg + qrDmg + Spells.E.dmg) then
			if Spells.W.ready then
				CastSkill(_W, Target) 
			end
			CastSkill(_Q, Target)		
			if Spells.Q.last then
				CastSkill(_R, Target)
			end
			CastSkill(_E, Target)
		elseif Distance <= ((Spells.W.range + Spells.Q.range)^2) and Target.health < (Spells.Q.dmg + qpDmg + qrDmg + Spells.E.dmg) then
			CastSkill(_W, Target)
			CastSkill(_Q, Target)	
			if Spells.Q.last then
				CastSkill(_R, Target)
			end
			CastSkill(_E, Target)
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
			if GetDistanceSqr(Target, myHero) <= Spells.Q.range*Spells.Q.range then
				if Spells.Q.ready then CastSkill(_Q, Target) end
				if Spells.W.ready and not wUsed() and qPassive then
					CastSkill(_W, Target)
				end
			elseif GetDistanceSqr(Target, myHero) <= Spells.W.range*Spells.W.range and not Spells.Q.ready then
				if Spells.W.ready then CastSkill(_W, Target) end
			elseif GetDistan2ceSqr(Target, myHero) <= ((Spells.W.range + Spells.Q.range)*(Spells.W.range + Spells.Q.range)) then
				if Spells.W.ready and not wUsed() then
					CastSkill(_W, Target)
				end
				if Spells.Q.ready then CastSkill(_Q, Target) end
			end
			if wUsed() and not Spells.Q.ready then
				if LeblancMenu.harass.secW then CastSpell(_W) end
			end
		elseif (LeblancMenu.harass.harassType == 2) then
			if GetDistanceSqr(Target, myHero) <= Spells.Q.range*Spells.Q.range then
				if Spells.Q.ready then CastSkill(_Q, Target) end
				if Spells.W.ready and not wUsed() and qPassive then
					CastSkill(_W, Target)
				end
			end
			if wUsed() and not Spells.Q.ready then
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
				if LeblancMenu.farming.qFarm and Spells.Q.ready and GetDistance(minion) <= Spells.Q.range and minion.health <= qMinionDmg then
					CastSpell(_Q, minion)
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

function ManaCost(Spell)
	if Spell == _Q and Spells.Q.data.level ~= 0 then
		return Spells.Q.mana
	elseif Spell == _W and Spells.W.data.level ~= 0 then
		return Spells.W.mana
	elseif Spell == _E and Spells.E.data.level ~= 0 then
		return Spells.E.mana
	end
	return 0
end

function ComboManaCost(Combo)
	local Result = 0	
	for i, spell in ipairs(Combo) do
		Result = Result + ManaCost(spell)
	end
	return Result
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
		if LeblancMenu.jungle.jungleQ and GetDistance(JungleMob) <= Spells.Q.range then CastSpell(_Q, JungleMob) end
		if not wUsed() and LeblancMenu.jungle.jungleW and GetDistance(JungleMob) <= Spells.W.range then CastSpell(_W, JungleMob.x, JungleMob.z) end
		if LeblancMenu.jungle.jungleE and GetDistance(JungleMob) <= Spells.E.range then CastSpell(_E, JungleMob.x, JungleMob.z) end
	end
end

-- Get Jungle Mob --
function GetJungleMob()
        for _, Mob in pairs(JungleFocusMobs) do
                if ValidTarget(Mob, Spells.Q.range) then return Mob end
        end
        for _, Mob in pairs(JungleMobs) do
                if ValidTarget(Mob, Spells.Q.range) then return Mob end
        end
end

function CastSkill(Skill, enemy)
	if Skill == _Q then
		if not Spells.Q.ready or (GetDistanceSqr(enemy) > Spells.Q.range^2) then
			return false
		end
		if VIP_USER then
			Packet("S_CAST", {spellId = _Q, targetNetworkId = enemy.networkID}):send()
			return true
		else
			CastSpell(_Q, enemy)
			return true
		end
	elseif Skill == _W and not wUsed() then
		if not Spells.W.ready then
			return false
		end
		if VIP_USER then
			if LeblancMenu.predType == 1 then
				Spells.W.pos = ProdictW:GetPrediction(enemy)
				if Spells.W.pos and not IsWall(D3DXVECTOR3(Spells.W.pos.x, Spells.W.pos.y, Spells.W.pos.z)) then
					CastSpell(_W, Spells.W.pos.x, Spells.W.pos.z)
					return true
				end
			else
				if vPredictionExists then
					local CastPosition,  HitChance,  Position = vPred:GetCircularCastPosition(enemy, Spells.W.delay, Spells.W.width, Spells.W.range)
					if HitChance >= 2 then
						if not IsWall(D3DXVECTOR3(CastPosition.x, CastPosition.y, CastPosition.z)) then
							CastSpell(_W, CastPosition.x, CastPosition.z)
							return true
						end
					end
				end
			end
		else
			local wPred = TargetPrediction(Spells.W.range, Spells.W.speed, Spells.W.delay, Spells.W.width)
           	local wPrediction = wPred:GetPrediction(enemy)
           	if wPrediction then
        		CastSpell(_W, wPrediction.x, wPrediction.z)
				return true
			end
		end
	elseif Skill == _E then
		if not Spells.E.ready or (GetDistanceSqr(enemy, myHero) > Spells.E.range*Spells.E.range) then
			return false
		end
		if VIP_USER then
			if LeblancMenu.predType == 1 then
				Spells.E.pos = ProdictE:GetPrediction(Target)
				local CollisionE =  Collision(Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width)
				if Spells.E.pos then
					if not CollisionE:GetMinionCollision(myHero, Spells.E.pos) then
						CastSpell(_E, Spells.E.pos.x, Spells.E.pos.z)
						return true
					end
				end
			else
				if vPredictionExists then
					local CastPosition, HitChance, Pos = vPred:GetLineCastPosition(enemy, Spells.E.delay, Spells.E.width, Spells.E.range, Spells.E.speed, myHero, true)
					if HitChance >= 2 then
						CastSpell(_E, CastPosition.x, CastPosition.z)
						return true
					end
				end
			end
		else
			local ePred = TargetPrediction(Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width)
            local ePrediction = ePred:GetPrediction(enemy)
            if ePrediction and not willHitMinion(ePrediction, Spells.E.width) then
				CastSpell(_E, ePrediction.x, ePrediction.z)
				return true
			end
		end
	elseif Skill == _R then
		if myHero:GetSpellData(_R).name == "leblancslidereturnm" then
			return false
		end
		local Distance = GetDistanceSqr(Target)
		if Spells.Q.last then
			if Distance <= Spells.Q.range^2 then
				if VIP_USER then
					Packet("S_CAST", {spellId = _R, targetNetworkId = enemy.networkID}):send()
					return true
				else
					CastSpell(_R, enemy)
					return true
				end
			end
		elseif Spells.W.last then
			if VIP_USER then
				if LeblancMenu.predType == 1 then
					Spells.R.pos = ProdictW:GetPrediction(enemy)
					if Spells.R.pos and not IsWall(D3DXVECTOR3(Spells.R.pos.x, Spells.R.pos.y, Spells.R.pos.z)) then
						CastSpell(_R, Spells.R.poss.x, Spells.R.pos.z)
						return true
					end
				else
					if vPredictionExists then
						local CastPosition,  HitChance,  Position = vPred:GetCircularCastPosition(enemy, Spells.W.delay, Spells.W.width, Spells.W.range)
						if HitChance >= 2 and not IsWall(D3DXVECTOR3(CastPosition.x, CastPosition.y, CastPosition.z)) then 
							CastSpell(_R, CastPosition.x, CastPosition.z)
							return true
						end
					end
				end
			else
				local wrPred = TargetPrediction(Spells.W.range, Spells.W.speed, Spells.W.delay, Spells.W.width)
            	local wrPrediction = wrPred:GetPrediction(enemy)
            	if wrPrediction then
					CastSpell(_W, wrPrediction.x, wrPrediction.z)
					return true
				end
			end
		elseif Spells.E.last then
			if Distance <= Spells.E.range^2 then
				if VIP_USER then
					if LeblancMenu.predType == 1 then
						local erPos = ProdictE:GetPrediction(Target)
						local CollisionER =  Collision(Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width)
						if erPos then
							if not CollisionER:GetMinionCollision(myHero, Spells.E.pos) then
								CastSpell(_E, erPos.x, erPos.z)
								return true
							end
						end
					else
						if vPredictionExists then
							local CastPosition, HitChance, Pos = vPred:GetLineCastPosition(enemy, Spells.E.delay, Spells.E.width, Spells.E.range, Spells.E.speed, myHero, true)
							if HitChance >= 2 then
								CastSpell(_R, CastPosition.x, CastPosition.z)
								return true
							end
						end
					end
				else
					local erPred = TargetPrediction(Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width)
            		local erPrediction = erPred:GetPrediction(enemy)
            		if erPrediction and not willHitMinion(erPrediction, Spells.E.width) then
						CastSpell(_E, erPrediction.x, erPrediction.z)
						return true
					end
				end 
			end
		end
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

function CloneLogic()
	if leblancImage and leblancImage.valid then
		if LeblancMenu.cloneLogic == 2 and Target then
			Packet('S_MOVE', {type = 6, x = Target.x, y = Target.z, sourceNetworkId = cloneId}):send()
		elseif LeblancMenu.cloneLogic == 3 then
			local movepoint =  wp:GetWayPoints(myHero)
			local line = Vector(leblancImage) - Vector(myHero):perpendicular()
			local Direction = (Vector(movepoint[#movepoint].x, 0, movepoint[#movepoint].z) - Vector(myHero)):mirrorOn(line):normalized()
			
			local movepos = Vector(leblancImage) + 500 * Direction
			Packet('S_MOVE', {type = 6, x = movepos.x, y = movepos.z, sourceNetworkId = cloneId}):send()
		elseif LeblancMenu.cloneLogic == 4 then
			local Point = Vector(0, 0, 0)
			local Count = 0
			for i, hero in ipairs(GetAllyHeroes()) do
				Point = Vector(Point) + Vector(hero)
				Count = Count + 1
			end
			Count = Count or 1
			Point = 1/Count * Vector(Point)
			Packet('S_MOVE', {type = 6, x = Point.x, y = Point.z, sourceNetworkId = cloneId}):send()
		else
			Packet('S_MOVE', {type = 6, x = mousePos.x, y = mousePos.z, sourceNetworkId = cloneId}):send()
		end
	end
end

-- Use Items on Enemy --
function UseItems(enemy)
	for _, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) and GetDistanceSqr(enemy) <= item.range^2 then
			CastItem(item.id, enemy)
		end
	end
end

-- KillSteal function --
function KillSteal()
	if ValidTarget(Target) then
		local comboMana = 0
		local wqRange = Spells.W.range + Spells.Q.range
		local wwqRange = (Spells.W.range * 2) + Spells.Q.range
		if GetDistance(Target) <= wwqRange and GetDistance(Target) > wqRange then
			if Spells.W.ready and Spells.R.ready and Spells.Q.ready then
				comboMana = Spells.Q.mana + Spells.W.mana
				if Target.health <= Spells.Q.dmg and myMana >= comboMana then
					CastSkill(_W, Target)
					if debugMode then PrintChat("338") end
				end
			end
		elseif GetDistance(Target) <= wqRange and GetDistance(Target) > Spells.Q.range then
			if Target.health <= Spells.Q.dmg and Spells.W.ready and Spells.Q.ready then
				if wUsed() then
					if Spells.R.ready then
						 CastSkill(_R, Target)
					end
				elseif not wUsed() then
					comboMana = Spells.Q.mana + Spells.W.mana
					if myMana > comboMana then
						CastSkill(_W, Target)
						if debugMode then PrintChat("348") end
					end
				end
			end
		elseif GetDistance(Target) <= Spells.Q.range and Target.health <= Spells.Q.dmg then
			if Spells.Q.ready then
				comboMana = Spells.Q.mana
				if myMana > comboMana then
					CastSkill(_Q, Target)
					if debugMode then PrintChat("358") end
				end
			end
		elseif GetDistance(Target) <= Spells.W.range and Target.health <= Spells.W.dmg then
			if Spells.W.ready then
				comboMana = Spells.W.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					if debugMode then PrintChat("366") end
				elseif wUsed() and Spells.R.ready then
					CastSkill(_R, Target)
					if debugMode then PrintChat("369") end
				end
			end
		elseif GetDistance(Target) <= Spells.E.range and Target.health <= Spells.E.dmg then
			if Spells.E.ready then
				comboMana = Spells.E.mana
				if myMana > comboMana then
					CastSkill(_E, Target)
					if debugMode then PrintChat("377") end
				end
			end
		elseif GetDistance(Target) <= Spells.W.range and Target.health <= (Spells.W.dmg + Spells.Q.dmg) then
			if Spells.W.ready and Spells.Q.ready then
				comboMana = Spells.Q.mana + Spells.W.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					if debugMode then PrintChat("385") end
				end
			end
		elseif GetDistance(Target) <= Spells.E.range and Target.health <= (Spells.E.dmg + Spells.Q.dmg) then
			if Spells.E.ready and Spells.Q.ready then
				comboMana = Spells.Q.mana + Spells.W.mana
				if myMana > comboMana then
					CastSkill(_E, Target)
					if debugMode then PrintChat("393") end
				end
			end
		elseif GetDistance(Target) <= Spells.W.range and Target.health <= (Spells.W.dmg + Spells.E.dmg) then
			if Spells.W.ready and Spells.E.ready then
				comboMana = Spells.W.mana + Spells.E.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					if debugMode then PrintChat("401") end
				end
			end
		elseif GetDistance(Target) <= wqRange and Target.health <= (Spells.Q.dmg + Spells.E.dmg) then
			if Spells.W.ready and Spells.Q.ready and Spells.E.ready then
				comboMana = Spells.W.mana + Spells.E.mana + Spells.Q.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					CastSkill(_Q, Target)
					if debugMode then PrintChat("410") end
				end
			end
		elseif GetDistance(Target) <= Spells.Q.range and Target.health <= qrDmg then
			if Spells.Q.last and Spells.R.ready then
				CastSkill(_R, Target)
				if debugMode then PrintChat("467") end
			end
		elseif GetDistance(Target) <= Spells.W.range and Target.health <= wrDmg then
			if Spells.W.last and Spells.R.ready then
					CastSkill(_R, Target)
					if debugMode then PrintChat("472") end
			end
		elseif GetDistance(Target) <= wqRange and GetDistance(Target) > Spells.Q.range and Target.health < (qrDmg + Spells.Q.dmg) then
			if Spells.W.ready and Spells.Q.ready and Spells.R.ready then
				comboMana = Spells.W.mana + Spells.Q.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					CastSkill(_Q, Target)
					if debugMode then PrintChat("420") end
				end
			end
		elseif GetDistance(Target) < Spells.Q.range and Target.health < (qpDmg + qrDmg + Spells.E.dmg) then
			if Spells.R.ready and Spells.E.ready and Spells.Q.last then
				comboMana = Spells.E.mana
				if myMana > comboMana then
					CastSkill(_R, Target)
					if debugMode then PrintChat("488") end
				end
			end
		elseif GetDistance(Target) < Spells.Q.range and Target.health < (Spells.Q.dmg + qpDmg + qrDmg + Spells.E.dmg) then
			if Spells.Q.ready and Spells.R.ready and Spells.E.ready then
				comboMana = Spells.Q.mana + Spells.E.mana
				if myMana > comboMana then
					CastSkill(_Q, Target)
					if debugMode then PrintChat("496") end
				end
			end
		elseif GetDistance(Target) < Spells.W.range and Target.health < (Spells.W.dmg + qpDmg + Spells.Q.dmg + qrDmg + Spells.E.dmg) then
			if Spells.Q.ready and Spells.W.ready and Spells.E.ready and Spells.R.ready then
				comboMana = Spells.W.mana + Spells.Q.mana + Spells.E.mana
				if not wUsed() and myMana > comboMana then
					CastSkill(_W, Target)
					if debugMode then PrintChat("505") end
				end
			end
		elseif GetDistance(Target) < Spells.W.range and Target.health < (Spells.W.dmg + qpDmg + Spells.Q.dmg + qrDmg + Spells.E.dmg + itemsDmg) then
			if Spells.Q.ready and Spells.W.ready and Spells.E.ready and Spells.R.ready then
				comboMana = Spells.W.mana + Spells.Q.mana + Spells.E.mana
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
			if Spells.Q.ready and Target.health <= Spells.Q.dmg then
				CastSkill(_Q, Target)
			elseif Spells.W.ready and Target.health <= Spells.W.dmg then
				if not wUsed() then 
					CastSkill(_W, Target)
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
function OnSendPacket(p)
	if p.header == 113 then
		dwArg1 = p.dwArg1
		dwArg2 = p.dwArg2
		sourceNetworkId = p:DecodeF()
		if sourceNetworkId ~= myHero.networkID then
			cloneId = sourceNetworkId
		end
	end
end

-- Damage Calculations --
function DamageCalculation()
	for i=1, heroManager.iCount do
	local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			myMana = (myHero.mana)
			qrDmg = getDmg("R", enemy, myHero)
			wrDmg = getDmg("R", enemy, myHero, 2)
			erDmg = getDmg("R", enemy, myHero, 3)
			qpDmg = getDmg("Q", enemy, myHero, 2)
			Spells.Q.dmg = (Spells.Q.ready and getDmg("Q", enemy, myHero)) or 0
    		Spells.W.dmg = (Spells.W.ready and getDmg("W", enemy, myHero)) or 0
			Spells.E.dmg = (Spells.E.ready and getDmg("E", enemy, myHero)) or 0
			Items.DFG.dmg = (Items.DFG.ready and getDmg("DFG", enemy, myHero) or 0)
            iDmg = (ignite and getDmg("IGNITE", enemy, myHero)) or 0
            itemsDmg = Items.DFG.dmg

            -- Calculations for drawing text --
            if enemy.health > (Spells.Q.dmg + qpDmg + Spells.W.dmg + qrDmg + Spells.E.dmg + itemsDmg) then
				KillText[i] = 1
				colorText = ARGB(255,0,0,255)
			elseif enemy.health <= Spells.Q.dmg and Spells.Q.ready then
				if myMana > Spells.Q.mana then
					KillText[i] = 2
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (Spells.Q.dmg + Spells.W.dmg) and Spells.Q.ready and Spells.W.ready then
				if myMana > (Spells.Q.mana + Spells.W.mana) and enemy.health > Spells.Q.dmg then
					KillText[i] = 3
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (Spells.Q.dmg + Spells.W.dmg + qpDmg) and Spells.Q.ready and Spells.W.ready then 
				if myMana > (Spells.Q.mana + Spells.W.mana) and enemy.health > (Spells.Q.dmg + Spells.W.dmg) then
					KillText[i] = 4
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (Spells.Q.dmg + Spells.W.dmg + Spells.E.dmg + qpDmg) and Spells.Q.ready and Spells.W.ready and Spells.E.ready then
				if myMana > (Spells.Q.mana + Spells.E.mana + Spells.W.mana) and enemy.health > (Spells.Q.dmg + Spells.W.dmg + Spells.E.dmg) then
					KillText[i] = 5
					colorText = ARGB(255,255,0,0)
				end
			elseif enemy.health <= (Spells.Q.dmg + qpDmg + Spells.W.dmg + qrDmg + Spells.E.dmg + itemsDmg) then
				if myMana > (Spells.Q.mana + Spells.E.mana + Spells.W.mana) and enemy.health > (Spells.Q.dmg + Spells.W.dmg + Spells.E.dmg + qpDmg) then
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
				if Target.health > (Spells.Q.dmg + qpDmg + Spells.W.dmg + qrDmg + Spells.E.dmg + itemsDmg + 500) then
					CastSpell(_W)
				end
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
		if Spells.Q.ready and LeblancMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.Q.range, 0xFFFF00)
		end
		if Spells.W.ready and LeblancMenu.drawing.wDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.W.range, 0xFFFF00)
		end
	end
	if LeblancMenu.drawing.cDraw then
		for i = 1, heroManager.iCount do
        	local Unit = heroManager:GetHero(i)
        	if ValidTarget(Unit) then
        		local barPos = WorldToScreen(D3DXVECTOR3(Unit.x, Unit.y, Unit.z)) --(Credit to Zikkah)
				local PosX = barPos.x - 35
				local PosY = barPos.y - 10        
        	 	DrawText(TextList[KillText[i]], 14, PosX, PosY, colorText)
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
        	Spells.Q.last, Spells.W.last, Spells.E.last = true, false, false
        elseif spell.name == "LeblancSlide" then
        	Spells.Q.last, Spells.W.last, Spells.E.last = false, true, false
        elseif spell.name == "LeblancSoulShackle" then
        	Spells.Q.last, Spells.W.last, Spells.E.last = false, false, true
        end
    end
end

function OnAnimation(unit, animationName)
    if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function GetTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then
    	return _G.MMA_Target
   	elseif _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair then
   		return _G.AutoCarry.Attack_Crosshair.target
   	elseif TargetSelector.target and not TargetSelector.target.dead and TargetSelector.target.type  == "obj_AI_Hero" then
    	return TargetSelector.target
    else
    	return nil
    end
end

-- Spells/Items Checks --
function Checks()
	-- Updates Targets --
	Target = GetTarget()

	-- Updates Items --
	for _, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) then
			item.ready = true
		end
	end
	
	-- Updates Spell Info --
	for _, spell in pairs(Spells) do
		if (myHero:CanUseSpell(spell.key) == READY) then
			spell.ready = true
			spell.mana = myHero:GetSpellData(spell.key).mana
		end
	end

	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	end

	iReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)

	-- Pots --
	hpReady = (hpSlot ~= nil and myHero:CanUseSpell(hpSlot) == READY)
	mpReady =(mpSlot ~= nil and myHero:CanUseSpell(mpSlot) == READY)
	fskReady = (fskSlot ~= nil and myHero:CanUseSpell(fskSlot) == READY)
	
	-- Updates Minions --
	enemyMinions:update()
end