<?php exit() ?>--by bothappy 83.46.99.170
--PRO Lee Sin by BotHappy, Entryway and Skeem
--Thanks to our Challenger Tester Nalle

--[[Changelog:
	v0.01:
		+Fixed P1 errors changing prodiction callback
		+Erased update stuff
	v0.02:
		+Added Orbwalk toggle to jungleclear
		+Added the option to attack without orbwalking on:
			Combo
			JungleClear
			Harass
	v0.03:
		+Added an option to WardJump at max range
	v0.04:
		+Added Solari + Randuins basic functions
	v0.05:
		+Reworked Buff functions, added QUnitLanded
		+QQSteal function done (Securing Jungle)
		+Erased PeelerCombo + Cleaning code
		+Reworked prodiction to callback.
		+Added Laneclear
	]]

if myHero.charName ~= "LeeSin" or not VIP_USER then return end

require "Prodiction"
require "Collision"

------------------------------------------------------
--					Basic Functions					--
------------------------------------------------------
function OnLoad() --Whatever you load when the game loads should be here.
    Variables()
	CheckIgnite()
	CheckFlash()
    Menu()
    PrintChat("<font color='#FFFFFF'> >> PROLeeSin "..Version.." loaded << </font>")
end

function OnTick() --This function is updated everyframe.
    Checks()
	AutoIgniteKS()
    if PROLeeSin.othersettings.ksR then KSwithR() end
	if ValidTarget(ts.target) then
		local ksSuccess = false
		if PROLeeSin.combosettings.combo then 
			if PROLeeSin.smartcombosettings.scombo then
				ksSuccess = autoKs(ts.target)
			end
			if not PROLeeSin.smartcombosettings.scombo or not ksSuccess then CastCombo(ts.target) end
		end
		if PROLeeSin.harasssettings.harass then
			if ComboHarassNear then 
				HarassNear(ts.target)
			else--if (#(allyMinions.objects) > 0) then
				HarassFar(ts.target) 
			--elseif PROLeeSin.harasssettings.UseMoveToMouse then
			--	MoveToCursor()
			end
		end
	elseif not ValidTarget(ts.target) then
		if PROLeeSin.combosettings.combo and PROLeeSin.combosettings.OrbWalk then MoveToCursor() end
		if PROLeeSin.harasssettings.harass and PROLeeSin.harasssettings.UseMoveToMouse then MoveToCursor() end -- and (#(allyMinions.objects) == 0)
	end
	if ValidTarget(tsInsec.target) then
		if PROLeeSin.insecsettings.insec then
			if UseFlash then
				InsecFlash(tsInsec.target)
			else
				Insec(tsInsec.target)
			end
		end
	elseif not ValidTarget(tsInsec.target) then 
		if PROLeeSin.insecsettings.insec and PROLeeSin.insecsettings.UseMoveToMouse then MoveToCursor() end
	end
    if PROLeeSin.othersettings.wjump then WardJump() end
	if PROLeeSin.junglesettings.jungle then JungleClear() end
	if PROLeeSin.junglesettings.secure then QQSteal() end
	if PROLeeSin.junglesettings.laneclear then LaneClear() end
	Resets(ts.target)
end

function OnDraw() --Draws, updated everyframe
    if PROLeeSin.drawsettings.drawQ then
        if Q1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, q1Range, ARGB(255,127,0,110))
        elseif Q2Ready then
        	DrawCircle(myHero.x, myHero.y, myHero.z, q2Range, ARGB(255,127,0,110))
        end
    end
    if PROLeeSin.drawsettings.drawW then
        if W1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, wRange, ARGB(255,95,159,159))
        end
    end
    if PROLeeSin.drawsettings.drawE then
        if E1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, e1Range, ARGB(255,204,50,50))
        elseif E2Ready then
        	DrawCircle(myHero.x, myHero.y, myHero.z, e2Range, ARGB(255,204,50,50))
        end
    end
    if PROLeeSin.drawsettings.drawR then
        if RReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGB(255,69,139,0))
        end
    end
    if PROLeeSin.drawsettings.texts then KillDraws() end
	--Another draws (Optional ones)
	if PROLeeSin.drawsettings.WardHelper and PROLeeSin.othersettings.wjump then
		DrawCircle(myHero.x, myHero.y, myHero.z, WardRange, ARGB(255,255,50,51))
		HelperPos = FindNearestNonWall(mousePos.x, mousePos.y, mousePos.z, WardRange, 20)
		if HelperPos then
			if GetDistance(mousePos) < WardRange then
				DrawCircle3D(HelperPos.x, HelperPos.y, HelperPos.z, 50, 2, ARGB(255, 255, 255, 255), 20)
			else
				DrawCircle3D(HelperPos.x, HelperPos.y, HelperPos.z, 50, 2, ARGB(255, 255, 0, 0), 20)
			end
		end
	end
	if not RReady and PROLeeSin.insecsettings.insec then
		local barPos = WorldToScreen(D3DXVECTOR3(myHero.x, myHero.y, myHero.z)) --(Credit to Zikkah)
		local PosX = barPos.x - 35
		local PosY = barPos.y - 50 
		DrawText("R on Cooldown", 13, PosX, PosY, 0xFFFFE303) --Could give errors, should change to DrawText3D
	end
	if PROLeeSin.drawsettings.circles then
		if ValidTarget(ts.target, q2Range) then DrawCircle(ts.target.x, ts.target.y, ts.target.z, 35, ARGB(255,198,239,247)) end
		if ValidTarget(tsInsec.target, q2Range) then DrawCircle(tsInsec.target.x, tsInsec.target.y, tsInsec.target.z, 40, ARGB(255,198,239,247)) end
	end
end

------------------------------------------------------
--					Combo Functions					--
------------------------------------------------------

function CastCombo(Target) 
	if PROLeeSin.combosettings.OrbWalk then 
		OrbWalking(Target)
	else
		if ValidTarget(Target) and TimeToAttack() then
			myHero:Attack(Target)
		end
	end
	UseItems(Target)
	
	--Gapcloser if far + JumpWarded, or Target is far
	if GetDistance(Target) > 450 and Q1Ready and CurrentPassive then
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	end
	--If Near, start with E to maximize dmg. If not, start with Q.
	if GetDistance(Target) <= e1Range and E1Ready and not CurrentPassive then
		CastSpellAttack(E1Ready, e1Range, _E, Target)
	elseif Q1Ready and ValidTarget(Target, q1Range) and not CurrentPassive then 
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	end
	--If QLanded, if E Range, will try to E. If not, just Q. If far, 2ndQ too.
	if Q2Ready and QLanded and ValidTarget(Target, q2Range) then
		if ValidTarget(Target, e1Range) and not CurrentPassive and E1Ready then
			CastSpellAttack(E1Ready, e1Range, _E, Target)
		elseif not CurrentPassive and ValidTarget(Target, e1Range) then
			CastSpellAttack(Q2Ready, q2Range, _Q, Target, QLanded)
		elseif ValidTarget(Target, q2Range) then
			CastSpellAttack(Q2Ready, q2Range, _Q, Target, QLanded)
		end
	end
	--2nd E Passive usage
	if E2Ready and PROLeeSin.combosettings.use2ndE and ELanded and ValidTarget(Target, e2Range) and GetDistance(Target) > 500 then
		CastSpell(_E)
	end 
	--If near to the enemy and UseW, use W two times
	if PROLeeSin.combosettings.useW then
		if W1Ready and not CurrentPassive then 
			CastSpellAttack(W1Ready, 250, _W, Target)
		elseif W2Ready and not CurrentPassive then	
			CastSpellAttack(W2Ready, 250, _W, Target)
		end
	end
end

function HarassFar(Target)
	if PROLeeSin.harasssettings.UseMoveToMouse then MoveToCursor() end
	if myHero.mana >= 120 and W1Ready and Q1Ready and ValidTarget(Target, q1Range) then 
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	end
	if Q2Ready and ValidTarget(Target, q2Range) and QLanded then 
		CastSpell(_Q) 
		QHarass= true
	end
	if PROLeeSin.harasssettings.useE and E1Ready and myHero.mana >=100 then
		if QHarass and GetDistance(myHero, Target) < 100 then 
			CastSpell(_E)
			EHarass = true
		end
		if EHarass and W1Ready then 
			DelayAction(function() AutoWMinion() end, 0.5) 
		end
	elseif not EHarass and W1Ready and QHarass and GetDistance(myHero, Target) < GetHitBoxRadius(Target)*0.2 then 
		DelayAction(function() AutoWMinion() end, 0.5)
	end
