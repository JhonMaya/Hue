<?php exit() ?>--by zikkah 83.84.221.160
if myHero.charName ~= "Syndra" then return end


function OnLoad()
	require "Prodiction"
	PetChamp = false
	Menu()
	Vars()
    Spells()
	LoadJungle()
	Balls = {}
	GetAsyncWebResult("zikkah.net78.net","counter.php", donothing)

	for i=1, heroManager.iCount do
		local champ = heroManager:GetHero(i)
		if champ.team ~= myHero.team then
		EnemysInTable = EnemysInTable + 1
		EnemyTable[EnemysInTable] = { hero = champ, Name = champ.charName, q = 0, w = 0, e = 0, r = 0, IndicatorText = "", IndicatorPos, NotReady = false, Pct = 0, PeelMe = false }
		if champ.charName == "Yorick" or champ.charName == "Malzahar" or champ.charName == "Heimerdinger" or champ.charName == "Annie" then PetChamp = true end
		end
	end
	for i=1, BallTable do
		Balls[i] = { pos = nil, time = nil, stunball = false, wball = false}
	end
end
function donothing(response)
end
function OnTick()
	if SMenu.ManualStun	then
		
			for _, enemy in pairs(GetEnemyHeroes()) do
			
			if GetDistance(mousePos, enemy) < 250 then
				Stun(enemy, true)
			end
			
			
			end
		
	end
	
	if SkipGrab and SkipTil ~= nil then
		if SkipTil < os.clock() then
			SkipGrab = false
			SkipTil = nil
		end
	end	
	if StunTil ~= nil and StunTil < os.clock() then
		Stunned = nil
		StunTil = nil
	end
	
	Ks()
	GetStunBall()
	GetPet()
	GlobalInfo()
	Calculations()
	if SMenu.JungleSteal then
		JungleSteal()
	end
	if SMenu.AutoGrabPets and GetPet() ~= nil and wReady and not Grabbed then
		CastSpell(_W, GetPet().x, GetPet().z)
	end
	if SMenu.Harass then 
		if SMenu.HSettings.OrbWalk then
				OrbWalk()
		elseif SMenu.HSettings.MoveToMouse then
			moveToCursor()
		end
		
		if ValidTarget(Target) then
			Harass()
		elseif ValidTarget(Target2) and SMenu.HarassStun and qReady and eReady then
			Stun(Target2)
		end
	end
	if SMenu.Combo then 
		if SMenu.CSettings.OrbWalk then
				OrbWalk()
		elseif SMenu.CSettings.MoveToMouse then
			moveToCursor()
		end
		
		if ValidTarget(Target) then
			Combo()
		elseif ValidTarget(Target2) and qReady and eReady then
			Stun(Target2)
		end
	end
end

function Combo()



	if Stunned ~= nil and Target == Stunned then 
	--	wPos = Target
	--	qPos = Target
	end
	
		Dfg()
		Ulti()
	

		if qReady and qPos and GetDistance(qPos) < 800 then
			CastSpell(_Q, qPos.x, qPos.z)
		end
		if eReady and ePos and not Grabbed then
			
			local StunBall = GetStunBall()
			if StunBall and GetDistance(StunBall)<725 then
				
				CastSpell(_E, StunBall.x, StunBall.z)
				return 
			end
			
		end
		if GetDistance(Target) > 800  then
			if eReady and qReady and not wReady then
				Stun(Target)
				return
			end
			if GetDistance(Target) > 900 and GetDistance(Target) < 1200 and qReady and eReady then
				Stun(Target)
				return
			end
			
		end		
		
		if wPos and GetDistance(wPos) < 925 and wReady  then

--			if qReady and qMana + wMana < MyMana then
--				CastSpell(_Q, myHero.x, myHero.z)
--			end
			if Grabbed then
				CastSpell(_W, wPos.x, wPos.z)
					
			end
			if not SkipGrab and GrabObject() then
				CastSpell(_W, wPos.x, wPos.z)
			end
			
		end
		

	
end

