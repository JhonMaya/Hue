<?php exit() ?>--by Fuggi 93.223.86.250
if myHero.charName ~= "Velkoz" then return end

local Config
local Minions
local FarmMinions
local JungleMinions
local JungleFarmMinions
local isattacking = 0
local AAanim = 0
local AAwind = 0
local lastAttack = 0
local timeToHit = 0
local nCC = 0
local CCtable = {}
local ResetAA = false
local Ult1Ready = false
local Ult2Ready = false
local AAreset = false
local Action = false
local CountDelay = false
local TimeCheck = false
local RTime = nil
local firstQ = false
local pPosW, pPosQ, pPosE
local qVec
local ultUp = false
local sbtwUlt = false
local pStack= 0
local enemyTable = {}

function OnLoad()
	PrintChat("<font color='#aaff34'>Fuggi's Vel'Koz - Tentacles for Pentakills</font>")
    Menu()
    Init()
    if VIP_USER then
        if Config.SMother.prodiction == true then
            failsafeProdiction = true
            require "Prodiction"
            Prod = ProdictManager.GetInstance()
            ProdW = Prod:AddProdictionObject(_W, w.Range, w.Speed, w.Delay, w.Width)
            ProdE = Prod:AddProdictionObject(_E, e.Range, e.Speed, e.Delay, e.Width)
            ProdQ = Prod:AddProdictionObject(_Q, w.Range, q.Speed, q.Delay, q.Width)
        else
            failsafeProdiction = false
            require "VPrediction"
            VP = VPrediction()
        end
    end
    
    for _, enemy in pairs(GetEnemyHeroes()) do    
        enemyTable[enemy.name] = {pStack = 0}
    end

    initDone = true
end

function OnProcessSpell(object,spellProc)
    if myHero.dead then return end
    if object.isMe and spellProc.name:lower():find("attack") then
        AAwind = spellProc.windUpTime
		AAanim = spellProc.animationTime
    end 
    if object.isMe and spellProc.name:lower():find("velkozq") then
        qVec = Vector(spellProc.endPos) - Vector(myHero)
        pqVec = qVec:perpendicular():normalized():__mul(q.Range)        
    end    
end

function Init()
    q = { Range = 1050, Delay = 0.25, Speed = 750, Width = 60}
    w = { Range = 1050, Delay = 0.25, Speed = 750, Width = 90}
    e = { Range = 850, Delay = 0.25, Speed = 750, Width = 200}
    r = { Range = 1575, Delay = 0.01, Speed = 20000, Width = 50}
    AArange = 525
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then 
		igniteSpell = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then 
		igniteSpell = SUMMONER_2
	else 
		igniteSpell = nil
	end
	 
    Minions = minionManager(MINION_ENEMY, q.Range)
    FarmMinions = minionManager(MINION_ENEMY, w.Range, myHero, MINION_SORT_HEALTH_ASC)
    JungleFarmMinions = minionManager(MINION_JUNGLE, w.Range, myHero, MINION_SORT_HEALTH_ASC)

    ts = TargetSelector(TARGET_NEAR_MOUSE, 1300, DAMAGE_MAGICAL)
    ts.name = "Vel'Koz"
    Config:addTS(ts)

	
end