end

function HarassNear(Target) --Testing
	if PROLeeSin.harasssettings.UseMoveToMouse then 
		OrbWalking(Target) 
	else
		if ValidTarget(Target) and TimeToAttack() then
			myHero:Attack(Target)
		end
	end
	if E1Ready and ValidTarget(Target,e1Range) then
		CastSpell(_E)
	elseif E2Ready and ValidTarget(Target,e2Range) and not HarassCurrentPassive then
		CastSpell(_E)
	elseif ValidTarget(Target,q1Range) and Q1Ready and not HarassCurrentPassive then
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	elseif ValidTarget(Target,q2Range) and Q2Ready and QLanded and not HarassCurrentPassive then
		CastSpell(_Q)
		QHarass= true
	end
	if QHarass and W1Ready then 
		DelayAction(function() AutoWMinion() end, 0.5) 
	end
end

function Insec(Target)
	local CastWardInsec= Vector(0,0,0)
	if not RReady or RCasted then return end
	local hero = nil
    if Q1Ready and PROLeeSin.insecsettings.UseMoveToMouse then MoveToCursor() end
    if Q1Ready and myHero.mana > 125 and W1Ready and RReady and GotWard and ValidTarget(Target, q1Range) then 
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	end
    if Q2Ready and ValidTarget(Target, q2Range) and QInsecLanded then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
        CastSpell(_Q)
    end
	if MyPos ~= nil then
		if PROLeeSin.insecsettings.ToAllies and CountAllyHeroInRange(1200, myHero) > 0 and AllyWithMoreAlliesNear(1200) ~= nil then
			hero = AllyWithMoreAlliesNear(1200)
			CastWardInsec = Target + (Vector(Target.x, Target.y, Target.z) - Vector(hero.x,hero.y,hero.z)):normalized()*250
		elseif (CountAllyHeroInRange(1200, myHero) == 0 or AllyWithMoreAlliesNear(1200) == nil or not PROLeeSin.insecsettings.ToAllies) then
			CastWardInsec = Target + (Vector(Target.x, Target.y, Target.z) - MyPos):normalized()*250
		end
	end
	
	if W1Ready and RReady and GotWard and not (Q1Ready and Q2Ready) and GetDistance(myHero, Target) < 150 then
		if RSStoneReady and not WardInsecReady then
			CastSpell(RSStoneSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		elseif SStoneReady and not WardInsecReady then
			CastSpell(SStoneSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		elseif TrinketReady and not WardInsecReady then
			CastSpell(ITEM_7, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		elseif WrigglesReady and not WardInsecReady then
			CastSpell(WrigglesSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		elseif SWardReady and not WardInsecReady then
			CastSpell(SWardSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		elseif VWardReady and not WardInsecReady then
			CastSpell(VWardSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
		end
	end
	if W1Ready and RReady and WardInsecReady then
		for _, Ward in ipairs(InsecWardTable) do
			if GetDistance(Ward) < wRange then
				CastOnObject(_W, Ward) --Sometimes it doesn't jump to the ward
				JumpdToWard = true
			end
		end
	end
	if RReady and JumpdToWard then-- and ValidTarget(Target, rRange) then
		DelayAction(function() CastOnObject(_R, Target) end, math.abs(0.2-(GetLatency()*0.001)))
	end
end

function InsecFlash(Target)
	if not RReady or JumpdToWard then return end
	local hero = nil
	if Q1Ready and PROLeeSin.insecsettings.UseMoveToMouse then MoveToCursor() end
	if ValidTarget(Target, q1Range) and Q1Ready and RReady and FlashReady then
		ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
	end
	if Q2Ready and ValidTarget(Target, q2Range) and QInsecLanded then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
        CastSpell(_Q)
    end
	if MyPos ~= nil then
		if PROLeeSin.insecsettings.ToAllies and CountAllyHeroInRange(1300, myHero) > 0 and AllyWithMoreAlliesNear(1300) ~= nil then
			hero = AllyWithMoreAlliesNear(1300)
			CastFlash = Target + (Vector(Target.x, Target.y, Target.z) - Vector(hero.x,hero.y,hero.z)):normalized()*250
		elseif (CountAllyHeroInRange(1300, myHero) == 0 or AllyWithMoreAlliesNear(1300) == nil or not PROLeeSin.insecsettings.ToAllies) then
			CastFlash = Target + (Vector(Target.x, Target.y, Target.z) - MyPos):normalized()*250
		end
	end
	if RReady and GetDistance(Target) < 250 then
		RCasted = true
		CastOnObject(_R, Target)
	end
end

function autoKs(Target)
	--Damages for autoKS
	local qKSDmg = ((Q1Ready and getDmg("Q", Target, myHero, 1)) or 0)
	local eKSDmg = ((E1Ready and getDmg("E", Target, myHero)) or 0)
	local rKSDmg = ((RReady and getDmg("R", Target, myHero)) or 0)
	local iKSDmg = ((IgniteReady and getDmg("IGNITE", Target, myHero)) or 0)

	--Mana Calculations
	local qMana = 50
	local q2Mana = 30
	local eMana = 50
	local rMana = 0

	OrbWalking(Target)
	--Killsteal Combos
	if ValidTarget(Target) then
		--1st Q allready hit
		if QLanded and Q2Ready and myHero.mana >= q2Mana then
			-- Only 2nd Q
			if GetDistance(Target) <= q2Range then
				if Target.health <= q2ComboDmg(0, Target) then
					CastSpell(_Q)
					return true
				elseif  Target.health <= q2ComboDmg(0, Target) + iKSDmg then
					CastSpell(_Q)
					DelayAction(function()CastSpell(IgniteSlot, Target) end, 0.5)
					return true
				end
			end
			-- If our E is ready, then check for these KS combos
			if E1Ready and myHero.mana >= (q2Mana + eMana) then
				if GetDistance(Target) <= e1Range and Target.health <= eKSDmg + q2ComboDmg(eKSDmg, Target) then
					CastSpell(_E)
					DelayAction(function()CastSpell(_Q) end, 0.3)
					return true
				elseif GetDistance(Target) > e1Range and Target.health <= eKSDmg + q2ComboDmg(0, Target) then
					CastSpell(_Q)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) <= e1Range and Target.health <= eKSDmg + iKSDmg + q2ComboDmg(eKSDmg, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_E, Target) end, 0.3)
					DelayAction(function()CastSpell(_Q) end, 0.5)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) > e1Range and Target.health <= eKSDmg + iKSDmg + q2ComboDmg(0, Target) then
					CastSpell(_Q)
					DelayAction(function()CastSpell(IgniteSlot, Target) end, 0.5)
					return true
				end
			end
			-- If our R is ready, then check for these KS combos
			if RReady and myHero.mana >= (q2Mana + rMana) then 
				if GetDistance(Target) <= rRange and Target.health <= rKSDmg + q2ComboDmg(rKSDmg, Target) then
					CastOnObject(_R, Target)
					DelayAction(function()CastSpell(_Q) end, 0.5)
					return true
				elseif GetDistance(Target) > rRange and Target.health <= rKSDmg + q2ComboDmg(0, Target) then
					CastSpell(_Q)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) <= rRange and Target.health <= rKSDmg + iKSDmg + q2ComboDmg(rKSDmg, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_R, Target) end, 0.3)
					DelayAction(function()CastSpell(_Q) end, 0.8)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) > rRange and Target.health <= rKSDmg + iKSDmg + q2ComboDmg(0, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_Q, Target) end, 0.3)
					return true
				end
			end
			--If our R and E is ready, then check for these KS combos -- Have to check for eRange and rRange later .... needs testing
			if RReady and E1Ready and myHero.mana >= (q2Mana + eMana + rMana) then
				if GetDistance(Target) <= e1Range and Target.health <= rKSDmg + eKSDmg + q2ComboDmg(rKSDmg + eKSDmg, Target) then
					CastSpell(_E)
					DelayAction(function()CastSpell(_R, Target) end, 0.3)
					DelayAction(function()CastSpell(_Q) end, 0.8)
					return true
				elseif GetDistance(Target) <= rRange and Target.health <= rKSDmg + eKSDmg + q2ComboDmg(rKSDmg, Target) then
					CastOnObject(_R, Target)
					DelayAction(function()CastSpell(_Q) end, 0.5)
					return true
				elseif GetDistance(Target) > rRange and Target.health <= rKSDmg + eKSDmg + q2ComboDmg(0, Target) then
					CastSpell(_Q)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) <= e1Range and Target.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(rKSDmg + eKSDmg, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_E) end, 0.3)
					DelayAction(function()CastSpell(_R, Target) end, 0.5)
					DelayAction(function()CastSpell(_Q) end, 1.0)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) <= rRange and Target.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(rKSDmg, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_R, Target) end, 0.3)
					DelayAction(function()CastSpell(_Q) end, 0.8)
					return true
				elseif iKSDmg > 0 and GetDistance(Target) > rRange and Target.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(0, Target) then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_Q) end, 0.3)
					return true
				end
			end
		-- We didn't use or hit our 1st Q
		else
			-- If our E is ready, check if we can kill with E or E + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
			if E1Ready and GetDistance(Target) <= e1Range then
				if Target.health <= eKSDmg then 
					CastSpell(_E)
					return true
				elseif Target.health <= eKSDmg + iKSDmg then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_E, Target) end, 0.3)
					return true
				end
			end
			-- If our R is ready, check if we can kill with R or R + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
			if RReady and GetDistance(Target) <= rRange then
				if Target.health  <= rKSDmg then
					if E1Ready and Target.health > eKSDmg then
						return true
					elseif Q1Ready and Target.health > qKSDmg then
						return
					else
						CastOnObject(_R, Target)
						return true
					end
				elseif Target.health <= rKSDmg + iKSDmg then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_R, Target) end, 0.3)
					return true
				end
			end
			-- If our E and R is ready, check if we can kill with E + R or E + R + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
			if RReady and E1Ready and GetDistance(Target) <= e1Range then
				if Target.health <= eKSDmg + rKSDmg then
					CastSpell(_E)
					DelayAction(function()CastSpell(_R, Target) end, 0.3)
					return true
				elseif Target.health <= eKSDmg + rKSDmg + iKSDmg then
					CastSpell(IgniteSlot, Target)
					DelayAction(function()CastSpell(_E) end, 0.3)
					DelayAction(function()CastSpell(_R, Target) end, 0.5)
					return true
				end
			end
			-- If our Q is ready, check for these. If we hit Q, we're looping back automaticaly to the part before the elseif(if QLanded then)
			if Q1Ready and GetDistance(Target) <= q1Range then
				if Target.health <= qKSDmg + iKSDmg and myHero.mana <= qMana then 
					ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
					return true
				end
				if Target.health <= qKSDmg + iKSDmg + q2ComboDmg(qKSDmg, Target) and myHero.mana >= (qMana + q2Mana) then
					ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
					return true
				end

				--If our E is ready too, then ...
				if E1Ready then
					if GetDistance(Target) <= e1Range and Target.health <= qKSDmg + eKSDmg + iKSDmg and myHero.mana >= (qMana + eMana) then
						CastSpell(_E)
						DelayAction(function() ProdictQ:GetPredictionCallBack(Target, CastQCallBack) end, 0.3)
						return true
					end
					if GetDistance(Target) <= e1Range and Target.health <= qKSDmg + eKSDmg + iKSDmg + q2ComboDmg(qKSDmg + eKSDmg, Target) and myHero.mana >= (qMana + eMana + q2Mana) then
						CastSpell(_E)
						DelayAction(function() ProdictQ:GetPredictionCallBack(Target, CastQCallBack) end, 0.3)
						return true
					end
					if GetDistance(Target) > e1Range and Target.health <= qKSDmg + eKSDmg + iKSDmg + q2ComboDmg(qKSDmg, Target) and myHero.mana >=  (qMana + eMana + q2Mana) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
				end
				-- If our R is ready, then ...
				if RReady then
					if GetDistance(Target) <= rRange and Target.health <= qKSDmg + rKSDmg + iKSDmg and myHero.mana >= qMana then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
					if GetDistance(Target) <= rRange and Target.health <= qKSDmg + rKSDmg + iKSDmg + q2ComboDmg(qKSDmg + rKSDmg, Target) and myHero.mana >= (qMana + q2Mana) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
					if GetDistance(Target) > rRange and Target.health <= qKSDmg + rKSDmg + iKSDmg + q2ComboDmg(qKSDmg, Target) and myHero.mana >= (qMana + q2Mana) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
				end
				--If our E and R is ready, then .... (we're looping back to if E + R ready and Qlanded, no need for checks here)
				if RReady and E1Ready then
					if Target.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + eKSDmg, Target) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
					if Target.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + rKSDmg, Target) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
					if Target.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + eKSDmg + rKSDmg, Target) then
						ProdictQ:GetPredictionCallBack(Target, CastQCallBack)
						return true
					end
				end
			end
		end
	end

	return false
