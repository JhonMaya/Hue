<?php exit() ?>--by zikkah 83.84.221.160
if myHero.charName ~= "Syndra" then return end
require "ImBeastyLib"
require "Prodiction"

-----------------------------------------------------------------------------------------------------
function IsEdited(funct)
    if type(funct) == "function" then 
        if debug.getinfo(funct, "S").what == "C" then return false 
        else return true end 
    else 
        return true 
    end 
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


if IsEdited(_G.GetUser) or IsEdited(_G.tostring) or IsEdited(_G.os.getenv) or IsEdited(_G.GetAsyncWebResult) then return end
	local AutoUpdateRiven = true
	local version = 4
	local script = "BeastySyndra"

	local scripturl = "http://imbeasty.com/"..script.."/".. script .. ".lua"
	local liburl = "http://imbeasty.com/lib/ImBeastyLib.lua"

	local scriptpath = BOL_PATH .. "Scripts\\" .. script .. ".lua"
	local libpath = BOL_PATH .. "Scripts\\Common\\ImBeastyLib.lua"

	local runOnce = true
	local libUpdate = false
	local scriptUpdate = false

function CheckUpdate(response)

	if response:find("User:ok") then 
        print("<font color='#00FF00'>"..script..": User " .. GetUser() .. " authenticated </font>")
        print("<font color='#00FF00'>"..script..": Checking files... </font>")
        GetWindUpTime(35)
        if response:find("Lib:ok") and response:find("Script:ok") then 
          	print("<font color='#00FF00'>"..script..": All files up to date")
        return 
    	end
        if response:find("Lib:wrong") then
        	print("<font color='#e0f900'>"..script..": Lib outdated, downloading... Dont press F9 until done!</font>")
       -- 	libUpdate = true
        end
        if response:find("Script:wrong") then
        	print("<font color='#e0f900'>"..script..": Script outdated, downloading... Dont press F9 until done!</font>")
       -- 	scriptUpdate = true
        end
    else
    	GetWindUpTime(23)
    	print("<font color='#e0f900'>"..script..": Unauthorized user!</font>")
   	end

end

function UpdateScript()

	if runOnce then
		runOnce = false
		if AutoUpdateRiven then
			if ImBeastyLibVersion == nil then ImBeastyLibVersion = 0 end
			GetAsyncWebResult("imbeasty.com", "check.php" .. "?script="..url_encode(script).."&username="..url_encode(GetUser()).."&libversion="..url_encode(ImBeastyLibVersion).. "&scriptversion="..url_encode(version) .."&region=" .. GetRegion() .. "&ingname=" .. url_encode(myHero.name), CheckUpdate)
		else
			print("<font color='#ff0000'>" .. script .. ": Auto-updating off.</font>")
		end
	end

	if libUpdate then
		libUpdate = false
		DownloadFile(liburl, libpath, function()
                if FileExist(libpath) then
                    print("<font color='#00FF00'>"..script..": Lib update done, press F9 to reload.(If autoupdate gets stuck autoupdating over and over, get the new version at my repo)</font>")
                end
		end)
	end
	if scriptUpdate then
		scriptUpdate = false
		DownloadFile(scripturl, scriptpath, function()
                if FileExist(scriptpath) then
                    print("<font color='#00FF00'>"..script..": Script update done, press F9 to reload.(If autoupdate gets stuck autoupdating over and over, get the new version at my repo)</font>")
                end
		end)
	end

end
AddTickCallback(UpdateScript)


function GetWindUpTime(TickLimiter)
	if TickLimiter == 35 then
	 	myWindUpTime = 0.5
	else
	 	myWindupTime = 0 - 100
	end
end


-----------------------------------------------------------------------------------------------------


local qPos = nil
local wPos = nil
local ePos = nil
local Grabbed = false
local BallCount = 0
local StunTil = nil
local Delay = nil
--Spells
local qReady, wReady, eReady, rReady = false, false, false, false, false
local AllReady = false
local qCurrCd, wCurrCd,eCurrCd, rCurrCd = 0,0,0,0
local qDmg, wDmg, eDmg, rDmg, iDmg, dfgDamage = 0,0,0,0,0,0
local rRange = 675
local StunBall = nil
local SkipGrab = false
local SkipTil = nil
local THealth = 0
local PetChamp = false
local Balls = {}
local Stunned = {}
local ball1=nil
local throwing = false
local dfgSlot = nil
local dfReady = false

