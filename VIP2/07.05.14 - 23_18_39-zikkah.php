<?php exit() ?>--by zikkah 83.84.221.160

require "VPrediction"
require "ImBeastyLib"

--Human
local Qh = { state=1, range=1500, speed=1300, delay=0.250, width=70, name = "JavelinToss"}	
local Wh = { state=1, range=900, speed=2000, delay=0.450, width=70, name = "Bushwhack"}
local Eh = { state=1, range=1500, speed=1300, delay=0.250, width=70,  name = "PrimalSurge"}
--Cougar
local Qc = { state=1, cd=0, time=0, name = "Takedown"}
local Wc = { state=1, range=375, name = "Pounce"}
local Ec = { state=1, range=350, name = "Swipe"}
-- Form
local R = { state=0, name = "AspectOfTheCougar"}

local human = true

local Target = nil


local VP = nil

local wait = false 
local pause = false
local attacking = false
local passive = false
local iSlot = nil
local iReady = false
local failsafe1 = false
local failsafe2 = false

local CustomTargets = {}
local heros = 0
local qManaHuman = 50
function OnLoad()
	print("nid")
	ImBeasty(true,true,true,true,true)
	ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 800, false, true)
	VP = VPrediction()
	MMenu = LibMenu.GetSub()
	MMenu:addParam("sep", "----- [ Hotkey Settings ] -----", SCRIPT_PARAM_INFO, "")
	MMenu:addParam("Combo","Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	MMenu:addParam("sep", "----- [ Combo Settings ] -----", SCRIPT_PARAM_INFO, "")
	MMenu:addParam("useQc","COUGAR:Use Q", SCRIPT_PARAM_ONOFF, true)
	MMenu:addParam("useWc","COUGAR:Use W", SCRIPT_PARAM_ONOFF, true)
	MMenu:addParam("useEc","COUGAR:Use E", SCRIPT_PARAM_ONOFF, true)
	MMenu:addParam("useWh","HUMAN:Use E", SCRIPT_PARAM_ONOFF, true)
	MMenu:addParam("switch","SWITCH FORMS", SCRIPT_PARAM_ONOFF, true)


	MMenu:addParam("aSpear","Auto Spear[TOGGLE]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("M"))
	MMenu:addParam("aHeal","Auto Heal[TOGGLE]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("N"))

	MMenu:addParam("aHealMana","Auto Heal min. mana %", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)

end



function OnTick()
	GlobalInfo()
	AutoSpells()
	if MMenu.Combo then
	--	OrbWalking.Activate(Target)


		if ValidTarget(Target) then
			CastW(Target)
			CastE(Target)
			if MMenu.switch then
				if GetDistance(Target) < 600 and human and rReady then
					CastSpell(_R)
				elseif not human and rReady and GetDistance(Target) > 600 and Qh.state == 1  then
					CastSpell(_R)
				end
			end
		end
	end

end


function AutoSpells()
	if MMenu.aHeal then
		local manapct = (myHero.mana / myHero.maxMana)*100
		if Eh.state == 1 and human and manapct >= MMenu.aHealMana then
			local healamount = 15+(35*myHero:GetSpellData(_E).level)+(myHero.ap*.7) 
			if myHero.maxHealth - myHero.health > healamount then
				CastSpell(_E, myHero)
			end
		end
	end

	if MMenu.aSpear then
		bestTarget = nil
		local possibleTargets = {}
		local targets = 0
		for _ , enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) and GetDistance(enemy) < 1600 then
				local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(enemy, Qh.delay, Qh.width, Qh.range, Qh.speed, myHero, true)
				local a = GetDistance(CastPosition)
				if HitChance > 1 and a <= Qh.range then
					local qDmg = getDmg("Q", enemy, myHero)
					if a >  myHero.range then
						local b = Qh.range - myHero.range
						qDmg = qDmg*((((a-myHero.range)/b)*1.5)+1)
						local hpLeft = (enemy.health - qDmg)+60
						if bestTarget then
							if hpLeft < bestTarget.hp then
								bestTarget = {hero=enemy,hp=hpLeft,hc=HitChance,pos=CastPosition}
							end					
						else
							bestTarget = {hero=enemy,hp=hpLeft,hc=HitChance,pos=CastPosition}
						end
					end
				end
			end
		end

		if bestTarget and myHero.mana >= qManaHuman then
			if human then
				CastSpell(_Q, bestTarget.pos.x, bestTarget.pos.z)
			elseif bestTarget.hp < 0 then
				if GetDistance(bestTarget.pos) > 400 and MMenu.switch then
					CastSpell(_R)
				end
			end
		end
	end

end

function KillCombo()


end


function CastQ(target)


		if GetDistance(qpos) < Q.range then
			CastSpell(_Q, qpos.x, qpos .z)
			return true
		end
		return false	


end

function CastW(pos)
	if not human then
		if GetDistance(pos) > 240 and MMenu.useWc then
			direction  = Vector(myHero) + (Vector(pos) - Vector(myHero)):normalized() *400
			if Wc.state == 1 and not failsafe2 and not attacking then
			Packet('S_MOVE', {x = direction.x, y = direction.z, type=2}):send()
			DelayAction1(function()
			Packet("S_CAST", {spellId = _W, toX = mousePos.x, toY = mousePos.z, fromX = mousePos.x, fromY = mousePos.z}):send()
				end,0.1)

				failsafe2 = true
				DelayAction1(function() failsafe2 = false end, 0.3)

			end
		end
	else
		if MMenu.useWh then
			local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(pos, Wh.delay, Wh.width, Wh.range, Wh.speed, myHero, true)
			if Wh.state == 1 then
				CastSpell(_W, CastPosition.x, CastPosition.z)
			end
		end
	end
	--CastSpell(_W, pos.x, pos.z)
	
end

function CastE(pos)
	if not human then
		if GetDistance(pos) < 400 and MMenu.useEc then
			direction  = Vector(myHero) + (Vector(pos) - Vector(myHero)):normalized() *400

			if Ec.state == 1 and not failsafe1 and not attacking then
			Packet('S_MOVE', {x = direction.x, y = direction.z, type=2}):send()
			DelayAction1(function()
			Packet("S_CAST", {spellId = _E}):send()
				end,0.1)
			failsafe1 = true
			DelayAction1(function() failsafe1 = false end, 0.3)

			end
		end
	end

end

function CastR()
	CastSpell(_R, Target.x, Target.z)
end

function CastIgnite(target)
		Packet('S_CAST', {spellId = iSlot, targetNetworkId = target.networkID}):send()
end

function CastItems()
	if hxgReady and GetDistance(Target) < 600 then CastItem(3146, Target) return true end
	if bwcReady and GetDistance(Target) < 600 then CastItem(3144, Target) return true end
	if borkReady and GetDistance(Target) < 600 then CastItem(3153, Target) return true end
	if tmtReady and GetDistance(Target) < 400 then CastItem(3077) return true end
	if rvhReady and GetDistance(Target) < 400 then CastItem(3074) return true end
	return false
end




	

function GlobalInfo()
	ts:update()
	Target = ts.target

	qReady = myHero:CanUseSpell(_Q) == READY 
	wReady = myHero:CanUseSpell(_W) == READY 
	eReady = myHero:CanUseSpell(_E) == READY 
	rReady = myHero:CanUseSpell(_R) == READY 
--[[
	if human then
		if qReady then Qh.state = 1 end
		if wReady then Wh.state = 1 end
		if eReady then Eh.state = 1 end

	else
		if qReady then Qc.state = 1 end
		if wReady then Wc.state = 1 end
		if eReady then Ec.state = 1 end
	end]]


	if ValidTarget(Target) then


	end

	if human then
		qManaHuman = myHero:GetSpellData(_Q).mana
	end

	if myHero:GetSpellData(_Q).name == "JavelinToss" or myHero:GetSpellData(_W).name == "Bushwhack" or myHero:GetSpellData(_E).name == "PrimalSurge" then 
		human = true
	else
		human = false
	end
	if human then 
	--	if qReady then Qh.state = 1 end
	--	if wReady then Qh.state = 1 end
	--	if eReady then Qh.state = 1 end
	 	Qc.state = 1 Qc.time = 0 Qc.cd = 0 

	 	Wc.state = 1 

	 	Ec.state = 1 
	end
			
	if not human then
		--if myHero:CanUseSpell(_Q) == READY and not TargetHaveBuff("Takedown", myHero) then  Qc.state = 1 end
	end




	hxgReady = GetInventoryItemIsCastable(3146)
	bwcReady = GetInventoryItemIsCastable(3144)
	borkReady = GetInventoryItemIsCastable(3153)
	tmtReady = GetInventoryItemIsCastable(3077)
	rvhReady = GetInventoryItemIsCastable(3074)
    iSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
   	iReady = iSlot and myHero:CanUseSpell(iSlot) == READY or false

end

function DamageCalcs()


end


function OnWndMsg(msg, key)

end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name == Qh.name then
			local ready = myHero:GetSpellData(_Q).cd
			Qh.state = 0
			Qh.time = GetGameTimer()+ready
			DelayAction1(function() Qh.state = 1 end, ready)

		elseif spell.name == Wh.name then
			local ready = myHero:GetSpellData(_W).cd
			Wh.state = 0
			Wh.time = GetGameTimer()+ready
			DelayAction1(function() Wh.state = 1 end, ready)	

		elseif spell.name == Eh.name then
			local ready = myHero:GetSpellData(_E).cd
			Eh.state = 0
			Eh.time = GetGameTimer()+ready
			DelayAction1(function() Eh.state = 1 end, ready)	

		elseif spell.name == Qc.name then
			local ready = myHero:GetSpellData(_Q).cd
			Qc.state = 2
			Qc.cd = ready
			Qc.time = 10+GetGameTimer()
			DelayAction1(function() if Qc.state == 2 then Qc.state = 0 Qc.time = Qc.cd+GetGameTimer() DelayAction(function() if Qc.state == 0 then Qc.state = 1 end end, Qc.cd) end end, 10)

		elseif spell.name == "NidaleeTakedownAttack" then
			local ready = myHero:GetSpellData(_Q).cd
			Qc.state = 0
			Qc.time = ready+GetGameTimer()
			DelayAction1(function() Qc.state = 1 end, ready)
		elseif spell.name == Wc.name then
			local ready = myHero:GetSpellData(_W).cd
			Wc.state = 0
			Wc.time = GetGameTimer()+ready
			DelayAction1(function() Wc.state = 1 end, ready)	

		elseif spell.name == Ec.name then
			local ready = myHero:GetSpellData(_E).cd
			Ec.state = 0
			Ec.time = GetGameTimer()+ready
			DelayAction1(function() Ec.state = 1 end, ready)	

		elseif spell.name:find("Attack") or spell.name:find("attack") then
			attacking = true
		end
	end
end


function OnSendPacket(p)


end

function OnCreateObj(obj)
	if GetDistance(obj) < 100 and obj.name == "Nidalee_Hunter_Glow" then
		human = true
	end

end



function OnDeleteObj(obj)
	

end

function OnGainBuff(unit, buff)
end

function OnLoseBuff(unit, buff)

end

function OnDraw()

	    DrawText("QH:"..tostring(Qh.state),13,100 ,00 ,ARGB(255,0,255,0))   
	    DrawText("WH:"..tostring(Wh.state),13,100 ,10 ,ARGB(255,0,255,0))   
	    DrawText("EH:"..tostring(Eh.state),13,100 ,20 ,ARGB(255,0,255,0))   
	    DrawText("QC:"..tostring(Qc.state),13,130 ,00 ,ARGB(255,0,255,0))   
	    DrawText("WC:"..tostring(Wc.state),13,130 ,10 ,ARGB(255,0,255,0))   
	    DrawText("EC:"..tostring(Ec.state),13,130 ,20 ,ARGB(255,0,255,0))   


--[[
    DrawText(tostring(W.state),50,500 ,740 ,ARGB(255,0,255,0))    
    DrawText(tostring(E.state),50,500 ,780 ,ARGB(255,0,255,0))      
    DrawText(tostring(R.state),50,500 ,820 ,ARGB(255,0,255,0))    
    DrawText(tostring(attacking),50,500 ,860 ,ARGB(255,0,255,0))       
    DrawText(tostring(passive),50,500 ,900 ,ARGB(255,0,255,0))     

   		DrawText(tostring(hitchance),50,800 ,800 ,ARGB(255,0,255,0))     
   	]]
   	--[[
    if Q.target then
   		DrawText(tostring(Q.target.type),50,500 ,940 ,ARGB(255,0,255,0))     
   	end
	if Target then
		DrawCircle(Target.x, Target.y, Target.z, 200, ARGB(255,255,255,255))
	end
	if Q.state == 1 then
		DrawCircle(myHero.x, myHero.y, myHero.z, 975, ARGB(255,255,255,255))
	elseif Q.state == 3 then
		DrawCircle(myHero.x, myHero.y, myHero.z, 1100, ARGB(255,255,255,255))
	end
		
	DrawCircle(myHero.x, myHero.y, myHero.z, 700, ARGB(255,0,255,0))
]]

	DrawCircle(myHero.x, myHero.y, myHero.z, Qh.range, ARGB(255,0,255,0))



end

function OnWindUp(unit, spell)
	attacking = false
	if unit.type =="obj_AI_Hero" and  MMenu.Combo and not human and MMenu.useQc then
		if Qc.state == 1 and MMenu.useQc then CastSpell(_Q) end
		if Wc.state == 1 and MMenu.useWc then CastW(unit) end
		if Ec.state == 1 and MMenu.useEc then CastE(unit) end
	end
end