end


function CastSpellAttack(SpellReady, SpellRange, Spell, Target, Optional, TargetSpell)
    if Optional == nil then Optional = true end

    if SpellReady and ValidTarget(Target, SpellRange) and Optional then
        if TimeToAttack() and ValidTarget(Target,250) then
			if TargetSpell ~= nil and Spell == _Q then
				DelayAction(function() ProdictQ:GetPredictionCallBack(TargetSpell, CastQCallBack) end, 0.3)
			else
				DelayAction(function() CastSpell(Spell) end, 0.3)
			end
            return true
        else
            if ValidTarget(TargetSpell) and Spell == _Q then
				ProdictQ:GetPredictionCallBack(TargetSpell, CastQCallBack)
			else
				CastSpell(Spell)
			end
            return true
        end
    end
    return false
end

function AutoWMinion() --Thanks to Skeem
	MinionTable = DeepCopyTable(allyMinions.objects)
	table.sort(MinionTable, function(x,y) return GetDistance(x) > GetDistance(y) end)
	for _, wMinion in ipairs(MinionTable) do
		if GetDistance(myHero, wMinion) < wRange then
			CastOnObject(_W, wMinion)
		end
	end
end

function LaneClear()
	for _, Enemy in ipairs(enemyMinions.objects) do
		if ValidTarget(Enemy) and GetDistance(Enemy) <= SmiteRange then
			--add an option to orbwalk
			if PROLeeSin.junglesettings.Orbwalk then
				if ValidTarget(Enemy) then
					OrbWalking(Enemy)
				else
					MoveToCursor()
				end
			else
				if ValidTarget(Enemy) and TimeToAttack() then
					myHero:Attack(Enemy)
				end
			end
			if (E1Ready or E2Ready) and GetDistance(Enemy) < e1Range then CastSpell(_E)  end
			if (Q1Ready or Q2Ready) then CastSpell(_Q, Enemy.x, Enemy.z) end
			if not (Q1Ready or Q2Ready or E1Ready or E2Ready) and (W1Ready or W2Ready) then CastSpell(_W) end
			if TiamatReady and GetDistance(Enemy) < 400 then CastSpell(TiamatSlot) end
			if HydraReady and GetDistance(Enemy) < 400 then CastSpell(HydraSlot) end
		end
	end
end

------------------------------------------------------
--					Orbwalk Functions				--
------------------------------------------------------
--Based on Manciuzz Orbwalker http://pastebin.com/jufCeE0e

function OrbWalking(Unit)
	if ValidTarget(Unit) and TimeToAttack() and GetDistance(Unit) <= TrueRange(myHero) then
		myHero:Attack(Unit)
	elseif HeroCanMove() then
		MoveToCursor()
	end
end

function TrueRange(Unit)
	return Unit.range + GetDistance(Unit.minBBox)
end

function TimeToAttack()
	return (GetTickCount() + GetLatency()*0.5 > lastAttack + lastAttackCD)
end

function HeroCanMove()
	return (GetTickCount() + GetLatency()*0.5 > lastAttack + lastWindUpTime + 20)
end

function MoveToCursor()
	if GetDistance(mousePos) > 1 or LastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		Packet('S_MOVE', {x = moveToPos.x, y = moveToPos.z}):send()
	end	
end

------------------------------------------------------
--					Auxiliary Functions				--
------------------------------------------------------