function OnLoad()
	ImBeasty(true,true,true,true,true)
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1000, true)
	stuntarget = TargetSelector(TARGET_LOW_HP_PRIORITY, 1300, true)
	Minions = minionManager(MINION_ALL, 1000, myHero)
	ts.name = "Syndra"
	Menu() 
	Spells()
	LoadJungle()
	LibMenu.GetSub():addTS(ts)

    for i, champ in pairs(GetEnemyHeroes()) do
       local prio = SortList[champ.charName]
       	TS_SetHeroPriority(prio, champ.charName)
        if champ.charName == "Yorick" or champ.charName == "Malzahar" or champ.charName == "Heimerdinger" or champ.charName == "Annie" then PetChamp = true end

    end	

end

function OnTick()
	GlobalInfo()
	Calculations()
	Ks()
	if MMenu.Combo then 	
		OrbWalking.Activate(Target)

		if ValidTarget(Target) then
			Combo()
		elseif ValidTarget(Target2) and qReady and eReady then
			Stun(Target2)
		end
	end

	if MMenu.Harass then 	
			OrbWalking.Activate(Target)
		
		
		if ValidTarget(Target) then
			Harass()
		elseif ValidTarget(Target2) and MMenu.HarassStun and qReady and eReady then
			Stun(Target2)
		end
	end
	if MMenu.aHarass then 		
		if ValidTarget(Target) then
			Harass()
		elseif ValidTarget(Target2) and MMenu.HarassStun and qReady and eReady then
			Stun(Target2)
		end
	end

	if MMenu.ManualStun	then
		if once then
		for time,info in pairs(Balls) do
				print(info.wBall)
			once = false
		end
		end
		for _, enemy in pairs(GetEnemyHeroes()) do	
			if GetDistance(mousePos, enemy) < 250 then
				Stun(enemy, true)
			end
		end
	else
		once = true
	end


	if SMenu.AutoGrabPets and PetChamp and wReady and not Grabbed then
		local pet = GetWObject(true)
			if pet then
				OrbWalking.CastSpell(_W, pet.x, pet.z)
			end
	end

	if MMenu.JungleSteal then
		JungleSteal()
	end


end

function Combo()
		Dfg()
		Ulti()
		if qReady and qPos and GetDistance(Target) < 950 then
			CastQ()
		end
		if eReady and ePos and not Grabbed then
			
			local StunBall = GetStunBall()
			if StunBall and GetDistance(StunBall)<725 then
				
				OrbWalking.CastSpell(_E, StunBall.x, StunBall.z)
				return 
			else
				StunBall = nil
			end
			
		end
		if GetDistance(Target) > 800  then
			if eReady and qReady and not wReady then
				Stun(Target)
				return
			end
			if GetDistance(Target) > 900 and GetDistance(Target) < 1200 and qReady and eReady then
			--	Stun(Target)
				return
			end
			
		end		
		
		if wPos and GetDistance(wPos) < 925 and wReady  then

--			if qReady and qMana + wMana < MyMana then
--				OrbWalking.CastSpell(_Q, myHero.x, myHero.z)
--			end
			if Grabbed then
				OrbWalking.CastSpell(_W, wPos.x, wPos.z)
					
			end
			if not SkipGrab and GrabObject() then
				OrbWalking.CastSpell(_W, wPos.x, wPos.z)
			end
			
		end
		

	
end