function Harass()



	if Stunned ~= nil and Target == Stunned then 
		wPos = Target
		qPos = Target
	end
	


		
	
	
		
		if qReady and qPos and GetDistance(qPos) < 800 then
			CastSpell(_Q, qPos.x, qPos.z)
		end
		
	if SMenu.HSettings.UseE then
		if eReady and ePos and not Grabbed then
			if Harass and not SMenu.HSettings.UseE then
			else
			local StunBall = GetStunBall()
			if StunBall and GetDistance(StunBall)<725 then
				
				CastSpell(_E, StunBall.x, StunBall.z)
				return 
			end
			end
		end
	end
	if SMenu.HSettings.StunHarass then
		if GetDistance(Target) > 800  then
			if eReady and qReady and not wReady then
				Stun(Target)
				return
			end
			if GetDistance(Target) > 900 and GetDistance(Target) < 1200 and qReady and eReady then
				Stun(Target)
				return
			end
			
		end		
	end	
	if SMenu.HSettings.UseW then
		if wPos and GetDistance(wPos) < 925 and wReady  then
	

			if Grabbed then
				CastSpell(_W, wPos.x, wPos.z)
					
			end
			if not SkipGrab and GrabObject() then
				CastSpell(_W, wPos.x, wPos.z)
			end
			
		end
	end
		
end
function Ulti()

	if rReady then
		if qDmg > THealth and qCurrCd < 1.5 then return 
		elseif  wDmg > THealth and wCurrCd < 1.5 then return 
		elseif Grabbed and wDmg > THealth then return 
		elseif qDmg+eDmg > THealth and qCurrCd < 2 and eCurrCd < 2 then return 
		elseif qReady and wReady and qDmg+wDmg > THealth then return 
		elseif qReady and Grabbed and qDmg+wDmg > THealth then return 
		elseif qReady and wReady and eReady and qDmg+wDmg+eDmg > THealth then return 
		
		elseif THealth < rDmg + iDmg and GetDistance(Target) < 600 then
			if iReady then
				CastSpell(iSlot, Target)
			end
			CastSpell(_R, Target)
		
		
		elseif wCurrCd < 1.5 and wDmg + rDmg > THealth then
			if not qReady then
				CastSpell(_R, Target)
			end
		elseif Grabbed and wDmg + rDmg > THealth then
			if not qReady then
				CastSpell(_R, Target)
			end		
		
		elseif qReady and qDmg + rDmg > THealth then
			CastSpell(_R, Target)
		
		
		
		elseif qPos and qReady and Grabbed and qDmg + wDmg + rDmg > THealth then
			CastSpell(_Q, qPos.x, qPos.z)
            CastSpell(_W, wPos.x, wPos.z)
			CastSpell(_R, Target)
		end
        
	
		
	end


end

function Dfg()
	if dfgReady then
	
		if qDmg + iDmg > THealth then return 
		elseif  wDmg+ iDmg > THealth then return 
		elseif wDmg+ iDmg > THealth then return 
		elseif qDmg+eDmg+ iDmg > THealth then return 
		elseif  qDmg+wDmg+ iDmg > THealth then return 
		elseif  qDmg+wDmg+ iDmg > THealth then return 
		elseif qReady and wReady and eReady and qDmg+wDmg+eDmg > THealth and GetDistance(Target) < 800 then 
			CastSpell(dfgSlot, Target)
		elseif qDmg+wDmg+eDmg+rDmg+ExtraDamage > THealth then
			CastSpell(dfgSlot, Target)
		end


		
	end
end


function Ks()
	for _, enemy in pairs(GetEnemyHeroes()) do
		if GetDistance(enemy) < 1300 and ValidTarget(enemy) then

			if qReady and eReady and enemy.health < eDmg and GetDistance(enemy) > 900 and GetDistance(enemy) < 1300 then
				Stun(enemy)
			end
			
			if iReady and SMenu.Ignite and iDmg > THealth and GetDistance(enemy) < 600  then
				CastSpell(iSlot, enemy)

			end
		end
	end
end

function GrabObject()
	local Grab = nil
	if SkipGrab then return false end
	if Grabbed then return true end
	if GetWObject() == nil then 
		return false
	else
		Grab = GetWObject() 
	end
	
	if Grab ~= nil and not Grabbed then
		CastSpell(_W, Grab.x, Grab.z)
		return true
	end
	
end

