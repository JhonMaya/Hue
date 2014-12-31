<?php exit() ?>--by chancity 72.87.162.226
--Nidalee Reborn Plugin by Chancey and Kain, BETA
--Pounce to mouse holding spacebar
--Combos with WEQ while in Cougar (Aims pounce to enemy perfectly)
--Hit Box PROdicition for Javelin Toss
--Jungle Farming Q/W/E Cougar
--Lane Clearing with W/E Cougar
--Auto Heal Self, Allies, and Force Heal (When in cougar form)
--Auto switch to human and Q when enemy killable.
--Auto switch to cougar when close range.
--Auto switch to cougar and pounce for speed when running longer distances. Optionally show waypoints for this.
--Properly keep track of cooldowns even when in other form.
--Draw targets waypoints (where they are moving to) and line to target.
--Human Q, then cougar pounce away. Does higher Q damage.

--[[
	Version History:
		Version: 1.43:
			Added Revamped compatibility.
			Made heal only occur between auto attacks, instead of interrupting.
			Added alerting of available human spells when in cougar form.
			Fix pounce not spamming in AutoCarry mode. Now only fires when mouse distance far enough. See extras menu.
			Added toggle for mouse pounce.
			Renamed pounce in menu to Rush Pounce for distances.
			Added Revamped jungle clearing.
		Version: 1.39:
			Replaced ignite function.
			Fixed Q killsteal.
			Fixed pounce range on Q killsteal.
			Changed default hotkeys on heal and cougar.
		Version: 1.35:
			First public release.

	To Do:
--]]

if myHero.charName ~= "Nidalee" then return end

require "VPrediction"

webTrialVersion = nil
scriptTrialVersion = "g101"
GetAsyncWebResult("bolscripts.com","scriptauth/script_date_check.php", function(result) webTrialVersion = result end)

function _G.PluginOnLoad()
	Vars()
	Menu()
	UpdateSkillRanges()
	VP = VPrediction()
end

function Vars()
	curVersion = "1.43"
	
	local VP = nil
	
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end

	-- Disable SAC Reborn's skills. Ours are better.
	if IsSACReborn then
		AutoCarry.Skills:DisableAll()
	end
	
	currentForm = true
	recall = false
	
	if VIP_USER then
		AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
		AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
	end

	igniteRange = 600

	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
    elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	else
		ignite = nil
	end

	QReady, WReady, EReady, RReady = false, false, false, false
	DFGSlot, HXGSlot, BWCSlot, STDSlot, SheenSlot, TrinitySlot, LichBaneSlot = nil, nil, nil, nil, nil, nil, nil
	DFGReady, HXGReady, BWCReady, STDReady, IReady = false, false, false, false, false

	enemyMinions = {}
	enemyMinions = minionManager(MINION_ENEMY, 1600, myHero, MINION_SORT_HEALTH_ASC)

	PounceMinRange = 145
	PounceMaxRange = 545

	lastAttack = 0
	nextAttack = 0

	Cooldown = {
		["Q"] = 0,
		["W"] = 0,
		["E"] = 0,
		["R"] = 0,
		["QM"] = 0,
		["WM"] = 0,
		["EM"] = 0
	}

	Alerted = {["Q"] = false, ["W"] = false, ["E"] = false, ["R"] = false, ["QM"] = false, ["WM"] = false, ["EM"] = false}

	DIRECTION_AWAY = 0 
	DIRECTION_TOWARDS = 1
	DIRECTION_UNKNOWN = 2

	wpm = WayPointManager()
	RushMinDistance = 3000

	tick = 0

	Target = nil
	LastTarget = nil

	QCollision = Collision(1500, 1300, 0.125, 90)
end