function Variables()
	--GameVariables
	gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end
	--Prodiction Values
	q1Range, qSpeed, qDelay, qWidth = 975, 1700, 0.250, 60

	--Lee Sin Variables
    q2Range, wRange, e1Range, e2Range, rRange = 1300, 700, 425, 600, 375
	
	--Damages
	TrinitySlot, SheenSlot, BWCSlot, BotrkSlot, YoumuSlot, HydraSlot, EntropySlot = nil, nil, nil, nil, nil, nil, nil
	q1Dmg, q2Dmg, eDmg, AADmg, IgniteDmg, rDmg = 0,0,0,0,0,0
	SheenDmg, BWCDmg, TrinityDmg, BotrkDmg, HydraDmg, TiamatDmg, EntropyDmg = 0,0,0,0,0,0,0
	BurstCombo1, BurstCombo2, Combo = 0,0,0

    --Prodiction
    Prodict = ProdictManager.GetInstance()
    ProdictQ = Prodict:AddProdictionObject(_Q, q1Range, qSpeed, qDelay, qWidth)
	ProdictQCollision = Collision(q1Range, qSpeed, qDelay, qWidth)

    --Priority Table of TS -> Based on Manciuszz http://pastebin.com/0mzbDAvv
    priorityTable = {
    AP = {
        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
    		},
    Support = {
        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
    			},
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Nautilus", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac",
    		},
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MasterYi", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon","Tryndamere", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Yasuo","Zed", 
    			},
    Bruiser = {
        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nocturne", "Olaf", "Poppy",
	    "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao",
			},
						}

	--TS
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, q2Range, DAMAGE_PHYSICAL, true)
	tsInsec = TargetSelector(TARGET_PRIORITY, q2Range, DAMAGE_PHYSICAL, true)
    ts.name = "LeeSin"


	--Jungle Variables
	JungleMobs = {}
	JungleFocusMobs = {}
	JungleCurrentPassive = false
	SmiteRange = 600
	SmiteSlot = nil
	SmiteDmg = 0
	SmiteReady = false
	QUnitLanded = nil
	
	q1DmgMob, q2SmiteDmgMob, q2DmgMob = 0,0,0
	
	if TTMAP then --
		FocusJungleNames = {
		["TT_NWraith1.1.1"] = true,
		["TT_NGolem2.1.1"] = true,
		["TT_NWolf3.1.1"] = true,
		["TT_NWraith4.1.1"] = true,
		["TT_NGolem5.1.1"] = true,
		["TT_NWolf6.1.1"] = true,
		["TT_Spiderboss8.1.1"] = true,
							}
		
		JungleMobNames = {
        ["TT_NWraith21.1.2"] = true,
        ["TT_NWraith21.1.3"] = true,
        ["TT_NGolem22.1.2"] = true,
        ["TT_NWolf23.1.2"] = true,
        ["TT_NWolf23.1.3"] = true,
        ["TT_NWraith24.1.2"] = true,
        ["TT_NWraith24.1.3"] = true,
        ["TT_NGolem25.1.1"] = true,
        ["TT_NWolf26.1.2"] = true,
        ["TT_NWolf26.1.3"] = true,
						}
	else 
	JungleMobNames = { 
        ["Wolf8.1.2"] = true,
        ["Wolf8.1.3"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.3"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["Wolf2.1.2"] = true,
        ["Wolf2.1.3"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.3"] = true,
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
	end
	DragonRDY, VilemawRDY, NashorRDY = false, false, false
	CheckSmite()

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
	
	--Minions, Allies and Enemies
	EnemyTable = {}
	EnemysInTable = 0
    enemyHeroes = GetEnemyHeroes()
	allyHeroes = GetAllyHeroes()
	allyMinions = minionManager(MINION_ALLY, wRange, player, MINION_SORT_HEALTH_ASC)
	enemyMinions = minionManager(MINION_ENEMY, SmiteRange, player, MINION_SORT_HEALTH_ASC)

	units = {}
	
	--Priority Table Use
	if heroManager.iCount < 10 then -- borrowed from Sidas Auto Carry, modified to 3v3
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end

	--Orbwalking Variables
    NextTick = 0
	LastAnimation = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
    
	--Items
	--Randuins Omen : 500 Range and the id: 3143
    items = -- With entropy
    {
        BRK = {id=3153, range = 500, reqTarget = true, slot = nil },
        ETP = {id=3184, range = 350, reqTarget = true, slot = nil },
        BWC = {id=3144, range = 400, reqTarget = true, slot = nil },
        DFG = {id=3128, range = 750, reqTarget = true, slot = nil },
        HGB = {id=3146, range = 400, reqTarget = true, slot = nil },
        STD = {id=3131, range = 350, reqTarget = false, slot = nil},
		RSH = {id=3074, range = 350, reqTarget = false, slot = nil},
        TMT = {id=3077, range = 350, reqTarget = false, slot = nil},
        YGB = {id=3142, range = 350, reqTarget = false, slot = nil}
    }
	
	--WardJump Variables
	WardTable = {}
	InsecWardTable = {}
	WardReady = false

	--Trinkets ID = 3340,3350,3361,3362} --Warding Totem, Greater Totem, Greater Stealth Totem, Greater Vision Totem
	SWard, VWard, SStone, RSStone, Wriggles = 2044, 2043, 2049, 2045, 3154
	WTotem, GTotem, GSTotem, GVTotem = 3340, 3350, 3361, 3362
	SWardSlot, VWardSlot, SStoneSlot, RSStoneSlot, WrigglesSlot = nil, nil, nil, nil, nil
	RSStoneReady, SStoneReady, SWardReady, VWardReady, WrigglesReady, TrinketReady = false, false, false, false, false, false
	WardRange = 600
	
	--Function Aux Helpers

	QLanded = false
	--ESlow = false
	Passive = false
	PassiveStacks = 0
	CurrentPassive = false
	
	QHarass = false
	EHarass = false
	HarassCurrentPassive = false
	ComboHarassNear = false

	WardInsecReady = false
	JumpdToWard = false
	QInsecLanded = false
	UseFlash = false

	PeelerEReady = false

	FlashSlot = nil
	IgniteSlot = nil
	CastFlash = Vector(0,0,0)
	
	HelperPos = nil

	Spells = {
			"AbsoluteZero", "AlZaharNetherGrasp", "CaitlynAceintheHole", "Crowstorm" ,--"DrainChannel", 
			"FallenOne", "GalioIdolOfDurand", "InfiniteDuress","KatarinaR","MissFortuneBulletTime",
			"Teleport","Pantheon_GrandSkyfall_Jump", "ShenStandUnited",	"UrgotSwap2",
				}
	
	Version = "1.01"

	for i=1, heroManager.iCount do
		local champ = heroManager:GetHero(i)
		if champ.team ~= myHero.team then
			EnemysInTable = EnemysInTable + 1
			EnemyTable[EnemysInTable] = { hero = champ, Name = champ.charName, DisplayText = "", Ready = true, CDText = "Wait for CDs"}
		end
	end
end

function Checks()
	--Spell Checks
    Q1Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "BlindMonkQOne")
	Q2Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "blindmonkqtwo")
	W1Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "BlindMonkWOne")
	W2Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "blindmonkwtwo")
    E1Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "BlindMonkEOne")
	E2Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "blindmonketwo")
    RReady = (myHero:CanUseSpell(_R) == READY)

    --Spell CDs
 	-- QCurrentCD = myHero:GetSpellData(_Q).currentCd
	-- WCurrentCD = myHero:GetSpellData(_E).currentCd
	-- ECurrentCD = myHero:GetSpellData(_E).currentCd
	-- RCurrentCD = myHero:GetSpellData(_R).currentCd

	--Summoner Checks
    IgniteReady = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
	SmiteReady = (SmiteSlot ~= nil and myHero:CanUseSpell(SmiteSlot) == READY)
	FlashReady = (FlashSlot ~= nil and myHero:CanUseSpell(FlashSlot) == READY)
    
	--ItemSlot Checks
    TrinitySlot = GetInventorySlotItem(3078)
    SheenSlot = GetInventorySlotItem(3057)
    BCWSlot = GetInventorySlotItem(3144)
    BotrkSlot = GetInventorySlotItem(3153)
    YoumuSlot = GetInventorySlotItem(3142)
    TiamatSlot = GetInventorySlotItem(3077)
    HydraSlot = GetInventorySlotItem(3074)
    EntropySlot = GetInventorySlotItem(3184)

	--Ward Check
	SWardSlot = GetInventorySlotItem(SWard)
	VWardSlot = GetInventorySlotItem(VWard)
	SStoneSlot = GetInventorySlotItem(SStone) 
	RSStoneSlot = GetInventorySlotItem(RSStone)
	WrigglesSlot = GetInventorySlotItem(Wriggles)

	--Ward Checks
	RSStoneReady = (RSStoneSlot ~= nil and CanUseSpell(RSStoneSlot) == READY)
	SStoneReady = (SStoneSlot ~= nil and CanUseSpell(SStoneSlot) == READY)
	SWardReady = (SWardSlot ~= nil and CanUseSpell(SWardSlot) == READY)
	VWardReady = (VWardSlot ~= nil and CanUseSpell(VWardSlot) == READY)
	WrigglesReady = (WrigglesSlot ~= nil and CanUseSpell(WrigglesSlot) == READY)
	TrinketReady = (myHero:CanUseSpell(ITEM_7) == READY and myHero:getItem(ITEM_7).id == 3340) or (myHero:CanUseSpell(ITEM_7) == READY and myHero:getItem(ITEM_7).id == 3350) or (myHero:CanUseSpell(ITEM_7) == READY and myHero:getItem(ITEM_7).id == 3361) or (myHero:CanUseSpell(ITEM_7) == READY and myHero:getItem(ITEM_7).id == 3362)

	--Got a ward to place to jump
	GotWard = WrigglesReady or RSStoneReady or SStoneReady or SWardReady or VWardReady or TrinketReady

    --Item Checks
    BCWReady = (BCWSlot~= nil and myHero:CanUseSpell(BCWSlot) == READY)
    BotrkReady = (BotrkSlot ~= nil and myHero:CanUseSpell(BotrkSlot) == READY)
    YoumuReady = (YoumuSlot ~= nil and myHero:CanUseSpell(YoumuSlot) == READY)
    TiamatReady = (TiamatSlot ~= nil and myHero:CanUseSpell(TiamatSlot) == READY)
    HydraReady = (HydraSlot ~= nil and myHero:CanUseSpell(HydraSlot) == READY)
    EntropyReady = (EntropySlot ~= nil and myHero:CanUseSpell(EntropySlot) == READY)

    --Other Checks
    GetDamages()
	JungleDmgs()
    ts:update()
	tsInsec:update()
	allyMinions:update()
	enemyMinions:update()

	if PROLeeSin.drawsettings.FreeLagCircles then
		_G.DrawCircle = DrawCircle2
	end
