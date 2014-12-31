<?php exit() ?>--by Jus 187.10.176.226
if not myHero.charName == "Diana" then return end

--packets
-- q = 0x99 - spell pos 0x00
-- r = 0x99 - spell pos 0x03

--------------------------------
local menu						=	nil 
local ts 						=	nil
local Target 					=	nil
local pred 						=	nil
local predR 					=	nil
local myPlayer 					=	GetMyHero()
--------------------------------
local lastAttack, lastWindUpTime, lastAttackCD = 0,0,0

function LoadMenu()
	menu = scriptConfig("[Diana by Jus]", "DianaJus")

	menu:addSubMenu('[Combo]', "combo")
	menu.combo:addParam("", "- Combo Settings -", SCRIPT_PARAM_INFO, "")
	menu.combo:addParam("mode", "Q and R mode", SCRIPT_PARAM_LIST, 1, { "R", "Q"})
	menu.combo:addParam("key", "Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
end

function Configuration()
	ts = TargetSelector(TARGET_LESS_CAST, 1000, DAMAGE_MAGIC, true, true)
	pred = TargetPredictionVIP(800, 1500, 0.25, 75, myPlayer) -- range 900, width 75, speed 1500, delay 0.5
	predR = TargetPredictionVIP(800, 1500, 0.25, 75, myPlayer) -- range 900, width 75, speed 1500, delay 0.5
end

function OnLoad()
	LoadMenu()
	Configuration()
	print("Diana Loaded")
end

function SendQ()
	if Target ~= nil then
		local Pos = pred:GetPrediction(Target)
		if Pos then
			--local newPos = Pos + (Vector(myPlayer) - Pos):normalized()*(GetDistance(Pos)*-1)
			CastSpell(_Q, Pos.x, Pos.z)
		end
	end
end

function SendR()
	if Target ~= nil then
		local Pos = predR:GetPrediction(Target)
		if Pos then
			CastSpell(_R, Target)
		end
	end
end

local qTick = 0
local rTick = 0

function OnSendPacket(p)
	if Target == nil then return end
	if p.header == 0x99 then
		p.pos = 1
		
		if not p:DecodeF() == myPlayer.networkID then return end
		local spell = p:Decode1()
		-- combo mode
		if menu.combo.mode == 1 and spell == 0x03 then -- R
			--rTick = GetTickCount() + GetLatency()/2
			--if GetTickCount() + GetLatency()/2 - rTick > 0.01 then
				SendQ()
				--if myPlayer:CanUseSpell(_R) == READY then SendR() end
			--end
				-- DelayAction(function() SendQ() end, rTick)
				 print('1. Packet')
		elseif menu.combo.mode == 2 and spell == 0x00 then -- Q
			DelayAction(function() if TargetHaveBuff("dianamoonlight", Target) then SendR() end end, 0.5)
		end
	end
end

function GetMyTarget()
	ts:update()
	if ValidTarget(ts.target) then return ts.target end
end

function heroCanMove()
    return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime)
end 
 
function timeToShoot()  
  return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD +20)
end 

function moveToCursor()  
  if GetDistance(mousePos) >= 300 then
    local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()*600
    myPlayer:MoveTo(moveToPos.x, moveToPos.z)
  end   
end

function OrbWalk(myTarget)
    if ValidTarget(myTarget) and GetDistance(myTarget) <= myPlayer.range then
        if timeToShoot() then
         myPlayer:Attack(myTarget)
        elseif heroCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end

--DianaArc = Q
--DianaTeleport = R

function OnProcessSpell(unit, spell)
	if unit ~= nil and unit.isMe then 
		if spell.name:lower():find("attack") then          
            --[[orbwalk]]             
            lastAttack      = GetTickCount() - GetLatency() / 2
            lastWindUpTime  = spell.windUpTime * 1000 -- can move
            lastAttackCD    = spell.animationTime * 1000 -- can attack
        end
        if Target ~= nil and menu.combo.mode == 1 or 2 then

        	if spell.name == "DianaTeleport" then        		
        		
        		SendQ()
        		
        		--print('2. Process')        		
        	end
        	if spell.name == "DianaArc" then
        		--local TimeOutQ = GetDistance(Target, myPlayer)/1500
        		--print(tostring(TimeOutQ))
        		SendR()        		
        	end
        end
    end
end

-- function OnGainBuff(unit, buff) -- dianamoonlight
-- 	if unit ~= nil and Target ~= nil and unit.type == myPlayer.type and unit.team == Target.team then
-- 		print(buff.name)
-- 	end
-- end



function OnTick()
	Target = GetMyTarget()
	if menu.combo.key then
		if menu.combo.mode == 1 then
			SendR()			
		elseif menu.combo.mode == 2 then
			SendQ()
		end
		OrbWalk(Target)
	end
end