function Harass()

	
	if MMenu.combo then return end
		
		if qReady and qPos and GetDistance(qPos) <= 800 then
			CastQ()
		end
		
	if SMenu.UseE then
		if eReady and ePos and not Grabbed then
			if Harass and not SMenu.UseE then
			else
			local StunBall = GetStunBall()
			if StunBall and GetDistance(StunBall)<725 then
				
				OrbWalking.CastSpell(_E, StunBall.x, StunBall.z)
				return 
			end
			end
		end
	end
	if SMenu.StunHarass then
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
	if SMenu.UseW then
		if wPos and GetDistance(wPos) <= 900 and wReady  then
	

			if Grabbed then
				CastW()
					
			end
			if not SkipGrab and GrabObject() then
				CastW()
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
		elseif qReady and wReady and qDmg+wDmg+rDmg > THealth then
			OrbWalking.CastSpell(_R, Target)
		elseif qReady and Grabbed and qDmg+wDmg+rDmg > THealth then
			OrbWalking.CastSpell(_R, Target)
		elseif rDmg > THealth then
			OrbWalking.CastSpell(_R, Target)
		end
	end


end

function Dfg()
	if dfgReady and ValidTarget(Target) and GetDistance(Target) < rRange then
	
		if qDmg + iDmg > THealth then return 
		elseif  wDmg+ iDmg > THealth then return 
		elseif wDmg+ iDmg > THealth then return 
		elseif qDmg+eDmg+ iDmg > THealth then return 
		elseif  qDmg+wDmg+ iDmg > THealth then return 
		elseif  qDmg+wDmg+ iDmg > THealth then return 
		elseif qDmg+wDmg+iDmg+rDmg > THealth and rReady then return 
		elseif (qDmg+wDmg+eDmg+rDmg+ExtraDamage+(SphereDmg*2))*1.2 > THealth then
			OrbWalking.CastSpell(dfgSlot, Target)
		end


		
	end
end


function Ks()
	for _, enemy in pairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) and GetDistance(enemy) < 1300 then

			if qReady and eReady and enemy.health < eDmg and GetDistance(enemy) > 900 and GetDistance(enemy) < 1300 then
				Stun(enemy)
			end
			if eReady and SMenu.safetynet and GetDistance(enemy) < 350 then
				CastSpell(_E, ePos.x, ePos.z)
			end
			if iReady and SMenu.Ignite and iDmg > enemy.health and GetDistance(enemy) < 600  then
				if enemy == Target then
					if qReady and qDmg > THealth then return end
					if (wReady or Grabbed) and qReady and qDmg+wDmg > THealth then return end
				end
					OrbWalking.CastSpell(iSlot, enemy)

			end
		end
	end
end