function GetWObject()
	local CurrentObject = nil

	local CurrentBall = nil
	local BallNumber = nil
	
	if SMenu.PrioPets and PetChamp and GetPet() ~= nil then
		return GetPet()
	end
	
	-- Get the ball which is longest in the game to extend its duration
	for i=1, BallTable do
		if Balls[i].pos ~= nil and GetDistance(Balls[i].pos) < 925  then
			CurrentBall = Balls[i]
			if CurrentObject == nil then CurrentObject = CurrentBall end
			if CurrentBall.time < CurrentObject.time then
				CurrentObject.wBall = false
				CurrentObject = CurrentBall
				CurrentObject.wBall = true

			end
					
		end

	end
	if CurrentObject ~= nil then
	
		return CurrentObject.pos
	else
	
	
		for i, AvaibleMinion in pairs(Minions.objects) do
			if AvaibleMinion ~= nil and AvaibleMinion.valid and AvaibleMinion.team == TEAM_ENEMY then 
				CurrentObject = AvaibleMinion 
			end
		end
		return CurrentObject

	end
	return nil
end



function GetStunBall()

if ValidTarget(Target) then
	local EndPos, time, hitchance = ProStun:GetPrediction(Target)	
	local stunball = nil
	if EndPos then

		local Ball = nil
		for i=1, BallTable do
			if Balls[i].pos ~= nil and GetDistance(Balls[i].pos) < 800 and eReady  then
				if wReady and GetWObject() == Balls[i].pos then return nil end
				local hit = checkhitlinepass(myHero, Balls[i].pos, 80, 1200, EndPos, getHitBoxRadius(Target)) 
				
				if hit then 
				Balls[i].stunball = true 
				stunball = Balls[i].pos
				return stunball
				end
				
			else
				Balls[i].stunball = false
			end
		end
		return stunball
	end	
end
end

function Stun(unit, manual)

 local pos, time, hitchance =   ProStun:GetPrediction(unit) 
	if pos and GetDistance(pos) < 1300 and  myHero.mana > qMana + eMana then
   
		local x,y,z = (Vector(pos) - Vector(myHero)):normalized():unpack()
		Correction = GetDistance(myHero, pos) / 2
 
		local posX = pos.x - (x * Correction)
		local posY = pos.y - (y * Correction) 
		local posZ = pos.z - (z * Correction)
 
		if manual then
            if qReady and eReady then
                CastSpell(_Q, posX, posZ)
                 TimeToE = os.clock()+(1.1-(GetDistance(unit)/1000))
            
            elseif TimeToE and os.clock() > TimeToE then
                CastSpell(_E, posX, posZ)
                TimeToE = nil
            end
        else
        
			CastSpell(_Q, posX, posZ)
			CastSpell(_E, posX, posZ)
		end
	SkipGrab = true
 end
end

function JungleSteal()

	if Nashor ~= nil then if not Nashor.valid or Nashor.dead or Nashor.health <= 0 then Nashor = nil end end
	if Dragon ~= nil then if not Dragon.valid or Dragon.dead or Dragon.health <= 0 then Dragon = nil end end
	if Golem1 ~= nil then if not Golem1.valid or Golem1.dead or Golem1.health <= 0 then Golem1 = nil end end
	if Golem2 ~= nil then if not Golem2.valid or Golem2.dead or Golem2.health <= 0 then Golem2 = nil end end
	if Lizard1 ~= nil then if not Lizard1.valid or Lizard1.dead or Lizard1.health <= 0 then Lizard1 = nil end end
	if Lizard2 ~= nil then if not Lizard2.valid or Lizard2.dead or Lizard2.health <= 0 then Lizard2 = nil end end
	
	if Nashor ~= nil and GetDistance(Nashor) < 1300 and Nashor.visible then Kill(Nashor, true) end
	if Dragon ~= nil and GetDistance(Dragon) < 1300 and Dragon.visible then Kill(Dragon, true) end
	if Golem1 ~= nil and GetDistance(Golem1) < 1300 and Golem1.visible then Kill(Golem1) end
	if Golem2 ~= nil and GetDistance(Golem2) < 1300 and Golem2.visible then Kill(Golem2) end
	if Lizard1 ~= nil and GetDistance(Lizard1) < 1300 and Lizard1.visible then Kill(Lizard1) end
	if Lizard2 ~= nil and GetDistance(Lizard2) < 1300 and Lizard2.visible then Kill(Lizard2) end	


end