end

function Resets(Target)
	if Passive then
		--Combo Passive
		if PassiveStacks > (2-PROLeeSin.combosettings.passivestacks) then
			CurrentPassive = true
		else
			CurrentPassive = false
		end
		--Jungle Passive
		if PassiveStacks > (2-PROLeeSin.junglesettings.passivestacks) then
			JungleCurrentPassive = true
		else
			JungleCurrentPassive = false
		end
		--Harass Passive
		if PassiveStacks > (2-PROLeeSin.harasssettings.passivestacks) then
			HarassCurrentPassive = true
		else
			HarassCurrentPassive = false
		end
	elseif not Passive then
			JungleCurrentPassive = false
			CurrentPassive = false
			HarassCurrentPassive = false
	end
	--To reset insec
	if not PROLeeSin.insecsettings.insec then
		JumpdToWard = false
		WardInsecReady = false
		for k,v in pairs(InsecWardTable) do 
			InsecWardTable[k]=nil 
		end
		RCasted = false
		UseFlash = (PROLeeSin.insecsettings.flash and ((PROLeeSin.insecsettings.priority == 1 and FlashReady) or (PROLeeSin.insecsettings.priority == 0 and not GotWard and FlashReady)))
	end
	
	--Harass Settings
	if PROLeeSin.harasssettings.harass then 
		if Q1Ready then 
			QHarass = false 
		end
		if E1Ready then 
			EHarass = false 
		end
	elseif not PROLeeSin.harasssettings.harass then
		if ValidTarget(Target, e1Range) then
			ComboHarassNear = myHero.mana > 130
		end
	end

	if not PROLeeSin.othersettings.wjump then
		for k,v in pairs(WardTable) do WardTable[k]=nil end
		WardReady = false
	end
	--if not PROLeeSin.peelersettings.pcombo then
	--	PeelerEReady = myHero.mana > 130
	--end
end

function CheckIgnite()
    if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then IgniteSlot = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then IgniteSlot = SUMMONER_2
    end
end

function CheckFlash()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then FlashSlot = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then FlashSlot = SUMMONER_2
	end
end

function GetHitBoxRadius(Unit)
	return GetDistance(Unit, Unit.minBBox)
end

function DeepCopyTable(orig) --To copy a whole table
    local orig_type = type(orig)
    local copy
    if orig_type == 'table' then
        copy = {}
        for orig_key, orig_value in next, orig, nil do
            copy[DeepCopyTable(orig_key)] = DeepCopyTable(orig_value)
        end
        setmetatable(copy, DeepCopyTable(getmetatable(orig)))
    else -- number, string, boolean, etc
        copy = orig
    end
    return copy
end

function q2ComboDmg(Damage, Target)
    if Damage == nil then Damage = 0 end
    local PredHealth = Target.health - Damage
    local q2Dmg = ((myHero:GetSpellData(_Q).level*30) + 20) + (myHero.addDamage*0.9) + (0.08*(Target.maxHealth-PredHealth))
    return myHero:CalcDamage(Target, q2Dmg)
end

function CastQCallBack(unit, pos)
    if ValidTarget(unit) and pos ~= nil then
    	local willCollide = ProdictQCollision:GetMinionCollision(myHero, pos)
   		if not willCollide then CastSpell(_Q, pos.x, pos.z) end
    end
end

function AllyWithMoreAlliesNear(Range) --It gets the hero with more heroes around and returns that object
	local CurrentNumber = 0
	local AllyInRange = nil
    for i = 1, heroManager.iCount, 1 do
        local Hero = heroManager:getHero(i)
        if Hero ~=nil and GetDistance(Hero) <Range and Hero.team == myHero.team and Hero.networkID ~= myHero.networkID then
            if CountAllyHeroInRange(Range, Hero) > CurrentNumber then
				CurrentNumber = CountAllyHeroInRange(Range, Hero)
				AllyInRange = Hero
			end
		end
    end
	return AllyInRange
end

function CountAllyHeroInRange(Range, Hero)
    local NumberInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local ally = heroManager:getHero(i)
        if ally ~=nil and GetDistance(ally, Hero) <Range and ally.team == Hero.team and ally.networkID ~= Hero.networkID then
            NumberInRange = NumberInRange + 1
        end
    end
    return NumberInRange
end

function ArrangePrioritys()
    for i, enemy in pairs(enemyHeroes) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function ArrangeTTPrioritys()
	for i, enemy in pairs(enemyHeroes) do
		SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 1)
        SetPriority(priorityTable.Support, enemy, 2)
        SetPriority(priorityTable.Bruiser, enemy, 2)
        SetPriority(priorityTable.Tank, enemy, 3)
	end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