function Menu()
	Menu = AutoCarry.PluginMenu
	-- Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "Nidalee "..tostring(curVersion))
	Menu:addParam("sep", "----- Nidalee by Kain & Chancey: v"..tostring(curVersion).." -----", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu(""..myHero.charName.." Auto Carry: Auto Carry", "autocarry")
		Menu.autocarry:addParam("CastR","Auto Cougar (J)", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("J"))
		Menu.autocarry:addParam("CastW","Use Pounce (X)", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("X"))
		Menu.autocarry:addParam("CastE","Primal Surge Before Cougar", SCRIPT_PARAM_ONOFF, true)
		Menu.autocarry:permaShow("CastW")
		Menu.autocarry:permaShow("CastR")
		
	-- Menu:addSubMenu(""..myHero.charName.." Auto Carry: Mixed Mode", "mixedmode")
	--	Menu.mixedmode:addParam("CastW","Use Bushwack", SCRIPT_PARAM_ONOFF, true)
		
	Menu:addSubMenu(""..myHero.charName.." Auto Carry: Lane Clear", "laneclear")
		Menu.laneclear:addParam("CastW","Use Pounce", SCRIPT_PARAM_ONOFF, true)
		Menu.laneclear:addParam("CastE","Use Swipe", SCRIPT_PARAM_ONOFF, true)
		
	Menu:addSubMenu(""..myHero.charName.." Auto Carry: Healing", "healing")
		Menu.healing:addParam("AutoHeal", "Auto Heal", SCRIPT_PARAM_ONOFF, true)
		Menu.healing:addParam("ForceHeal", "Force Heal Self", SCRIPT_PARAM_ONOFF, false)
		Menu.healing:addParam("HealHealth", "Heal Health %", SCRIPT_PARAM_SLICE, 70, 0, 100, 0)

				Menu.healing:addSubMenu("Heal Ally Settings", "healally")
					Menu.healing.healally:addParam("HealAllies", "Heal Allies (H)", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("H"))
					Menu.healing.healally:permaShow("HealAllies")
					for i=1, heroManager.iCount do
						local ally = heroManager:GetHero(i)
						if ally.team == myHero.team and ally.name ~= myHero.name then Menu.healing.healally:addParam("heal"..i, "Heal "..ally.charName, SCRIPT_PARAM_ONOFF, true) end
					end

	Menu:addSubMenu(""..myHero.charName.." Auto Carry: Draw", "draw")
		-- Menu.draw:addParam("DrawKillable","Draw Killable", SCRIPT_PARAM_ONOFF, true)
		-- Menu.draw:addParam("DrawKillableTextSize","Draw Killable Text Size", SCRIPT_PARAM_SLICE, 15, 0, 40, 0)
		-- Menu.draw:addParam("DrawTextTargetColor","Target Color", SCRIPT_PARAM_COLOR, {255,0,238,0})
		-- Menu.draw:addParam("DrawTextUnitColor","Unit Color", SCRIPT_PARAM_COLOR, { 255, 255, 50, 50 })
		Menu.draw:addParam("DrawSpellAlerts","Draw Spell Alerts", SCRIPT_PARAM_ONOFF, true)
		Menu.draw:addParam("DrawRange","Draw Skill Range", SCRIPT_PARAM_ONOFF, true)
		Menu.draw:addParam("DrawRushWaypoints","Draw Rush Waypoints", SCRIPT_PARAM_ONOFF, true)
		Menu.draw:addParam("DrawTargetArrow","Draw Target Arrow", SCRIPT_PARAM_ONOFF, false)
		Menu.draw:addParam("DrawTargetLine","Draw Target Line", SCRIPT_PARAM_ONOFF, true)
		Menu.draw:addParam("DrawTargetWaypoints","Draw Target Waypoints", SCRIPT_PARAM_ONOFF, true)
		-- Menu.draw:addParam("DrawArrow","Draw Arrow", SCRIPT_PARAM_ONOFF, true)
		
	Menu:addSubMenu(""..myHero.charName.." Auto Carry: Extras", "extras")
		Menu.extras:addParam("Ignite","Use Ignite", SCRIPT_PARAM_ONOFF, true)
		Menu.extras:addParam("DoubleIgnite", "Don't Double Ignite", SCRIPT_PARAM_ONOFF, true)
		Menu.extras:addParam("RushPounce","Auto Pounce to Rush", SCRIPT_PARAM_ONOFF, true)
		Menu.extras:addParam("MousePounce","Auto Pounce to Mouse", SCRIPT_PARAM_ONOFF, true)
		Menu.extras:addParam("MousePounceMouseDiff", "Mouse Pounce Min. Mouse Diff.", SCRIPT_PARAM_SLICE, 800, 100, 1000, 0)
		Menu.extras:addParam("MinMana","Mana Manager %", SCRIPT_PARAM_SLICE, 40, 0, 100, 0)
end

function _G.PluginOnTick()
	tick = GetTickCount()

	if (webTrialVersion ~= nil and scriptTrialVersion ~= webTrialVersion) or webTrialVersion == nil then
		if webTrialVersion ~= nil then
			print("<font color='#c22e13'>This version of the script has been disabled, go to forums for update</font>")
			return 
		end

		return
	end

	if myHero.dead or recall then return end
	
	CheckSpells()
	SpellAlerts()

	if Menu.healing.AutoHeal then Heal() end	

	--[[if JumpAssistant() and not currentForm then
		if JumpAssistantIsBusy() then
			SetMovement(false)
		else
			SetMovement(true)
		end
	end]]--

	if AutoCarry.MainMenu.AutoCarry and (Target ~= nil or LastTarget ~= nil) then
		SwitchFormIfNeeded()
	end

	if AutoCarry.MainMenu.AutoCarry and Target ~= nil then
		if currentForm then
			CastBushwhackonCCEnemy(Target)
			CastQ(Target)
		else
			CougarCombo()
		end

		if Menu.extras.MousePounce and Menu.autocarry.CastW and not currentForm and GetDistance(Target) > 600 and GetDistance(mousePos) > Menu.extras.MousePounceMouseDiff then
			MoveAndCastPounce(mousePos)
		end
	end
	
	if (AutoCarry.MainMenu.MixedMode or AutoCarry.MainMenu.LaneClear) and currentForm and Target ~= nil then
		-- if Menu.mixedmode.CastW and CheckMana() then CastW(Target) end
		if CheckMana() then CastQ(Target) end
	end

	if AutoCarry.MainMenu.LaneClear and not currentForm then
		if Menu.laneclear.CastE then
			SmartFarm()
			FarmMinion()
		end

		local JungleTarget = nil
		if IsSACReborn then
			JungleTarget = AutoCarry.Jungle:GetAttackableMonster()
		else
			local JungleMobs = AutoCarry.GetJungleMobs()
			for _, Monster in pairs(JungleMobs) do
				JungleTarget = Monster
				break
			end
		end

		if JungleTarget ~= nil then
			CastSpellToward(_W, JungleTarget, PounceMinRange)
			if not JungleTarget.dead then
				CastSpellToward(_E, JungleTarget)
			end
			if not JungleTarget.dead then
				CastQ(JungleTarget)
			end
		end	
	end	
	
	if Menu.extras.Ignite then AutoIgnite() end

	RushDistance()
end

function SetMovement(movement)
	if IsSACReborn then
		AutoCarry.MyHero:MovementEnabled(movement)
	else
		AutoCarry.CanMove = movement
	end
end

function _G.PluginOnProcessSpell(object, spell)
    if object~= nil and spell ~= nil and object.isMe then
--[[ Don't remove!
		if spell.name == "JavelinToss" then 
            Cooldown["Q"] = GetGameTimer() + (myHero:GetSpellData(_Q).cd * (1 + myHero.cdr))
        elseif spell.name == "Bushwhack" then 
            Cooldown["W"] = GetGameTimer() + (myHero:GetSpellData(_W).cd * (1 + myHero.cdr))
        elseif spell.name == "PrimalSurge" then 
            Cooldown["E"] = GetGameTimer() + (myHero:GetSpellData(_E).cd * (1 + myHero.cdr))
        elseif spell.name == "AspectOfTheCougar" then 
            Cooldown["R"] = GetGameTimer() + (myHero:GetSpellData(_R).cd * (1 + myHero.cdr))
		elseif spell.name == "Takedown" then 
            Cooldown["QM"] = GetGameTimer() + (myHero:GetSpellData(_Q).cd * (1 + myHero.cdr))
        elseif spell.name == "Pounce" then 
            Cooldown["WM"] = GetGameTimer() + (myHero:GetSpellData(_W).cd * (1 + myHero.cdr))
        elseif spell.name == "Swipe" then 
            Cooldown["EM"] = GetGameTimer() + (myHero:GetSpellData(_E).cd * (1 + myHero.cdr))
        end
--]]
        if spell.name == "JavelinToss" then 
            Cooldown["Q"] = GetGameTimer() + (5.76 * (1 + myHero.cdr))
			Alerted["Q"] = false
        elseif spell.name == "Bushwhack" then 
            Cooldown["W"] = GetGameTimer() + (17.28 * (1 + myHero.cdr))
			Alerted["W"] = false
        elseif spell.name == "PrimalSurge" then 
            Cooldown["E"] = GetGameTimer() + (9.6 * (1 + myHero.cdr))
			Alerted["E"] = false
        elseif spell.name == "AspectOfTheCougar" then 
            Cooldown["R"] = GetGameTimer() + (3.84 * (1 + myHero.cdr))
		elseif spell.name == "Takedown" then 
            Cooldown["QM"] = GetGameTimer() + (4.8 * (1 + myHero.cdr))
        elseif spell.name == "Pounce" then 
            Cooldown["WM"] = GetGameTimer() + (3.36 * (1 + myHero.cdr))
        elseif spell.name == "Swipe" then 
            Cooldown["EM"] = GetGameTimer() + (5.76 * (1 + myHero.cdr))
        end
    end
end

function IsSpellReady(spell)
	return GetGameTimer() > Cooldown[spell]
end

function SwitchFormIfNeeded()
	if Menu.autocarry.CastR and RReady then
		if currentForm then
			if ValidTarget(Target) and GetDistance(Target) < PounceMaxRange then
				if Menu.autocarry.CastE then CastE(myHero) end
				CastSpell(_R)
			end
		else
			if KillstealJavelin(true, LastTarget) then return true end
		end
	end
end

function KillstealJavelin(switchNeeded, mainTarget)
	if IsSpellReady("Q") then
		if mainTarget and mainTarget.valid and not mainTarget.dead and GetDistance(mainTarget) <= 1500 then
			local qDmg = getDmg("Q", mainTarget, myHero)
			if qDmg > mainTarget.health then
				if switchNeeded and not currentForm and GetDistance(LastTarget) > PounceMaxRange then CastSpell(_R) end
				if currentForm then CastQ(mainTarget) end
				return true
			end
		end

		for _, enemy in pairs(GetEnemyHeroes()) do
			if enemy and enemy.valid and not enemy.dead and GetDistance(enemy) < QRange then
				local qDmg = getDmg("Q", enemy, myHero)
				if (enemy.health <= qDmg or (not QReady and not WReady and not EReady)) and GetDistance(enemy) > PounceMaxRange and GetDistance(enemy) <= 1500 then
					if switchNeeded and not currentForm then CastSpell(_R) end
					if currentForm then CastQ(enemy) end
					return true
				end
			end
		end
	end

	return false
end

local function getHitBoxRadius(target)
	return GetDistance(target, target.minBBox)
end

function FireQ(unit) 
	CastPosition,  HitChance,  Position = VP:GetLineCastPosition(unit, .1, 90, 1500, 1270, myHero)
	
	local unitDirection = GetDirection(unit)
	local unitDistance = GetDistance(Position)

	if ((unitDirection ~= DIRECTION_AWAY and unitDistance < 1500) or (unitDirection == DIRECTION_AWAY and unitDistance < (1500 * .95))) and
	myHero:GetSpellData(_Q).name == "JavelinToss" and not QCollision:GetMinionCollision(Position, myHero) then
     		if HitChance >= 2 and myHero:GetSpellData(_Q).name == "JavelinToss" and not QCollision:GetMinionCollision(Position, myHero) then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
     		end
	end
end

function FireW(unit)
	CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(unit, 0.9, 80, 900)
    if HitChance >= 2 and GetDistance(CastPosition) < 900 then
        CastSpell(_W, CastPosition.x, CastPosition.z)
    end
end

function CastSpellToward(spell, enemy, minRange)
	if spell and myHero:CanUseSpell(spell) == READY and enemy and (not minRange or GetDistance(enemy) >= minRange) then
		if IsSACReborn then
			AutoCarry.MyHero:MovementEnabled(false)
		else
			AutoCarry.CanMove = false
		end
		
		if spell == _W then
			MoveAndCastPounce(enemy)
		else
			myHero:MoveTo(enemy.x, enemy.z)
			CastSpell(spell)
		end

		if IsSACReborn then
			AutoCarry.MyHero:MovementEnabled(true)
		else
			AutoCarry.CanMove = true
		end
	end
end

function MoveAndCastPounce(pos)
	alpha = math.atan(math.abs(pos.z - myHero.z) / math.abs(pos.x - myHero.x))
	locX = math.cos(alpha) * (GetDistance(pos) - (GetDistance(pos) - 300))
	locZ = math.sin(alpha) * (GetDistance(pos) - (GetDistance(pos) - 300))

	myHero:MoveTo(math.sign(pos.x - myHero.x) * locX + myHero.x, math.sign(pos.z - myHero.z) * locZ + myHero.z)
	DelayAction(function() CastSpell(_W) end, 0.15)
end

function _G.PluginOnDraw()
	if myHero.dead or recall then return end

	if Target and not Target.dead and (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.MixedMode) then
		if Menu.draw.DrawTargetArrow then DrawArrowsToPos(myHero, Target) end

		if Menu.draw.DrawTargetLine then
			local x1, y1, OnScreen1 = get2DFrom3D(myHero.x, myHero.y, myHero.z)
			local x2, y2, OnScreen2 = get2DFrom3D(Target.x, Target.y, Target.z)
			DrawLine(x1, y1, x2, y2, 3, 0xFFFF0000)
		end

		if Menu.draw.DrawTargetWaypoints then
			wpm:DrawWayPoints(Target, ARGB(255, 255, 0, 0))
		end
	end

	if Menu.draw.DrawRushWaypoints then DrawWayPoints() end

	if currentForm then
		if Menu.draw.DrawRange and QReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0xe066a3)
		elseif Menu.draw.DrawRange and WReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0xe066a3)
		elseif Menu.draw.DrawRange and EReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0xe066a3)
		end
	else
		if Menu.draw.DrawRange and WReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0xe066a3)
		elseif Menu.draw.DrawRange and EReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0xe066a3)
		elseif Menu.draw.DrawRange and QReady then
			DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0xe066a3)
		end
	end