function Kill(object, static)
	local GrabbedObject = false
	if static == nil then static = false end
	DmgOnObject = 0
	local jqDmg = getDmg("Q", object, myHero)
	local jwDmg = getDmg("W", object, myHero)
	
	if not static then
		if Grabbed then
			CastSpell(_W, myHero.x+50, myHero.z+50)
		 
		elseif qReady and wReady  and jqDmg+jwDmg > object.health and GetDistance(object) < 900 then
			CastSpell(_W, object.x, object.z)
			GrabbedObject = true
		elseif wReady and jwDmg > object.health and GetDistance(object) < 1000  then
			CastSpell(_W, object.x, object.z)
		elseif qReady and jqDmg > object.health and GetDistance(object) < 900 then
			CastSpell(_Q, object.x, object.z)
		end
		
	else
		if Grabbed then
			CastSpell(_W, object.x, object.z)
		elseif GetDistance(object) > 1000 and GetDistance(object) < 1400 then
			if eReady and qReady and object.health < eDmg then
				Stun(object)
			end
		elseif GetDistance(object) < 1000 and wReady and GetWObject() ~= nil and wDmg > object.health then
			CastSpell(_W, GetWObject().x, GetWObject().z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and GetWObject() == nil and wDmg > object.health then
			CastSpell(_Q, myHero.x, myHero.z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and qReady and jqDmg + jwDmg > object.health then
			CastSpell(_Q, object.x, object.z)
		elseif GetDistance(object) < 1000 and qReady and jqDmg > object.health then
			CastSpell(_Q, object.x, object.z)
		end
 
	end
	

end
	
------------------
-- 	Helpers 	--
------------------


function GlobalInfo()

	MouseScreen = WorldToScreen(D3DXVECTOR3(mousePos.x, mousePos.y, mousePos.z))
	ts:update()
	Minions:update()
	Target = ts.target
	if Target == nil then
		stuntarget:update()
		Target2 = stuntarget.target
	end
	qReady = myHero:CanUseSpell(_Q) == READY 
	wReady = myHero:CanUseSpell(_W) == READY
	eReady = myHero:CanUseSpell(_E) == READY
	rReady = myHero:CanUseSpell(_R) == READY
	qMana = myHero:GetSpellData(_Q).mana
	wMana = myHero:GetSpellData(_W).mana
	eMana = myHero:GetSpellData(_E).mana
	rMana = myHero:GetSpellData(_R).mana
	qCurrCd = myHero:GetSpellData(_Q).currentCd
	wCurrCd = myHero:GetSpellData(_E).currentCd
	eCurrCd = myHero:GetSpellData(_E).currentCd
	rCurrCd = myHero:GetSpellData(_R).currentCd
	if myHero:GetSpellData(_R).level == 3 then
		rRange = 750
	end

	iSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	iReady = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
	dfgSlot = GetInventorySlotItem(3128)
	dfgReady = (dfgSlot ~= nil and GetInventoryItemIsCastable(3128,myHero))
	lichSlot = GetInventorySlotItem(3100)
	lichReady = (lichSlot ~= nil and myHero:CanUseSpell(lichSlot) == READY)
	sheenSlot = GetInventorySlotItem(3057)
	sheenReady = (sheenSlot ~= nil and myHero:CanUseSpell(sheenSlot) == READY)

	MyMana = myHero.mana
	ManaPct = math.round((myHero.mana / myHero.maxMana)*100)
	if qMana + eMana + rMana <= MyMana then
		GotMana = true
	else
		GotMana = false
	end
	if wTime ~= nil and os.clock()-wTime > 1.4 then 
		ChannelingW = false
		wTime = nil
	end
	if TimeToE and TimeToE + 0.3 < os.clock() then
        TimeToE = nil
        
    end
    
	if ValidTarget(Target) then
		ProQ:GetPredictionCallBack(Target, GetQ)
		ProW:GetPredictionCallBack(Target, GetW)
		ProE:GetPredictionCallBack(Target, GetE)
		qDmg = getDmg("Q", Target, myHero)
		wDmg = getDmg("W", Target, myHero)
		eDmg = getDmg("E", Target, myHero)
		rDmg = getDmg("R", Target, myHero)*(3+BallCount)
		SphereDmg = getDmg("R", Target, myHero) 
		iDmg = (iReady and getDmg("IGNITE", Target, myHero) or 0)
		THealth = Target.health
		sheendamage = (SHEENSlot and getDmg("SHEEN",enemy,myHero) or 0)
		lichdamage = (LICHSlot and getDmg("LICHBANE",enemy,myHero) or 0)
		TotalDamage = qDmg+eDmg+rDmg+sheendamage+lichdamage+iDmg
		ExtraDamage = sheendamage+lichdamage+iDmg
		if dfgReady then 
			TotalDamage = TotalDamage * 1.2
			ExtraDamage = ExtraDamage * 1.2
		end	
		
		if rReady then
			AllReady = true
			if qCurrCd > 1.5 then
				AllReady = false
			end
			if iSlot and not iReady then
				AllReady = false
			end
			if dfgSlot and not dfgReady then
				AllReady = false
			end
		else
			AllReady = false
		end
		
	else
        qPos = nil
        wPos = nil
        ePos = nil
    end

end


function AreaEnemyCount(Spot, Range, Killable)
	local count = 0
	if Killable == nil then Killable = false end
	
	if Killable == true then
	
		for _, enemy in pairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and GetDistance(Spot, enemy) <= Range and getDmg("R", enemy, myHero) > enemy.health then
				count = count + 1
			end
		end   
	
	
	else
		for _, enemy in pairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and GetDistance(Spot, enemy) <= Range then
				count = count + 1
			end
		end            
	end
	return count
end



function GetPet()
	for i, AvaibleMinion in pairs(Minions.objects) do
		if AvaibleMinion ~= nil and AvaibleMinion.valid and AvaibleMinion.team == TEAM_ENEMY then 
		
			if AvaibleMinion.name:find("Tibbers") then
				return AvaibleMinion
			elseif AvaibleMinion.name:find("H-28") then
				return AvaibleMinion
			elseif AvaibleMinion.name:find("Voidling") then
				return AvaibleMinion
			elseif AvaibleMinion.name:find("Inky") then
				return AvaibleMinion
			elseif AvaibleMinion.name:find("Blinky") then
				return AvaibleMinion
			elseif AvaibleMinion.name:find("Clyde") then
				return AvaibleMinion			
			end
			
		end
	end
	return nil
end

function GetQ(unit, pos)
    qPos = pos
end
function GetW(unit, pos)
    wPos = pos
end
function GetE(unit, pos)
    ePos = pos
end




------------------
-- Orbwalkstuff --
------------------
function OrbWalk()
	if ValidTarget(Target) and GetDistance(Target) <= 550 then
		if timeToShoot() then
			myHero:Attack(Target)
		elseif heroCanMove()  then
			moveToCursor()
		end
	else
		moveToCursor()
		
	end
end

function trueRange()
	return myHero.range + GetDistance(myHero.minBBox)
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end

function timeToShoot()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end

function moveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end	
end

function getHitBoxRadius(unit)
	if unit ~= nil then 
		return GetDistance(unit.minBBox, unit.maxBBox)/2
	else
		return 0
	end
end


------------------
-- Draw+Calcs	--
------------------
function OnDraw()

	


	if not SMenu.DisableDraw then
	
	if SMenu.ManualStun then
		DrawCircle(mousePos.x, mousePos.y, mousePos.z, 250, ARGB(255,0,255,0))
	end
	
--[[	if SMenu.ScriptMenu then
	end
	if SMenu.DmgIndic then
	for i=1, EnemysInTable do
		local enemy = EnemyTable[i].hero
		if enemy.visible and not enemy.dead and GetDistance(enemy, cameraPos) < 3000 then
			enemy.barData = GetEnemyBarData()
			local barPos = GetUnitHPBarPos(enemy)
			local barPosOffset = GetUnitHPBarOffset(enemy)
			local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
			local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
			local BarPosOffsetX = 171
			local BarPosOffsetY = 46
			local CorrectionY =  14.5
			local StartHpPos = 31
			local IndicatorPos = EnemyTable[i].IndicatorPos
			local Text = EnemyTable[i].IndicatorText
			barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos 
			barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY 
			if IndicatorPos ~= nil then
				if EnemyTable[i].NotReady == true then
					DrawText(tostring(Text),13,barPos.x+IndicatorPos - 10 ,barPos.y-27 ,orange)		
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y ,orange)
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-9 ,orange)
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-18 ,orange)
				else
					DrawText(tostring(Text),13,barPos.x+IndicatorPos - 10 ,barPos.y-27 ,ARGB(255,0,255,0))	
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y ,ARGB(255,0,255,0))
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-9 ,ARGB(255,0,255,0))
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-18 ,ARGB(255,0,255,0))
				end
			end
		end
	end
	end]]
	if SMenu.ShowQ then
		if qReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 800, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 800, ARGB(255,255,0,0))
		end
	end