function GetDamages() -- Have to improve it much
    for i = 1, EnemysInTable do
        local EnemyDmgs = EnemyTable[i].hero
		if ValidTarget(EnemyDmgs) then
			local qKSDmg = ((Q1Ready and getDmg("Q", EnemyDmgs, myHero, 1)) or 0)
			local eKSDmg = ((E1Ready and getDmg("E", EnemyDmgs, myHero)) or 0)
			local rKSDmg = ((RReady and getDmg("R", EnemyDmgs, myHero)) or 0)
			local iKSDmg = ((IgniteReady and getDmg("IGNITE", EnemyDmgs, myHero)) or 0)

			--Mana Calculations
			local qMana = 50
			local q2Mana = 30
			local eMana = 50
			local rMana = 0

			--1st Q allready hit
			if QLanded and Q2Ready and myHero.mana >= q2Mana then
				-- Only 2nd Q
				if EnemyDmgs.health <= q2ComboDmg(0, EnemyDmgs) then
					EnemyTable[i].DisplayText = "Q Kill"
					EnemyTable[i].Ready = true
				elseif  EnemyDmgs.health <= q2ComboDmg(0, EnemyDmgs) + iKSDmg then
					EnemyTable[i].DisplayText = "Q+Ign Kill"
					EnemyTable[i].Ready = true
				end
				-- If our E is ready, then check for these KS combos
				if E1Ready and myHero.mana >= (q2Mana + eMana) then
					if EnemyDmgs.health <= eKSDmg + q2ComboDmg(eKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "E+Q Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= eKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+E Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= eKSDmg + iKSDmg + q2ComboDmg(eKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "E+Q+Ign Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= eKSDmg + iKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+E+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
				-- If our R is ready, then check for these KS combos
				if RReady and myHero.mana >= (q2Mana + rMana) then 
					if EnemyDmgs.health <= rKSDmg + q2ComboDmg(rKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "R+Q Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= rKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+R Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= rKSDmg + iKSDmg + q2ComboDmg(rKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "R+Q+Ign Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= rKSDmg + iKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+R+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
				--If our R and E is ready, then check for these KS combos -- Have to check for eRange and rRange later .... needs testing
				if RReady and E1Ready and myHero.mana >= (q2Mana + eMana + rMana) then
					if EnemyDmgs.health <= rKSDmg + eKSDmg + q2ComboDmg(rKSDmg + eKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "E+R+Q Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= rKSDmg + eKSDmg + q2ComboDmg(rKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "R+Q+E Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= rKSDmg + eKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+E+R Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(rKSDmg + eKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "E+R+Q+Ign Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(rKSDmg, EnemyDmgs) then
						EnemyTable[i].DisplayText = "R+E+Q+Ign Kill"
						EnemyTable[i].Ready = true
					elseif iKSDmg > 0 and EnemyDmgs.health <= rKSDmg + eKSDmg + iKSDmg + q2ComboDmg(0, EnemyDmgs) then
						EnemyTable[i].DisplayText = "Q+E+R+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
			-- We didn't use or hit our 1st Q
			else
				-- If our E is ready, check if we can kill with E or E + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
				if E1Ready then
					if EnemyDmgs.health <= eKSDmg then 
						EnemyTable[i].DisplayText = "E Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= eKSDmg + iKSDmg then
						EnemyTable[i].DisplayText = "E+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
				-- If our R is ready, check if we can kill with R or R + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
				if RReady then
					if EnemyDmgs.health  <= rKSDmg then
						if E1Ready then
							EnemyTable[i].DisplayText = "E+R Kill"
							EnemyTable[i].Ready = true
						elseif Q1Ready and EnemyDmgs.health > qKSDmg then
							EnemyTable[i].DisplayText = "Q+R Kill"
							EnemyTable[i].Ready = true
						else
							EnemyTable[i].DisplayText = "R Kill"
							EnemyTable[i].Ready = true
						end
					elseif EnemyDmgs.health <= rKSDmg + iKSDmg then
						EnemyTable[i].DisplayText = "R+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
				-- If our E and R is ready, check if we can kill with E + R or E + R + ign (looping here from the part before elseif, if we were out of range and our Q was allready landed)
				if RReady and E1Ready then
					if EnemyDmgs.health <= eKSDmg + rKSDmg then
						EnemyTable[i].DisplayText = "E+R Kill"
						EnemyTable[i].Ready = true
					elseif EnemyDmgs.health <= eKSDmg + rKSDmg + iKSDmg then
						EnemyTable[i].DisplayText = "E+R+Ign Kill"
						EnemyTable[i].Ready = true
					end
				end
				-- If our Q is ready, check for these. If we hit Q, we're looping back automaticaly to the part before the elseif(if QLanded then)
				if Q1Ready then
					if EnemyDmgs.health <= qKSDmg + iKSDmg and myHero.mana <= qMana then 
						EnemyTable[i].DisplayText = "Q Kill"
						EnemyTable[i].Ready = true
					end
					if EnemyDmgs.health <= qKSDmg + iKSDmg + q2ComboDmg(qKSDmg, EnemyDmgs) and myHero.mana >= (qMana + q2Mana) then
						EnemyTable[i].DisplayText = "Q+Q Kill"
						EnemyTable[i].Ready = true
					end

					--If our E is ready too, then ...
					if E1Ready then
						if EnemyDmgs.health <= qKSDmg + eKSDmg + iKSDmg and myHero.mana >= (qMana + eMana) then
							EnemyTable[i].DisplayText = "E+R Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + eKSDmg + iKSDmg + q2ComboDmg(qKSDmg + eKSDmg, EnemyDmgs) and myHero.mana >= (qMana + eMana + q2Mana) then
							EnemyTable[i].DisplayText = "E+Q+Q Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + eKSDmg + iKSDmg + q2ComboDmg(qKSDmg, EnemyDmgs) and myHero.mana >=  (qMana + eMana + q2Mana) then
							EnemyTable[i].DisplayText = "Q+Q+E Kill"
							EnemyTable[i].Ready = true
						end
					end
					-- If our R is ready, then ...
					if RReady then
						if EnemyDmgs.health <= qKSDmg + rKSDmg + iKSDmg and myHero.mana >= qMana then
							EnemyTable[i].DisplayText = "Q+R Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + rKSDmg + iKSDmg + q2ComboDmg(qKSDmg + rKSDmg, EnemyDmgs) and myHero.mana >= (qMana + q2Mana) then
							EnemyTable[i].DisplayText = "Q+R+Q Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + rKSDmg + iKSDmg + q2ComboDmg(qKSDmg, EnemyDmgs) and myHero.mana >= (qMana + q2Mana) then
							EnemyTable[i].DisplayText = "Q+Q+R Kill"
							EnemyTable[i].Ready = true
						end
					end
					--If our E and R is ready, then .... (we're looping back to if E + R ready and Qlanded, no need for checks here)
					if RReady and E1Ready then
						if EnemyDmgs.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + eKSDmg, EnemyDmgs) then
							EnemyTable[i].DisplayText = "Q+E+Q+R Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + rKSDmg, EnemyDmgs) then
							EnemyTable[i].DisplayText = "Q+R+Q+E Kill"
							EnemyTable[i].Ready = true
						end
						if EnemyDmgs.health <= qKSDmg + rKSDmg + eKSDmg + iKSDmg +q2ComboDmg(qKSDmg + eKSDmg + rKSDmg, EnemyDmgs) then
							EnemyTable[i].DisplayText = "Q+E+R+Q Kill"
							EnemyTable[i].Ready = true
						end
					end
				end
			end
		else
			EnemyTable[i].Ready = false
		end
	end
end

function CastOnObject(Spell, Object)
	Packet("S_CAST", {spellId = Spell, targetNetworkId = Object.networkID}):send()
end

function CastCoord(Spell, X, Z)
	Packet("S_CAST", {spellId = Spell, x = X, y = Z}):send()
end

function FindNearestNonWall(x0, y0, z0, maxRadius, precision)
    local vec, radius = D3DXVECTOR3(x0, y0, z0), 1
    if not IsWall(vec) then return vec end
    x0, z0, maxRadius, precision = math.round(x0 / precision) * precision, math.round(z0 / precision) * precision, maxRadius and math.floor(maxRadius / precision) or math.huge, precision or 50
    local function checkP(x, y) 
        vec.x, vec.z = x0 + x * precision, z0 + y * precision 
        return not IsWall(vec) 
    end
    while radius <= maxRadius do
        if checkP(0, radius) or checkP(radius, 0) or checkP(0, -radius) or checkP(-radius, 0) then 
            return vec 
        end
        local f, x, y = 1 - radius, 0, radius
        while x < y - 1 do
            x = x + 1
            if f < 0 then 
                f = f + 1 + 2 * x
            else 
                y, f = y - 1, f + 1 + 2 * (x - y)
            end
            if checkP(x, y) or checkP(-x, y) or checkP(x, -y) or checkP(-x, -y) or 
               checkP(y, x) or checkP(-y, x) or checkP(y, -x) or checkP(-y, -x) then 
                return vec 
            end
        end
        radius = radius + 1
    end
end

------------------------------------------------------
--					Jungle Stuff					--
------------------------------------------------------

function CheckSmite()
	if myHero:GetSpellData(SUMMONER_1).name:find("Smite") then SmiteSlot = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("Smite") then SmiteSlot = SUMMONER_2 end
end

function JungleDmgs()
	--Jungle Calculations
	if SmiteReady then
		SmiteDmg = math.max(20*myHero.level+370,30*myHero.level+330,40*myHero.level+240,50*myHero.level+100) 
	end
	
	local JungleMob = GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if ValidTarget(Mob, q1Range) then
			q1DmgMob = getDmg("Q", Mob, myHero, 1)
			q2DmgMob = q2ComboDmg(q1DmgMob, Mob)
			q2SmiteDmgMob = q2ComboDmg(q1DmgMob+SmiteDmg,Mob)
		end
	end
end

function JungleClear()
	local JungleMob = GetJungleMob()
	if PROLeeSin.junglesettings.Orbwalk then
		if ValidTarget(JungleMob) then
			OrbWalking(JungleMob)
		else
			MoveToCursor()
		end
	else
		if ValidTarget(JungleMob) and TimeToAttack() then
			myHero:Attack(JungleMob)
		end
	end
	if ValidTarget(JungleMob) and not JungleCurrentPassive then
		--UseItems(JungleMob)
		if PROLeeSin.junglesettings.jungleE and GetDistance(JungleMob) <= e1Range and E1Ready then 
			CastSpell(_E, JungleMob.x, JungleMob.z) 
		elseif PROLeeSin.junglesettings.jungleW and GetDistance(JungleMob) <= 250 and W1Ready then 
			CastSpell(_W)
		elseif PROLeeSin.junglesettings.jungleW and W2Ready then
			CastSpell(_W)
		elseif PROLeeSin.junglesettings.jungleQ and GetDistance(JungleMob) <= q1Range and Q1Ready then 
			CastSpell(_Q, JungleMob.x, JungleMob.z) 
		elseif PROLeeSin.junglesettings.jungleQ and GetDistance(JungleMob) <= q2Range and Q2Ready then
			CastSpell(_Q)
		elseif PROLeeSin.junglesettings.jungleE and GetDistance(JungleMob) <= e2Range and E2Ready then
			CastSpell(_E)
		end
	end
end

function GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if ValidTarget(Mob, q1Range) then return Mob end
	end
	for _, Mob in pairs(JungleMobs) do
		if ValidTarget(Mob, q1Range) then return Mob end
	end
end

-- function QSmiteQ()
-- 	local JungleMob = GetJungleMob()
-- 	local QUsed = false
-- 	for _, Mob in pairs(JungleFocusMobs) do
-- 		if Mob.name:find("Dragon") or Mob.name:find("Worm") or Mob.name:find("LizardElder") or Mob.name:find("AncientGolem") or Mob.name:find("Spider") then
-- 			if ValidTarget(Mob, q1Range) and Mob.health < (SmiteDmg + q1DmgMob + q2DmgMob) and myHero.mana >= 80 then
-- 				if Q1Ready then 
-- 					CastQ(Mob)
-- 					local QUsed = true
-- 				end
-- 				if Q2Ready then
-- 					DelayAction(function() CastSpell(_Q) end, 0.3)
-- 				end			
-- 				if GetDistance(Mob) < SmiteRange - 300 and QUsed and not Q2Ready and SmiteReady then
-- 					CastOnObject(SmiteSlot, Mob)
-- 				end
-- 			end
-- 		end
-- 	end
-- end

function QQSteal()
	local JungleMob = GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if Mob.name:find("Dragon") or Mob.name:find("Worm") or Mob.name:find("LizardElder") or Mob.name:find("AncientGolem") or Mob.name:find("Spider") then
			if ValidTarget(Mob, q1Range) and Mob.health < q1DmgMob + q2DmgMob and myHero.mana >= 80 then
				if Q1Ready then
					CastSpell(_Q,Mob.x, Mob.z)
				end
				if QUnitLanded ~= nil then
					if not Q1Ready and Q2Ready and QUnitLanded == Mob.name then
						CastSpell(_Q)
					end
				end
			end
		end
	end
end

-- function QSmiteQ()
	-- local JungleMob = GetJungleMob()
	-- for _, Mob in pairs(JungleFocusMobs) do
		-- if Mob.name:find("Dragon") or Mob.name:find("Worm") or Mob.name:find("LizardElder") or Mob.name:find("AncientGolem") or Mob.name:find("Spider") then
			-- if ValidTarget(Mob, q1Range) and Mob.health < (SmiteDmg + q1DmgMob + q2DmgMob) and myHero.mana >= 80 then
				-- if Q1Ready then 
					-- CastQ(Mob)
				-- end
				
				-- if QOnTarget(Mob) then
					-- DelayAction(function() CastSpell(_Q)
						-- if GetDistance(Mob) < SmiteRange - 300 and SmiteReady then
							-- CastOnObject(SmiteSlot, Mob)
						-- end
					-- end, 0.3)
				-- end
			-- end
		-- end
	-- end
-- end

------------------------------------------------------
--					Other Functions					--
------------------------------------------------------

function UseItems(Target)
	UseRanduins()
	UseSolari()
    if not ValidTarget(Target) then return end
    for _,item in pairs(items) do
        item.slot = GetInventorySlotItem(item.id)
        if item.slot ~= nil then
            if item.reqTarget and GetDistance(Target) < item.range then
                CastOnObject(item.slot, Target)
            elseif not item.reqTarget then
                if (GetDistance(Target) - GetHitBoxRadius(myHero) - GetHitBoxRadius(Target)) < 50 then
                    CastSpell(item.slot)
                end
            end
        end
    end
end

function UseRanduins()
	Randuins = GetInventorySlotItem(3143)
	RanduinsReady = (Randuins ~= nil and myHero:CanUseSpell(Randuins))
	if RanduinsReady and CountEnemyHeroInRange(500, myHero) > 2 then
		CastSpell(Randuins)
 	end
end

function UseSolari()
	Solari = GetInventorySlotItem(3190)
	SolariReady = (Solari ~= nil and myHero:CanUseSpell(Solari))
	if SolariReady and CountAllyHeroInRange(600, myHero) > 2 then
		CastSpell(Solari)
	end
end

function KillDraws()
    for i = 1, EnemysInTable do
        local EnemyDraws = EnemyTable[i].hero
        local barPos = WorldToScreen(D3DXVECTOR3(EnemyDraws.x, EnemyDraws.y, EnemyDraws.z)) --(Credit to Zikkah)
		local PosX = barPos.x - 35
		local PosY = barPos.y - 50

        if ValidTarget(EnemyDraws) then
        	if EnemyTable[i].Ready then
		  	 	DrawText(EnemyTable[i].DisplayText, 13, PosX, PosY, 0xFFFFE303)
			else
				DrawText("Not Killable", 13, PosX, PosY, 0xFFFFE303)
			end
		end
    end
end

function AutoIgniteKS()
    if PROLeeSin.othersettings.igniteKS and IgniteReady then
        local IgniteDMG = 50 + (20 * myHero.level)
        for _, enemy in pairs(GetEnemyHeroes()) do
            if ValidTarget(enemy, 600) and enemy.health <= IgniteDMG then
                CastOnObject(IgniteSlot, enemy)
            end
        end
    end
end

function WardJump()
	MoveToCursor()
	local WardMyPos = Vector(myHero.x, myHero.y, myHero.z)
	local WardMousePos = Vector(mousePos.x, mousePos.y, mousePos.z)
	if PROLeeSin.othersettings.MaxRange then
	 	if GetDistance(WardMousePos) < WardRange then
	 		Coordenates = mousePos
	 	else
	 		Coordenates = WardMyPos + (WardMousePos - WardMyPos):normalized()*(WardRange-25)
	 	end
	else
		Coordenates = mousePos
	end
	if W1Ready and GetDistance(Coordenates) <= WardRange and GotWard and not WardReady then
		if RSStoneReady then
			CastSpell(RSStoneSlot, Coordenates.x, Coordenates.z)
			WardReady = true
		elseif SStoneReady and not WardReady then
			CastSpell(SStoneSlot, Coordenates.x, Coordenates.z)
			WardReady = true
		elseif WrigglesReady and not WardReady then
			CastSpell(WrigglesSlot, Coordenates.x, Coordenates.z)
			WardReady = true
		elseif TrinketReady and not WardReady then
			CastSpell(ITEM_7, Coordenates.x, Coordenates.z)
			WardReady = true
		elseif SWardReady and not WardReady then
			CastSpell(SWardSlot, Coordenates.x, Coordenates.z)
			WardReady = true
		elseif VWardReady and not WardReady then
			CastSpell(VWardSlot, Coordenates.x, Coordenates.z)
			WardReady = true
		end
	end
	if W1Ready and WardReady then
		for _, Ward in ipairs(WardTable) do
			if GetDistance(Ward) < wRange then
				CastOnObject(_W, Ward)
			end
		end
	end
end

function KSwithR()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if RReady and ValidTarget(Enemy, rRange) and Enemy.health < getDmg("R",Enemy,myHero) + 25 then
            CastOnObject(_R, Enemy)
        end
    end
end

function Menu()
    PROLeeSin = scriptConfig("PROLeeSin - The Playmaker", "PROLeeSin")
    --Settings
    
    PROLeeSin:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
    	PROLeeSin.combosettings:addParam("combo", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    	PROLeeSin.combosettings:addParam("useW", "Use W in Combo", SCRIPT_PARAM_ONOFF, true)
    	PROLeeSin.combosettings:addParam("use2ndE", "Use 2nd E in Combo", SCRIPT_PARAM_ONOFF, true)
    	--PROLeeSin.combosettings:addParam("useR", "Use R in Combo", SCRIPT_PARAM_ONOFF, false)
		PROLeeSin.combosettings:addParam("useIgnite", "Use Ignite", SCRIPT_PARAM_ONOFF, true) 
		PROLeeSin.combosettings:addParam("usepassive", "Use Passive", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.combosettings:addParam("passivestacks", "No. Passive Stacks", SCRIPT_PARAM_SLICE, 1, 0, 2, 0)
		PROLeeSin.combosettings:addParam("OrbWalk", "Use Orbwalking", SCRIPT_PARAM_ONOFF, true)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Harass Settings]", "harasssettings")
        PROLeeSin.harasssettings:addParam("harass", "Harass Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
        PROLeeSin.harasssettings:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.harasssettings:addParam("UseMoveToMouse", "Move to Mouse", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.harasssettings:addParam("passivestacks", "No. Passive Stacks", SCRIPT_PARAM_SLICE, 1, 0, 2, 0)
		--PROLeeSin.harasssettings:addParam("useAA", "Use AA in Harass", SCRIPT_PARAM_ONOFF, false) -- next update

    PROLeeSin:addSubMenu("["..myHero.charName.." - Insec Settings]", "insecsettings")
        PROLeeSin.insecsettings:addParam("insec", "Insec Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
		PROLeeSin.insecsettings:addParam("UseMoveToMouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.insecsettings:addParam("ToAllies", "Kick to allies", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.insecsettings:addParam("flash", "Use Flash", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.insecsettings:addParam("priority", "Priority: 0 = Ward, 1 = Flash", SCRIPT_PARAM_SLICE, 0, 0, 1, 0)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Smart Combo Kill Settings]", "smartcombosettings")
        PROLeeSin.smartcombosettings:addParam("scombo", "Use Smart Combo Kill", SCRIPT_PARAM_ONOFF, true)
        --PROLeeSin.peelersettings:addParam("UseE", "Use E On Peeler Combo", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.smartcombosettings:addParam("UseMoveToMouse", "Use Orbwalking", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.peelersettings:addParam("useQ", "Use Q to get near the enemy", SCRIPT_PARAM_ONOFF, true) -- Force W+R
		--PROLeeSin.peelersettings:addParam("useW", "Use W to the near ally", SCRIPT_PARAM_ONOFF, false) -- W+R, W+Q(+E)+R+Q
	PROLeeSin:addSubMenu("["..myHero.charName.." - Jungle Clearing Settings]", "junglesettings")
		PROLeeSin.junglesettings:addParam("jungle", "Jungle Clearing", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
		PROLeeSin.junglesettings:addParam("laneclear", "Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte ("G"))
		PROLeeSin.junglesettings:addParam("Orbwalk", "Orbwalk on Jungle Clearing", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleW", "Use W", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleE", "Use E", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("hydra", "Reset AA with Tiamat/Hydra", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("secure", "Secure Jungle Objetives", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("UseSmite", "Use Smite to secure jungle objetives", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.junglesettings:addParam("QSmiteQ", "Q+Smite+Q Big Objectives", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.junglesettings:addParam("WWSmite", "Auto W+W+Smite when low life", SCRIPT_PARAM_ONOFF, true) -- ??
		PROLeeSin.junglesettings:addParam("passivestacks", "No. Passive Stacks", SCRIPT_PARAM_SLICE, 2, 0, 2, 0)
	
     PROLeeSin:addSubMenu("["..myHero.charName.." - Draw Settings", "drawsettings")
        PROLeeSin.drawsettings:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("WardHelper", "Draw Ward Helper", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("texts", "Draw Texts", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.drawsettings:addParam("circles", "Draw Circles around Target", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.drawsettings:addParam("FreeLagCircles","Enable lag free circles(RELOAD)", SCRIPT_PARAM_ONOFF, false)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
        PROLeeSin.othersettings:addParam("ksR", "KS with R (do not use with Smart Combo Kill)", SCRIPT_PARAM_ONOFF, false)
        PROLeeSin.othersettings:addParam("igniteKS", "Auto Ignite KS (do not use with Smart Combo Kill)", SCRIPT_PARAM_ONOFF, false)
		PROLeeSin.othersettings:addParam("wjump", "Ward Jump", SCRIPT_PARAM_ONKEYDOWN, false, string.byte ("Z"))
		PROLeeSin.othersettings:addParam("MaxRange", "WardJump out of range", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.othersettings:addParam("UltSaver", "AutoUlt Ults (BETA)", SCRIPT_PARAM_ONOFF, false)

    PROLeeSin.combosettings:permaShow("combo")
    PROLeeSin.harasssettings:permaShow("harass")
    PROLeeSin.insecsettings:permaShow("insec")
    --PROLeeSin.peelersettings:permaShow("pcombo")
    PROLeeSin.othersettings:permaShow("wjump")
	PROLeeSin.junglesettings:permaShow("jungle")
    PROLeeSin:addTS(ts)
end

-- Lag free circles (by barasia, vadash and viseversa)
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
  quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
  quality = 2 * math.pi / quality
  radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end

function round(num) 
	if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75) 
    end
end

------------------------------------------------------
--					Extra CallBacks					--
------------------------------------------------------

function OnProcessSpell(Object,Spell)
	if Object == myHero then
		if Spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()*0.5
			lastWindUpTime = Spell.windUpTime*1000
			lastAttackCD = Spell.animationTime*1000
		end
		if RCasted and Spell.name == "BlindMonkRKick" then 
			CastSpell(FlashSlot, CastFlash.x, CastFlash.z)
		end
	end
	if GetDistance(Object) < 500 and RReady and PROLeeSin.othersettings.UltSaver then
		for i=1, #Spells, 1 do
			if Spell.name == Spells[i] then
				CastOnObject(_R,Object) 
			end
		end
	end
end

function OnAttack(Hero)
	if Hero.isMe then
		if PROLeeSin.junglesettings.hydra and PROLeeSin.junglesettings.jungle then
			if TiamatReady then CastSpell(TiamatSlot) end
			if HydraReady then CastSpell(HydraSlot) end
		end
	end
end

function OnAnimation(Unit,AnimationName)
	if Unit.isMe and LastAnimation ~= AnimationName then LastAnimation = AnimationName end
end

function OnCreateObj(Object)
	if Object and Object.valid and (string.find(Object.name, "Ward") ~= nil or string.find(Object.name, "Wriggle") ~= nil or string.find(Object.name, "Trinket")) then 
		if PROLeeSin.insecsettings.insec then table.insert(InsecWardTable, Object)
		else table.insert(WardTable, Object) 
		end
	end
	if FocusJungleNames[Object.name] then
		table.insert(JungleFocusMobs, Object)
	elseif JungleMobNames[Object.name] then
        table.insert(JungleMobs, Object)
	end
end

function OnDeleteObj(Object)
	for i, Mob in pairs(JungleMobs) do
		if Object.name == Mob.name then
			table.remove(JungleMobs, i)
		end
	end
	for i, Mob in pairs(JungleFocusMobs) do
		if Object.name == Mob.name then
			table.remove(JungleFocusMobs, i)
		end
	end
end

function OnGainBuff(Unit, buff)
	if buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		if Unit==ts.target then
			QLanded = true
		end
		if Unit == tsInsec.target then
			QInsecLanded = true
		end
		QUnitLanded = Unit.name
	end

	if Unit==ts.target and buff.name == "BlindMonkEOne" then
		ELanded = true
	end
	-- if Unit==ts.target and buff.name == "BlindMonkETwoMissile" then
	-- 	ESlow = true
	-- end
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = true
		PassiveStacks = buff.stack
	end
end

function OnLoseBuff(Unit, buff)
	if buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		if Unit==ts.target then
			QLanded = false
		end
		if Unit == tsInsec.target then
			QInsecLanded = false
		end
		QUnitLanded = nil
	end
	if Unit~=ts.target and buff.name == "BlindMonkEOne" then
		ELanded = false
	end
	-- if Unit==ts.target and buff.name == "BlindMonkETwoMissile" then
	-- 	ESlow = false
	-- end
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = false
		PassiveStacks = buff.stack
	end
end

function OnUpdateBuff(Unit, buff)
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		PassiveStacks = buff.stack
	end
end

------------------------------------------------------
--					Additional Info					--
------------------------------------------------------

--[[Buffnames: 
	Q on Enemy: BlindMonkQOne, blindmonkqonechaos
	E1 on Enemy: BlindMonkEOne
	E2 on Enemy: BlindMonkETwoMissile
	Passive: blindmonkpassive_cosmetic
]]

--[[BuffLocations = {
		{x = 3662, y = 54, z = 7598}, -- Blue Team Blue Buff
		{x = 7422, y = 57, z = 3904}, -- Blue Team Red Buff 
		{x = 6501, y = 54, z = 10575}, -- Purple Team Red Buff
		{x = 10359, y = 54, z = 6849} -- Purple Team Blue Buff

]]