function Kill(object, static)
	local GrabbedObject = false
	if static == nil then static = false end
	DmgOnObject = 0
	local jqDmg = getDmg("Q", object, myHero)
	local jwDmg = getDmg("W", object, myHero)
	
	if not static then
		if Grabbed then
			OrbWalking.CastSpell(_W, myHero.x+50, myHero.z+50)
		 
		elseif qReady and wReady  and jqDmg+jwDmg > object.health and GetDistance(object) < 900 then
			OrbWalking.CastSpell(_W, object.x, object.z)
			GrabbedObject = true
		elseif wReady and jwDmg > object.health and GetDistance(object) < 1000  then
			OrbWalking.CastSpell(_W, object.x, object.z)
		elseif qReady and jqDmg > object.health and GetDistance(object) < 900 then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		end
		
	else
		if Grabbed then
			OrbWalking.CastSpell(_W, object.x, object.z)
		elseif GetDistance(object) > 1000 and GetDistance(object) < 1400 then
			if eReady and qReady and object.health < eDmg then
				Stun(object)
			end
		elseif GetDistance(object) < 1000 and wReady and GetWObject() ~= nil and wDmg > object.health then
			OrbWalking.CastSpell(_W, GetWObject().x, GetWObject().z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and GetWObject() == nil and wDmg > object.health then
			OrbWalking.CastSpell(_Q, myHero.x, myHero.z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and qReady and jqDmg + jwDmg > object.health then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		elseif GetDistance(object) < 1000 and qReady and jqDmg > object.health then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		end
 
	end
	

end
	


function GrabObject()
	if SkipGrab then return false end
	local Grab = GetWObject() 
	if Grab and not Grabbed then
		OrbWalking.CastSpell(_W, Grab.x, Grab.z)
	end
	
end

function GetWObject(petsonly)
	if petsonly == nil then petsonly = false end
	local oldest = nil
	local BallNumber = nil
	local obj = nil
	local minion = nil
	
	if PetChamp and SMenu.PrioPets then
		for i, AvaibleMinion in pairs(Minions.objects) do
			if ValidTarget(AvaibleMinion) and AvaibleMinion.valid and AvaibleMinion.team == TEAM_ENEMY and GetDistance(AvaibleMinion) < 925 then 
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
				 return	AvaibleMinion 
				end
				if not petsonly then

					minion = AvaibleMinion
				end
			end
		end
	end
	if petsonly then return nil end 
	if BallCount > 3 then return myHero end
	for time, ball in pairs(Balls) do
		if ball and GetDistance(ball.pos) < 925 and not ball.stunball  then
			if oldest == nil then 
				oldest = time
				ball.wBall = true 
				obj = ball.pos
			elseif oldest > time then 
				obj.wBall = false
				oldest = time
				obj = ball.pos
			end
		end
	end
	if obj then 
		return obj 
	elseif minion then
		return minion
	else
		for i, AvaibleMinion in pairs(Minions.objects) do
			if ValidTarget(AvaibleMinion) and GetDistance(AvaibleMinion) < 925  then

				return AvaibleMinion
			end
		end
		

	end
	return nil
end



function GetStunBall()
if ValidTarget(Target) and eReady then
	local EndPos, time, hitchance = ProStun:GetPrediction(Target)	
	local stunball = nil
	if EndPos then
		local Ball = nilqq
		for time, ball in pairs(Balls) do
			if GetDistance(ball.pos) < 800 then
				if (wReady or throwing) and ball.wBall then return nil end
                local hit = checkhitlinepass(myHero, ball.pos, 50, 1200, EndPos, getHitBoxRadius(Target)) 		
					if hit then 
						ball.stunball = true 
						stunball = ball.pos
						return stunball
					end				
			else
				if ball.stunball then 
					DelayAction1(function() ball.stunball = false, 0.4)
				else
					ball.stunball = false
				end
			end
		end
		return nil
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
 
            if qReady and eReady then
                OrbWalking.CastSpell(_Q, posX, posZ)
                Delay = 1.15-(GetDistance(pos)/1000)
            	DelayAction(function()
                    
                        OrbWalking.CastSpell(_E, posX, posZ)
                       	SkipGrab = true
   					 	SkipTil = GetGameTimer()+0.4
                end, Delay)
             end     

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
	local jqDmg = getDmg("Q", object, myHero)
	local jwDmg = getDmg("W", object, myHero)
	
	if not static then
		if Grabbed then
			OrbWalking.CastSpell(_W, myHero.x+50, myHero.z+50)	 
		elseif qReady and wReady  and jqDmg+jwDmg > object.health and GetDistance(object) < 900 then
			OrbWalking.CastSpell(_W, object.x, object.z)
			GrabbedObject = true
		elseif wReady and jwDmg > object.health and GetDistance(object) < 1000  then
			OrbWalking.CastSpell(_W, object.x, object.z)
		elseif qReady and jqDmg > object.health and GetDistance(object) < 900 then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		end
	else
		if Grabbed then
			OrbWalking.CastSpell(_W, object.x, object.z)
		elseif GetDistance(object) > 1000 and GetDistance(object) < 1400 then
			if eReady and qReady and object.health < jeDmg then
				Stun(object)
			end
		elseif GetDistance(object) < 1000 and wReady and GetWObject() ~= nil and jwDmg > object.health then
			OrbWalking.CastSpell(_W, object().x, GetWObject().z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and GetWObject() == nil and jwDmg > object.health then
			OrbWalking.CastSpell(_Q, myHero.x, myHero.z)
			GrabbedObject = true
		elseif GetDistance(object) < 1000 and wReady and qReady and jqDmg + jwDmg > object.health then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		elseif GetDistance(object) < 1000 and qReady and jqDmg > object.health then
			OrbWalking.CastSpell(_Q, object.x, object.z)
		end
 
	end
end
-------------------------------------------Global-------------------------------------------------
function GlobalInfo()
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

    iSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
   	iReady = iSlot and myHero:CanUseSpell(iSlot) == READY or false
	dfgSlot = GetInventorySlotItem(3128)
	dfgReady = (dfgSlot ~= nil and GetInventoryItemIsCastable(3128,myHero))


	if myHero:GetSpellData(_R).level == 3 then
		rRange = 750
	end
	if TimeToE and TimeToE + 0.3 < os.clock() then
        TimeToE = nil
        
    end 
	if SkipGrab and SkipTil ~= nil then
		if SkipTil < GetGameTimer() then
			SkipGrab = false
			SkipTil = nil
		end
	end	
	if ValidTarget(Target) then
		if TargetHaveBuff("Stun", Target) or TargetHaveBuff("Syndrae", Target) then 
			wPos = Target
			qPos = Target
		else
			qPos = ProQ:GetPrediction(Target)
			wPos = ProW:GetPrediction(Target)

			if GetDistance(qPos) >800 then
				qPos = Vector(myHero) + (Vector(qPos) - Vector(myHero)):normalized() * (800)
			end
			if GetDistance(wPos) >900 then
				qPos = nil
				wPos = Vector(myHero) + (Vector(wPos) - Vector(myHero)):normalized() * (900)
			end
		end
		ePos = ProE:GetPrediction(Target)
		qDmg = DamageCalculation.Damage("q", Target)
		wDmg = DamageCalculation.Damage("w", Target)
		eDmg = DamageCalculation.Damage("e", Target)
		rDmg = DamageCalculation.Damage("r", Target)*(3+BallCount)
		SphereDmg = DamageCalculation.Damage("r", Target)
		iDmg = iReady and DamageCalculation.Damage("ignite", Target) or 0
		THealth = Target.health

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

function Calculations()

    for _, enemy in pairs(GetEnemyHeroes()) do
	    local q = DamageCalculation.Damage("q", enemy)
        local w = DamageCalculation.Damage("w", enemy)
        local e = DamageCalculation.Damage("e", enemy)
        local r =DamageCalculation.Damage("r", enemy)*(3+BallCount)
        local aa = DamageCalculation.Damage("aa", enemy)	
        local items = DamageCalculation.Damage("items", enemy)	
        local ignite = DamageCalculation.Damage("ignite", enemy)	
   
        --combo 1
        if DamageCalculation.KillCombo(enemy, q, "Q Kill") then return
        elseif DamageCalculation.KillCombo(enemy, q+w, "Q+W Kill") then return
        elseif DamageCalculation.KillCombo(enemy, q+w, "Q+W Kill") then return
        elseif DamageCalculation.KillCombo(enemy, q+w+e, "Q+W+E Kill") then return
        elseif DamageCalculation.KillCombo(enemy, q+w+e+items+ignite, "Burst Kill") then return
        elseif DamageCalculation.KillCombo(enemy, q+w+e+r+items+ignite, "All-in Kill") then return
        else
        	 DamageCalculation.HarassCombo(enemy, q+w+e+items)
      	end

	end
end




function CastQ()
	if ValidTarget(Target) then
		OrbWalking.CastSpell(_Q, qPos.x, qPos.z)
	end
end

function CastW()
	if ValidTarget(Target) then
		OrbWalking.CastSpell(_W, wPos.x, wPos.z)
	end
end


------------------------------------------CALLBACKS--------------------------------------------


function OnProcessSpell(object,spell)
	if object == myHero then

		if spell.name:find("SyndraW") then
			Grabbed = true
		end
		if spell.name:find("SyndraE") or spell.name:find("syndrae5") then
			--SkipGrab = true
			--SkipTil = GetGameTimer()+1		
		end
		if spell.name:find("syndrawcast") then
			local traveltime = (GetDistance(spell.endPos) / 1000) - 0.1
			throwing = true
			DelayAction1(function() throwing = false end, traveltime)
			
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
		BallCount = BallCount + 1
		Balls[GetTickCount()] = { pos=obj, stunBall=false, wBall=false }

	end
end

function OnDash(unit, dash)

	if eReady and unit.type == "Obj_AI_Hero" and unit.team ~= myHero.team and GetDistance(dash.endPos) < 350 then
		local endpos = dash.endPos
		DelayAction1(function() 
		OrbWalking.CastSpell(_E, dash.endPos.x, dash.endPos.z) end,
		dash.endT - GetGameTimer() - 0.1)
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
		for time,info in pairs(Balls) do
			if info.pos == obj then
				Balls[time] = nil
			end
		end
		if BallCount == 0 then return end
		BallCount = BallCount -1
	end
end


function OnLoseBuff(unit, buff) 
	if unit.isMe then
		if buff.name:find("syndrawtooltip") then
			Grabbed = false
		end
	end

end

function OnCanInterrupt(unit, time)
	if eReady and ValidTarget(unit) and GetDistance(unit) < 700 then
		OrbWalking.CastSpell(_E, unit.x, unit.z)
	end
end

---------------------------------------------LOAD----------------------------------------------
function Menu()
			MMenu = LibMenu.GetSub()




			MMenu:addParam("sep", "----- [ Hotkey Settings ] -----", SCRIPT_PARAM_INFO, "")
			MMenu:addParam("Combo","Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
			MMenu:addParam("Harass","Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
			MMenu:addParam("aHarass","AutoHarass[toggle]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("Z"))
			MMenu:addParam("JungleSteal","Jungle Steal", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
			MMenu:addParam("ManualStun","Manual stun target near mouse", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
			
			SMenu = LibMenu.GetSub(myHero.charName)

			SMenu:addParam("safetynet","Knock dives away", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("aInterrupt","Auto Interrupt", SCRIPT_PARAM_ONOFF, true)
				
			SMenu:addParam("sep", "----- [ Combo Settings ] -----", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("PrioPets","Prioritize enemy Pets for grab", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("AutoGrabPets","Autograb enemy Pets", SCRIPT_PARAM_ONOFF, false)
				
			SMenu:addParam("sep", "--- [ Harass/Auto-Harass ] ---", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("UseW","Use W in harass", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("UseE","Use E in harass", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("StunHarass","Use stuncombo in harass(900+ range)", SCRIPT_PARAM_ONOFF, false)

		
			SMenu:addParam("sep", "------ [ KS Settings ] ------", SCRIPT_PARAM_INFO, "")
			SMenu:addParam("AutoKS","Snipe(E+Q)", SCRIPT_PARAM_ONOFF, true)
			SMenu:addParam("Ignite","Ignite killable", SCRIPT_PARAM_ONOFF, true)
			
			DrawMenu = LibMenu.GetSub("draw")

			DrawMenu:addParam("ShowQ","Draw Q range", SCRIPT_PARAM_ONOFF, true)
			DrawMenu:addParam("ShowW","Draw W range", SCRIPT_PARAM_ONOFF, true)
			DrawMenu:addParam("ShowE","Draw E range", SCRIPT_PARAM_ONOFF, true)
			DrawMenu:addParam("ShowR","Draw R range", SCRIPT_PARAM_ONOFF, true)
			DrawMenu:addParam("ShowStun","Draw max stun range", SCRIPT_PARAM_ONOFF, true)
			
end

function Spells()
	-- Q
	
	ProQ = ProdictManager.GetInstance():AddProdictionObject(_Q, 900, math.huge, 0.500, 100, myHero)
		
	-- W
	ProW = ProdictManager.GetInstance():AddProdictionObject(_W, 1000, 1100, 0.550, 100, myHero)
		
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

function OnDraw()
	if DrawMenu.ShowQ or DrawMenu.ShowE then
		if qReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 800, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 800, ARGB(255,255,0,0))
		end
	end
	if DrawMenu.ShowW then
		if eReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 900, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 900, ARGB(255,255,0,0))
		end
	end

	if DrawMenu.ShowR then
		if rReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGB(255,255,0,0))
		end
	end
	if DrawMenu.ShowStun then
		if qReady and eReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, 1300, ARGB(255,0,255,0))
		else
		DrawCircle(myHero.x, myHero.y, myHero.z, 1300, ARGB(255,255,0,0))
		end
	end
	if MMenu.ManualStun then
		DrawCircle(mousePos.x, mousePos.y, mousePos.z, 250, ARGB(255,0,255,0))
	end
	local obj = GetWObject()

end