--[[	if SMenu.ShowW then
		if eReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 900, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 900, ARGB(255,255,0,0))
		end
	end]]
--[[	if SMenu.ShowE then
		if eReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 575, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 575, ARGB(255,255,0,0))
		end
	end	]]
	if SMenu.ShowR then
		if rReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGB(255,255,0,0))
		end
	end
	if SMenu.ShowStun then
		if qReady and eReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 1300, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 1300, ARGB(255,255,0,0))
		end
	end
	end
end




function Calculations()
	
	 
	--[[
	for i=1, EnemysInTable do
		
		local enemy = EnemyTable[i].hero
		if not enemy.dead and enemy.visible then
		cqDmg = getDmg("Q", enemy, myHero)
		cwDmg = getDmg("W", enemy, myHero)
		ceDmg = getDmg("E", enemy, myHero)
		crTotalDmg = getDmg("R", enemy, myHero) 
		crSphereDmg = getDmg("R", enemy, myHero) 
		ciDmg = getDmg("IGNITE", enemy, myHero)
		csheendamage = (SHEENSlot and getDmg("SHEEN",enemy,myHero) or 0)
		clichdamage = (LICHSlot and getDmg("LICHBANE",enemy,myHero) or 0)
		cDfgDamage = 0
		cExtraDmg = 0
		cTotal = 0
		
	if iReady then
		cExtraDmg = cExtraDmg + iDmg
	end
	
	if sheenReady then
		cExtraDmg = cExtraDmg + csheenDamage
	end
	
	if lichReady then
		cExtraDmg = cExtraDmg + clichDamage
	end
	

	
	
	if rReady then
		crTotalDmg = crSphereDmg*(3+BallCount)
		EnemyTable[i].r = crTotalDmg
	else
		EnemyTable[i].r = 0
	end
	
		EnemyTable[i].q = cqDmg

		EnemyTable[i].w = cqDmg

		EnemyTable[i].e = ceDmg
	
	
	
	if dfgReady then 
		cDfgDamage = (EnemyTable[i].q + EnemyTable[i].w + EnemyTable[i].e + EnemyTable[i].r) * 1.2
	end	
	
	-- Make combos
	if enemy.health < EnemyTable[i].q then
		EnemyTable[i].IndicatorText = "Q Kill"
		EnemyTable[i].IndicatorPos = 0
		if qMana > MyMana or not qReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end
	
	elseif enemy.health <  EnemyTable[i].r  then
		EnemyTable[i].IndicatorText =  "Ult Kill"
		EnemyTable[i].IndicatorPos = 0
		if rMana > MyMana or not rReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end
		
	elseif enemy.health <  EnemyTable[i].q + EnemyTable[i].w then
		EnemyTable[i].IndicatorText =  "Q+W Kill"
		EnemyTable[i].IndicatorPos = 0
		if qMana + wMana > MyMana or not qReady or not wReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end
		
	elseif enemy.health < EnemyTable[i].q + EnemyTable[i].w + EnemyTable[i].e then
		EnemyTable[i].IndicatorText =  "Q+W+E Kill"
		EnemyTable[i].IndicatorPos = 0
		if wMana+qMana+eMana > MyMana or not wReady or not qReady or not eReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end	
		
	elseif enemy.health < EnemyTable[i].q + EnemyTable[i].w + EnemyTable[i].e + cDfgDamage + cExtraDmg then
		EnemyTable[i].IndicatorText =  "Burst Kill"
		EnemyTable[i].IndicatorPos = 0
		if wMana+qMana+eMana > MyMana or not wReady or not qReady or not eReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end			
		
	elseif enemy.health < EnemyTable[i].q + EnemyTable[i].w + EnemyTable[i].e + EnemyTable[i].r + crSphereDmg + cExtraDmg then
		EnemyTable[i].IndicatorText =  "All-In Kill"
		EnemyTable[i].IndicatorPos = 0
		if qMana + rMana > MyMana or not qReady or not rReady then
			EnemyTable[i].NotReady = true
		else
			EnemyTable[i].NotReady = false
		end

		
	else
		
			cTotal = cTotal + EnemyTable[i].q
		
			cTotal = cTotal + EnemyTable[i].w

			cTotal = cTotal + EnemyTable[i].e
		
			cTotal = cTotal + EnemyTable[i].r
		
		
		HealthLeft = math.round(enemy.health - cTotal)
		PctLeft = math.round(HealthLeft / enemy.maxHealth * 100)
		BarPct = PctLeft / 103 * 100
		EnemyTable[i].Pct = PctLeft
		EnemyTable[i].IndicatorPos = BarPct
 		EnemyTable[i].IndicatorText = PctLeft .. "% Harass"

		if not qReady or not rReady or not eReady then
			EnemyTable[i].NotReady =  true
		else
			EnemyTable[i].NotReady = false
		end
		if qMana + eMana + rMana > MyMana  then
			EnemyTable[i].NotReady =  true
		else
			EnemyTable[i].NotReady = false
		end

	end
	
	end

	end	

	
	]]
	