function Menu()
	Config = scriptConfig("Velkoz", "velkoz")
    Config:addSubMenu("Harass Options", "SMharass")
    --Config:addSubMenu("Farm Options", "SMfarm")
    --Config:addSubMenu("Auto Ult Options", "SMult")
    Config:addSubMenu("Drawing Options", "SMdraw")
    Config:addSubMenu("SBTW Options", "SMsbtw")

    Config:addSubMenu("Other Options", "SMother")
	--Config:addParam("farm", "Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
    Config:addParam("smartfarm", "Last Hit", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
    --Config:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
	Config:addParam("sbtw", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--Config:addParam("flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, 88)
    Config.SMharass:addParam("underTower", "AutoHarass under Tower", SCRIPT_PARAM_ONOFF, true)
	Config.SMharass:addParam("autoQ", "Auto-Q", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))
    Config.SMharass:addParam("autoW", "Auto-W", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))
    Config.SMharass:addParam("autoE", "Auto-E", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))
	--Config.SMharass:addParam("HuseMove", "Move to Mouse", SCRIPT_PARAM_ONOFF, true)

    --[[
	Config.SMfarm:addParam("useQFarm", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.SMfarm:addParam("useWFarm", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.SMfarm:addParam("useAA", "Autoattack",SCRIPT_PARAM_ONOFF, true)
    Config.SMfarm:addParam("useMove", "Move to Mouse", SCRIPT_PARAM_ONOFF, true)
    ]]--
	Config.SMsbtw:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.SMsbtw:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.SMsbtw:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.SMsbtw:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)
    Config.SMsbtw:addParam("useAA", "Autoattack",SCRIPT_PARAM_ONOFF, true)
    Config.SMsbtw:addParam("useMove", "Move to Mouse", SCRIPT_PARAM_ONOFF, true)
    --Config.SMsbtw:addParam("atf", "animtimefactor",SCRIPT_PARAM_SLICE, 0.75, 0.00, 1, 3)
    --[[
    Config.SMult:addParam("autoRkillable", "Auto-R when Target is killable",SCRIPT_PARAM_ONOFF, true)
	Config.SMult:addParam("autoRkNumCast", "ignore safety params for KS",SCRIPT_PARAM_ONOFF, true)
    Config.SMult:addParam("autoRMin", "Auto-R Many Targets", SCRIPT_PARAM_ONOFF, false)
    Config.SMult:addParam("autoRnum", "# of Targets to Auto-R",SCRIPT_PARAM_SLICE, 4, 2, 5, 0)
    Config.SMult:addParam("autoRPercent", "Second R when above % Health",SCRIPT_PARAM_SLICE, 50, 1, 75, 0)
	
    Config.SMother:addParam("killsteal", "Killsteal", SCRIPT_PARAM_ONOFF, true)
	--Config.SMother:addParam("ksW", "Use W to KS",SCRIPT_PARAM_ONOFF, false)
    ]]
    Config.SMother:addParam("qHelper", "Use Q Helper", SCRIPT_PARAM_ONOFF, true)
    Config.SMother:addParam("usePackets", "Use Packets", SCRIPT_PARAM_ONOFF, true)
	Config.SMother:addParam("autoIg", "Auto Ignite Killable", SCRIPT_PARAM_ONOFF, true)
    Config.SMother:addParam("prodiction", "Prodiction = on, VPrediction = off", SCRIPT_PARAM_ONOFF, true)
    --[[
    Config.SMother:addParam("autoLevel", "AutoLevel (R>W>Q>E) Level 3 E", SCRIPT_PARAM_ONOFF, true)
	
    Config.SMdraw:addParam("drawText","Draw Text",SCRIPT_PARAM_ONOFF, true)
    ]]--
    --Config.SMharass:permaShow("autoW")

    Config.SMdraw:addParam("drawW","Draw Spellrange",SCRIPT_PARAM_ONOFF, true)
    Config.SMdraw:addParam("drawAA","Draw AA Range",SCRIPT_PARAM_ONOFF, true)
    Config.SMdraw:addParam("drawTarget","Draw Target",SCRIPT_PARAM_ONOFF, true)

    Config:permaShow("smartfarm")
    --Config:permaShow("farm")
    --Config:permaShow("flee")
    Config:permaShow("sbtw")
end

function checkItems()
end

local pPos = nil 

AddTickCallback(function()
        if VIP_USER and Target ~= nil then            
            if Config.SMother.prodiction == true or failsafeProdiction == true then
                local posW = ProdW:GetPrediction(Target)
                local posQ = ProdQ:GetPrediction(Target)
                local posE = ProdE:GetPrediction(Target)
                if posW ~= nil then 
                    pPosW = posW 
                else
                    pPosW = Target
                end
                if posQ ~= nil then 
                    pPosQ = posQ
                else
                    pPosQ = Target
                end
                if posE ~= nil then 
                    pPosE = posE
                else
                    pPosE = Target
                end
            elseif Config.SMother.prodiction == false or failsafeProdiction == false then
                pPosW, HitChance,  Position = VP:GetLineCastPosition(Target, w.Delay, w.Width, w.Range, w.Speed, myHero)
                pPosQ, HitChance,  Position = VP:GetLineCastPosition(Target, q.Delay, q.Width, q.Range, q.Speed, myHero)
                pPosE, HitChance = VP:GetPredictedPos(Target, e.Delay, e.Speed, myHero)                
            end
        elseif not VIP_USER and Target ~= nil then
            pPosW = Target
            pPosE = Target
            pPosQ = Target
        end 
end)

function OnTick()
    if initDone then
        if Config.SMother.prodiction ~= failsafeProdiction and predictionMessage == nil then
            predictionMessage = true
            PrintChat("<font color='#DD2222'>You changed your Prediction Library - please Reload for the changes to take effect!</font>")
        end

        if myHero.dead then
            ultUp = false
            sbtwUlt = false
        end
        ts:update();
        Target = ts.target
		
        QREADY = (myHero:CanUseSpell(_Q) == READY)
        EREADY = (myHero:CanUseSpell(_E) == READY)
        WREADY = (myHero:CanUseSpell(_W) == READY)
        RREADY = (myHero:CanUseSpell(_R) == READY)
        IREADY = (igniteSpell ~= nil and myHero:CanUseSpell(igniteSpell) == READY)
		AAREADY = CanAtk()
		        
        if Config.SMother.autoIg and IREADY then 
            AutoI()
        end

        if ultUp == true and sbtwUlt == true then
            sendMousePacket()
        end
     
        if firstQ == true and Config.SMother.qHelper then
            --[[tVec = Vector(myHero.x - Target.x, myHero.z - Target.z)
            dist = qVec:dist(tVec)
            length = math.sqrt((tVec:len2()*tVec:len2()) - (dist*dist))
            PrintChat(dist.."  "..length)
            qBreak = qVec:normalized():__mul(length)
            ]]
            --PrintChat(" " ..qObj.z)
            if GetMinionCollision(qObj, Vector(qObj)+pqVec , q.Width, {pPosQ}) or GetMinionCollision(qObj, Vector(qObj)-pqVec , q.Width, {pPosQ}) then 
                --PrintChat("TEST")
                CastSpell(_Q)
            end
        end


        if Config.sbtw then
            SBTW() 
        elseif Config.farm then
        elseif Config.flee then
        elseif Config.smartfarm then
            smartfarm()
        elseif Config.harass then
            harass()
        end
		
		if Config.SMother.TimeReset and os.clock() > lastAttack + (AAanim*2) + ((GetLatency()/2)*0.001) + 2 then
			AAreset = false
			ResetAA = false			
		end
    end
end

function ult()
    if ultUp == false and RREADY and Target ~= nil then 
        ultUp = true
        sbtwUlt = true
        R() 
    end
end

function sendMousePacket()
    if Target == nil then
        ultUp = false
        sbtwUlt = false        
    end
    if Target ~= nil and Target.dead == false then
        local p = CLoLPacket(229)
        p:EncodeF(myHero.networkID)
        p:Encode1(_R) -- 0-4 or 128-131
        p:EncodeF(Target.x)
        p:EncodeF(Target.y)
        p:EncodeF(Target.z)
        SendPacket(p)
    else
        local p = CLoLPacket(229)
        p:EncodeF(myHero.networkID)
        p:Encode1(_R) -- 0-4 or 128-131
        p:EncodeF(mousePos.x)
        p:EncodeF(0)
        p:EncodeF(mousePos.z)
        SendPacket(p)
    end
end

function harass()
end

function MoveToMouse()
        local MousePos = Vector(mousePos.x, mousePos.y, mousePos.z)
        local Position = myHero + (Vector(MousePos) - myHero):normalized()*300
        myHero:MoveTo(Position.x, Position.z)
end

function OnCreateObj(obj)
    --if GetDistance(obj) < 200 then PrintChat(obj.name) end
    if obj.name:lower():find("velkoz") and obj.name:lower():find("r_beam_end") then
        ultUp = true
    end
    if obj.name:lower():find("split") and obj.name:lower():find("velkoz_") and firstQ then 
        firstQ = false
    end
    if obj.name:lower():find("velkoz_") and obj.name:lower():find("q_mis") then
        qObj = obj
        firstQ = true
    end
end
 
function OnDeleteObj(obj)
    if obj.name:lower():find("velkoz") and obj.name:lower():find("r_beam_end") then
        ultUp = false
        sbtwUlt = false
    end
    if obj.name:lower():find("velkoz_") and obj.name:lower():find("q_mis") then -- Thanks Bunnysaurus <3
        firstQ = false
        qObj = nil
    end
end

function OnUpdateBuff(unit, buff)
    if buff.name == "velkozresearchstack" then
        if enemyTable[unit.name] ~= nil then
            enemyTable[unit.name].pStack = 2
        end
    end
end

function OnGainBuff(unit, buff)
    if buff.name == "velkozresearchstack" then
        if enemyTable[unit.name] ~= nil then
            enemyTable[unit.name].pStack = 1
        end
    end
end

function OnLoseBuff(unit, buff)
    if buff.name == "velkozresearchstack" then
        if enemyTable[unit.name] ~= nil then
            enemyTable[unit.name].pStack = 0
        end
    end
end

function OnDraw()
    if initDone == true then
        if Config.SMdraw.drawW then
                DrawCircle(myHero.x, myHero.y, myHero.z, w.Range, 0x5544AA)
        end
        if Config.SMdraw.drawAA then
                DrawCircle(myHero.x, myHero.y, myHero.z, AArange, 0x5544AA)
        end

    	if Target ~= nil and Config.SMdraw.drawTarget then
    		for i=1,3, .5 do
    			DrawCircle(Target.x, Target.y, Target.z, 125+i, 0xFF0000)
    		end
    	end
        --[[
            if Target~=nil then
                DrawCircle(pPosW.x, 0, pPosW.z, 50, 0xFF0000)
                DrawCircle(pPosQ.x, 0, pPosQ.z, 55, 0xFF00FF)
                DrawCircle(pPosE.x, 0, pPosE.z, 60, 0xFFFF00)
            end
        ]]
    end
end

function CanAtk()
    return os.clock() > lastAttack + AAanim + (GetLatency()/2)*0.001
end

function IsAtk()
    return os.clock() < lastAttack + AAwind + (GetLatency()/2)*0.001
end

function smartfarm() -- BY LittleRedEye Edited by Jarvis101
    selectMinion()
    for index, minion in pairs(FarmMinions.objects) do
        if ValidTarget(minion) then
            local aDmg = 1.08 * getDmg("AD", minion, myHero)
            if GetDistance(minion) <= AArange then
                if minion.health < aDmg and CanAtk() then
                    Attack(minion)
                    break
                elseif not IsAtk() then
                    MoveToMouse()
                end
            end
        end
        break
    end
    if not IsAtk() then MoveToMouse()  end
end

function SBTW()
	if Target ~= nil then
		local TargetDistance = GetDistance(Target)
		--local CombatRoutine = analyzeCombatDmg(Target)

		if ultUp == false or sbtwUlt == false then
            if Config.SMsbtw.useW and WREADY and TargetDistance < w.Range then
    			W(pPosW)
    		end
            if Config.SMsbtw.useE and EREADY and TargetDistance < (e.Range+e.Width) then
    			E(pPosE)
    		end
            if Config.SMsbtw.useQ and QREADY and firstQ == false and TargetDistance < q.Range then
                Minions:update()
                if not GetMinionCollision(myHero, pPosQ, q.Width, Minions.objects) then 
                    Q(pPosQ)
                end
            end
            local rDmg = 5 * getDmg("R", Target, myHero, 0)
            if enemyTable[Target.name].pStack>=1 then 
                rDmg = rDmg + 25 + (10 * myHero.level)
            end
            if Config.SMsbtw.useR and Target.health < rDmg and RREADY and TargetDistance < (r.Range-200) and TargetDistance > 200 then
                heroVec = Vector(myHero)
                targetVec = Vector(Target)
                mouseVec = Vector(mousePos)
                angle = heroVec:angleBetween(mouseVec, targetVec)
                if angle < 12 then
                    ult()
                end
            end

            if Config.SMsbtw.useAA and TargetDistance < AArange then
    			Action = false
                if CanAtk() and not AAreset then
    				ResetAA = true
    				Action = true
    				Attack(Target)
    			elseif IsAtk() and AAreset then
    				Action = true
    				AAreset = false
    				Attack(Target)
    			end
    			if not Action and CanAtk() then
    				AAreset = false
    				ResetAA = false
    			end
    		end
    		
            if Config.SMsbtw.useMove and not IsAtk() then
                myHero:MoveTo(mousePos.x, mousePos.z) 
            end 
        end
    else
    	if Config.SMsbtw.useMove and not IsAtk() then
            myHero:MoveTo(mousePos.x, mousePos.z) 
        end
    end
end

function Attack(unit)
	CountDelay = true
    if VIP_USER and Config.SMother.usePackets then
		lastAttack = os.clock()
        --Packet("S_MOVE", {sourceNetworkId = myHero.networkID, type = 2, x = unit.x, y = unit.z}):send()
        myHero:Attack(unit)
    else
		lastAttack = os.clock()
        myHero:Attack(unit)
    end
end


function Q(unit)
	if VIP_USER and Config.SMother.usePackets then
        Packet("S_CAST", {spellId = _Q, toX=unit.x, toY=unit.z, fromX=unit.x, fromY=unit.z}):send()
	else
		CastSpell(_Q)
	end
end

function W(unit)
    if VIP_USER and Config.SMother.usePackets then
        Packet("S_CAST", {spellId = _W, toX=unit.x, toY=unit.z, fromX=unit.x, fromY=unit.z}):send()
    else
        CastSpell(_W, unit.x, unit.z)
    end
end

function E(unit)
    if VIP_USER and Config.SMother.usePackets then
        Packet("S_CAST", {spellId = _E, fromX=unit.x, fromY=unit.z, toX=unit.x, toY=unit.z}):send()
    else
        CastSpell(_E, unit)
    end
end

function R()
	if VIP_USER and Config.SMother.usePackets then
        Packet("S_CAST", {spellId = _R}):send()
	else
		CastSpell(_R)
	end
end

function AutoI()
	if IREADY then
		for i = 1, heroManager.iCount,1 do
			local eTarget = heroManager:getHero(i)
			if ValidTarget(eTarget) and GetDistance(eTarget) < 600 and eTarget.health <= (50 + (20 * myHero.level)) then
				CastSpell(igniteSpell, eTarget)
			end
		end
	end
end


function OnSendPacket(p)
    --[[
    packet=Packet(p)
    PrintChat(packet:get('name'))
    --packet:block()
    
    if p:DecodeF() == myHero.networkID then 
        PrintChat(""..p.header) 
        PrintChat(""..p.size)
        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
        PrintChat(""..p:Decode1())        
    end
    if p.header == 229 and p:DecodeF() == myHero.networkID then
    end
    ]]--
end

function selectMinion()
    FarmMinions:update()
    JungleFarmMinions:update()
    local distance = w.Range
    for index, minion in pairs(FarmMinions.objects) do
        if ValidTarget(minion) then
            check = GetDistance(minion)
            if check < distance then 
                distance = check
                farmMinion = minion 
            end
        end
    end 
    for index, minion in pairs(JungleFarmMinions.objects) do
        if ValidTarget(minion) then
            check = GetDistance(minion)
            if check < distance then 
                distance = check
                farmMinion = minion 
            end
        end
    end 
    return farmMinion
end