end

function DrawWayPoints()
	local points = wpm:GetWayPoints(myHero)

	if points and #points >= 2 then
		local endPoint = {x = points[#points].x, y = myHero.y, z = points[#points].y}
		
		if not currentForm and GetDistance(endPoint) > RushMinDistance then
			wpm:DrawWayPoints(myHero, ARGB(255, 0, 255, 0))
		end
	end
end

function DrawArrowsToPos(pos1, pos2)
	if pos1 and pos2 then
		startVector = D3DXVECTOR3(pos1.x, pos1.y, pos1.z)
		endVector = D3DXVECTOR3(pos2.x, pos2.y, pos2.z)
		DrawArrows(startVector, endVector, 60, 0xE97FA5, 100)
	end
end

function CougarCombo()
	if GetDistance(mousePos) < PounceMaxRange then
		CastW(Target)
	end
	CastQ(Target)
	CastE(Target)
end

function CastQ(unit)
	if QReady and ValidTarget(unit) then
		if currentForm then
			if GetDistance(unit) <= QRange then
				FireQ(unit) 
			end
		else
			if GetDistance(unit) <= QRange then
				CastSpell(_Q, unit)
			end
		end
	end
end

function CastW(unit)
	if WReady and ValidTarget(unit) then
		if currentForm then
			if GetDistance(unit) <= WRange then
				FireW(unit)
			end
		else
			if Menu.autocarry.CastW then
				CastSpellToward(_W, unit, PounceMinRange)
			end
		end
	end
end

function CastE(unit)
	if EReady then 
		if currentForm and unit ~= nil and unit.team == myHero.team then
			CastSpell(_E, unit)
		elseif not currentForm and unit ~= nil then
			if GetDistance(unit) < ERange and ValidTarget(unit) then
				CastSpell(_E, unit)
			end
		end
	end
end

function SmartFarm()
	local minions = {}
	local spell = {}
	
	for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
		local wDmg = getDmg("WM", minion, myHero)
		local eDmg = getDmg("EM", minion, myHero)

		if ValidTarget(minion) then
			local minionDistance = GetDistance(minion)

		--	if EReady and WReady and GetDistance(minion) >= PounceMinRange and minionDistance <= (ERange + WRange) and minion.health < (eDmg + wDmg) and minion.health > eDmg and minion.health > wDmg then
		--		table.insert(minions, minion)
		--		spell[1] = _W
		--		spell[2] = _E
			if EReady and minionDistance <= ERange and minion.health < eDmg then
				table.insert(minions, minion)
				spell[1] = _E
			elseif WReady and minionDistance >= PounceMinRange and minion.health < wDmg then
				table.insert(minions, minion)
				spell[1] = _W
			end
		end
	end

	local minionClusters = {}
	local closeMinion = 125

	for _, minion in pairs(minions) do
		local foundCluster = false
		for i, mc in ipairs(minionClusters) do
			if GetDistance(mc, minion) < closeMinion then
				mc.x = ((mc.x * mc.count) + minion.x) / (mc.count + 1)
				mc.z = ((mc.z * mc.count) + minion.z) / (mc.count + 1)
				mc.count = mc.count + 1
				foundCluster = true
				break
			end
		end

		if not foundCluster then
			local mc = {x=0, z=0, count=0}
			mc.x = minion.x
			mc.z = minion.z
			mc.count = 1
			table.insert(minionClusters, mc)
		end
	end

	if #minionClusters < 1 then return end

	local largestCluster = 0
	local largestClusterSize = 0
	for i, mc in ipairs(minionClusters) do
		if mc.count > largestClusterSize then
			largestCluster = i
			largestClusterSize = mc.count
		end
	end
	
	if largestClusterSize >= 2 then
		minionCluster = minionClusters[largestCluster]

		CastSpellToward(spell[1], minionCluster)
		-- if #spell >= 2 then
		--	CastSpellToward(spell[2], minionCluster)
		-- end
	end

	minions = nil
	minionClusters = nil
end

function RushDistance()
	if myHero.level < 6 or not Menu.extras.RushPounce then return end

	local points = wpm:GetWayPoints(myHero)

	if points and #points >= 2 then
		local endPoint = {x = points[#points].x, y = myHero.y, z = points[#points].y}
		local nextPoint = {x = points[2].x, y = myHero.y, z = points[2].y}

		local closestEnemy = FindClosestEnemy()

		--[[if JumpAssistant() and JumpAssistantIsNearRange() and GetDistance(endPoint) < 700 then return end -- Don't try to pounce when near a wall pounce point.]]--

		if RReady and currentForm and GetDistance(endPoint) > RushMinDistance and (not closestEnemy or GetDistance(closestEnemy) > 1000) then
			CastSpell(_R)
		end

		if WReady and not currentForm and GetDistance(endPoint) > 1000 and GetDistance(nextPoint) > 450 and (not closestEnemy or GetDistance(closestEnemy) > 800) then
			MoveAndCastPounce(endPoint)
			DelayAction(function(pos) myHero:MoveTo(pos.x, pos.z) end, 0.20, {endPoint})
		end
	end
end

function CastBushwhackonCCEnemy(enemy)
	if enemy and enemy.valid and not enemy.dead and not enemy.canMove then
		CastW(enemy)
		return true
	end

	for _, enemy in pairs(GetEnemyHeroes()) do
		if enemy and enemy.valid and not enemy.dead and not enemy.canMove then
			CastW(enemy)
			return true
		end
	end

	return false
end

function FindClosestEnemy()
	local closestEnemy = nil

	for _, enemy in pairs(GetEnemyHeroes()) do
		if enemy and enemy.valid and not enemy.dead then
			if not closestEnemy or GetDistance(enemy) < GetDistance(closestEnemy) then
				closestEnemy = enemy
			end
		end
	end

	return closestEnemy
end

function FindWeakestMinion()
	enemyMinions:update()
	tmp = nil
	for i,minionObject in ipairs(enemyMinions.objects) do
		if ValidTarget(minionObject, myHero.range) then
			tmp = minionObject
			break
		end
	end
	
	return tmp
end

function FarmMinion()
	enemyMinions:update()
	for _, minion in ipairs(enemyMinions.objects) do
		if ValidTarget(minion) then
			if getDmg("AD", minion, myHero) * 1.1 > minion.health then
				myHero:Attack(minion)
			end
		end
	end
end

function math.sign(x)
	if x < 0 then
		return -1
	elseif x > 0 then
		return 1
	else
		return 0
	end
end 

function AutoIgnite()
	if ignite and IReady and not myHero.dead then
		for _, enemy in ipairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				if enemy ~= nil and enemy.team ~= myHero.team and not enemy.dead and enemy.visible and GetDistance(enemy) < igniteRange and enemy.health < getDmg("IGNITE", enemy, myHero) then
					if Menu.extras.DoubleIgnite and not TargetHaveBuff("SummonerDot", enemy) then
						CastSpell(ignite, enemy)
					elseif not Menu.extras.DoubleIgnite then
						CastSpell(ignite, enemy)
					end
				end
			end
		end
	end
end

function IsIgnited(target)
	if TargetHaveBuff("SummonerDot", target) then
		igniteTick = GetTickCount()
		return true
	elseif igniteTick == nil or GetTickCount()-igniteTick>500 then
		return false
	end
end

function CheckSpells()
	CheckForm()
	Target = GetTarget()
	if Target ~= nil and Target ~= LastTarget then LastTarget = Target end

	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
	IReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end

function GetTarget()
	if IsSACReborn then
		return AutoCarry.Crosshair:GetTarget()
	else
		return AutoCarry.GetAttackTarget()
	end
end

function CheckForm()
	if myHero:GetSpellData(_Q).name == "JavelinToss" and not currentForm then
		currentForm = true
		UpdateSkillRanges()
		SetCrosshairRange(QRange)
	elseif myHero:GetSpellData(_Q).name == "Takedown" and currentForm then
		currentForm = false
		UpdateSkillRanges()
		SetCrosshairRange(WRange)
	end
end

function SetCrosshairRange(range)
	if IsSACReborn then
		AutoCarry.Crosshair:SetSkillCrosshairRange(range)
	else
		AutoCarry.SkillsCrosshair.range = range
	end
end

function UpdateSkillRanges()
	if currentForm then
		QRange = 1500
		WRange = 900
		ERange = 600
	else
		QRange = myHero.range + GetDistance(myHero, myHero.minBBox)
		WRange = 500
		ERange = 300
	end
end

function SpellAlerts()
	if Menu.draw.DrawSpellAlerts then
		if not currentForm then
			if IsSpellReady("Q") and not Alerted["Q"] then
				PrintAlert("Javelin is available!", 3, 50, 255, 50)
				Alerted["Q"] = true
			elseif IsSpellReady("W") and not Alerted["W"] then
				PrintAlert("Bushwhack is available!", 3, 50, 255, 50)
				Alerted["W"] = true
			elseif IsSpellReady("E") and not Alerted["E"] then
				PrintAlert("Heal is available!", 3, 50, 255, 50)
				Alerted["E"] = true
			end
		end
	end
end

function Heal()
	if CheckMana() then
		if not currentForm and Menu.healing.ForceHeal and not AutoCarry.MainMenu.AutoCarry and CheckHealth(myHero) and RReady and IsSpellReady("E") then
			CastSpell(_R)
			CheckSpells() -- Don't remove this. It's necessary here!
			if not RReady then CastE(myHero) end
			return
		else
			if currentForm and EReady then
				if CheckHealth(myHero) then
					if EReady then CastE(myHero) end
				elseif Menu.healing.healally.HealAllies then
					for i=1, heroManager.iCount do
						local ally = heroManager:GetHero(i)
						if ally.team == myHero.team and ally.name ~= myHero.name and Menu.healing.healally["heal"..i] and GetDistance(ally) <= 600 then
							if CheckHealth(ally) then
								if EReady then CastE(ally) end
							end
						end
					end
				end
			end
		end
	end
end

function OnAttacked()
	lastAttack = tick
	nextAttack = AutoCarry.GetNextAttackTime()
end

function IsBetweenAttacks()
	local betweenAttacks = (lastAttack == 0 or nextAttack > (tick + 300) or tick < (lastAttack + 200) or lastAttack < (tick - 4000)) or false

	if IsSACReborn then
		if Orbwalker:IsAfterAttack() and betweenAttacks then return true else return false end
	else
		return betweenAttacks
	end
end

function CheckMana()
	return myHero.mana >= myHero.maxMana * (Menu.extras.MinMana / 100)
end

function CheckHealth(unit)
	return unit.health <= unit.maxHealth * (Menu.healing.HealHealth / 100)
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved" then
			recall = true
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved" then
			recall = false
		end
	end
end

function GetDirection(enemy)
	if enemy and not enemy.dead then 
		local points = wpm:GetWayPoints(enemy)
		if points[1] == nil or points[2] == nil then return DIRECTION_UNKNOWN end 
		local d1 = GetDistanceSqr(points[1]) 
		local d2 = GetDistanceSqr(points[2]) 
		if d1 < d2 then 
			return DIRECTION_AWAY
		elseif d1 > d2 then 
			return DIRECTION_TOWARDS
		end 
	end 
end