end


------------------
-- 	On Load		--
------------------

function Menu()
			SMenu = scriptConfig("Beasty Syndra!", "Syndra")
			SMenu:addParam("sep", "----- [ General Settings ] -----", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("Combo","Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)

            SMenu:addSubMenu("Combo Settings", "CSettings")
			SMenu.CSettings:addParam("OrbWalk","Orbwalk in combo", SCRIPT_PARAM_ONOFF, true)
			SMenu.CSettings:addParam("MoveToMouse","Move to mouse only", SCRIPT_PARAM_ONOFF, false)
            
			SMenu:addParam("Harass","Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
            SMenu:addSubMenu("Harass settings", "HSettings")
			SMenu.HSettings:addParam("OrbWalk","Orbwalk in harass", SCRIPT_PARAM_ONOFF, true)
			SMenu.HSettings:addParam("MoveToMouse","Move to mouse only", SCRIPT_PARAM_ONOFF, false)
 			SMenu.HSettings:addParam("UseW","Use W in harass", SCRIPT_PARAM_ONOFF, true)
			SMenu.HSettings:addParam("UseE","Use E in harass", SCRIPT_PARAM_ONOFF, true)
			SMenu.HSettings:addParam("StunHarass","Use stuncombo in harass(900+ range)", SCRIPT_PARAM_ONOFF, false)           

			SMenu:addParam("JungleSteal","Jungle Steal", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
			SMenu:addParam("ManualStun","Manual stun target near mouse", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
		
			SMenu:addParam("sep", "----- [ Extra Settings ] -----", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("PrioPets","Prioritize enemy Pets for grab", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("AutoGrabPets","Autograb enemy Pets", SCRIPT_PARAM_ONOFF, false)
				

		
			SMenu:addParam("sep", "----- [ KS ] -----", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("AutoKS","Snipe(E+Q)", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("Ignite","Ignite killable", SCRIPT_PARAM_ONOFF, true)

				
			
			SMenu:addParam("sep", "---- [ Draw ] ----", SCRIPT_PARAM_INFO, "")
--			SMenu:addParam("DmgIndic","Show hp-bar indicator", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("ShowQ","Draw Q range", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("ShowW","Draw W range", SCRIPT_PARAM_ONOFF, false)
			SMenu:addParam("ShowE","Draw E range", SCRIPT_PARAM_ONOFF, false)
			SMenu:addParam("ShowR","Draw R range", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("ShowStun","Draw max stun range", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("DisableLagFree","Disable lag free circles(RELOAD)", SCRIPT_PARAM_ONOFF, false)
			SMenu:addParam("DisableDraw","Disable all visuals", SCRIPT_PARAM_ONOFF, false)
			

			
end



function Vars()
qPos = nil
wPos = nil
ePos = nil
ResetTime = nil
ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 950, true)
stuntarget = TargetSelector(TARGET_LOW_HP_PRIORITY, 1300, true)
Grabbed = false
Minions = minionManager(MINION_ALL, 925, myHero)
ts.name = "Syndra"
SMenu:addTS(ts)
BallCount = 0
StunTil = nil
if not SMenu.DisableLagFree then
	_G.DrawCircle = DrawCircle2
end
Delay = nil
--Spells
qReady, wReady, eReady, rReady = false, false, false, false, false
AllReady = false
qCurrCd, wCurrCd,eCurrCd, rCurrCd = 0,0,0,0
qDmg, wDmg, eDmg, rDmg, iDmg, dfgDamage = 0,0,0,0,0,0
rRange = 675
cqDmg, wDmg, ceDmg, crTotalDmg, ciDmg, cExtraDmg, cTotal, cMana, crSphereDmg = 0,0,0,0,0,0,0,0,0
MyMana = 0
GotMana = false
StunBall = nil
SkipGrab = false
SkipTil = nil
--Helpers
lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
THealth = 0
EnemyTable = {}
EnemysInTable = 0
HealthLeft = 0
PctLeft = 0
BarPct = 0
orange = 0xFFFFE303
green = ARGB(255,0,255,0)
blue = ARGB(255,0,0,255)
red = ARGB(255,255,0,0)

BallTable = 12
test = nil
end



function Spells()
	-- Q
	
	ProQ = ProdictManager.GetInstance():AddProdictionObject(_Q, 1000, 1500, 0.500, 100, myHero)
		
	-- W
	ProW = ProdictManager.GetInstance():AddProdictionObject(_W, 1000, 1500, 0.250, 100, myHero)
		
	-- E
	ProE = ProdictManager.GetInstance():AddProdictionObject(_R, 800, 1200, 0.250, 100, myHero)

	ProStun = ProdictManager.GetInstance():AddProdictionObject(stun, 1300, 1200, 0.250, 50, myHero)
		
end	


function LoadJungle()

	for i = 1, objManager.maxObjects do
		local obj = objManager:getObject(i)
		if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
			if obj.name == "TT_Spiderboss7.1.1" then Vilemaw = obj
			elseif obj.name == "Worm12.1.1" then Nashor = obj
			elseif obj.name == "Dragon6.1.1" then Dragon = obj
			elseif obj.name == "AncientGolem1.1.1" then Golem1 = obj
			elseif obj.name == "AncientGolem7.1.1" then Golem2 = obj
			elseif obj.name == "LizardElder4.1.1" then Lizard1 = obj
			elseif obj.name == "LizardElder10.1.1" then Lizard2 = obj end
		end
	end
end


------------------
-- Callbacks	--
------------------

function OnProcessSpell(object,spell)
	if object == myHero then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end
		if spell.name:find("SyndraW") then
			Grabbed = true
		end
		if spell.name:find("SyndraE") or spell.name:find("syndrae5") then
			
			SkipTil = os.clock()+1
			
		end
	end
end


function OnCreateObj(obj)
	if BallCount < 0 then BallCount = 0 end
	
	if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
		if obj.name == "TT_Spiderboss7.1.1" then Vilemaw = obj
		elseif obj.name == "Worm12.1.1" then Nashor = obj
		elseif obj.name == "Dragon6.1.1" then Dragon = obj
		elseif obj.name == "AncientGolem1.1.1" then Golem1 = obj
		elseif obj.name == "AncientGolem7.1.1" then Golem2 = obj
		elseif obj.name == "LizardElder4.1.1" then Lizard1 = obj
		elseif obj.name == "LizardElder10.1.1" then Lizard2 = obj end
	end
	
		if obj.name:find("Syndra_DarkSphere_idle") or obj.name:find("Syndra_DarkSphere5_idle") then
		
		BallCount = BallCount+1
			for i=1, BallTable do
				if Balls[i].pos == nil then
					Balls[i] = { pos = obj, time=os.clock() }
					return 
				end
			end
		end
end

function OnDeleteObj(obj)

	if obj ~= nil and obj.name ~= nil then
		if obj.name == "TT_Spiderboss7.1.1" then Vilemaw = nil
		elseif obj.name == "Worm12.1.1" then Nashor = nil
		elseif obj.name == "Dragon6.1.1" then Dragon = nil
		elseif obj.name == "AncientGolem1.1.1" then Golem1 = nil
		elseif obj.name == "AncientGolem7.1.1" then Golem2 = nil
		elseif obj.name == "LizardElder4.1.1" then Lizard1 = nil
		elseif obj.name == "LizardElder10.1.1" then Lizard2 = nil end
	end

		if obj.name:find("Syndra_DarkSphere_idle") or obj.name:find("Syndra_DarkSphere5_idle") then
			for i=1, BallTable do
				if obj == Balls[i].pos then
					Balls[i].pos = nil
					Balls[i].time = nil
					Balls[i].stunball = false
					Balls[i].wball = false

				break end
			end
				BallCount = BallCount -1

		end
end

function OnGainBuff(unit, buff) 
	if unit == Target then
		if buff.name == "syndraebump" and buff.source == myHero then
			Stunned = unit
			StunTil = os.clock() + 1.3
		end
	elseif unit == Target2 then
		if buff.name == "syndraebump" and buff.source == myHero then
		
			Stunned = unit
			StunTil = os.clock() + 1.3

		end
	end
end

function OnLoseBuff(unit, buff) 
	
	if buff.name == "syndraemarker" then
		Stunned = nil
		StunTil = nil
	end

	
	
	if unit.isMe then
		if buff.name:find("syndrawtooltip") then
			Grabbed = false
		end
	end

end

function OnUpdateBuff(unit, buff) 
	if unit.isMe then
	end

end

function OnDash(unit, dash)
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
