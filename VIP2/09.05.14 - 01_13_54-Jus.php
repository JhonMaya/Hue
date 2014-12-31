<?php exit() ?>--by Jus 189.69.28.112
if myHero.charName ~= "Riven" or not VIP_USER then return end

local version = "2.33"

local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Jusbol/scripts/master/SimpleRivenRelease.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."SimpleRivenRelease.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH
 
function _AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>Riven, I'm not a Bunny:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, "/Jusbol/scripts/master/VersionFiles/Riven.version")
    if ServerData then
        ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
        if ServerVersion then
            if tonumber(version) < ServerVersion then
                _AutoupdaterMsg("New version available"..ServerVersion)
                _AutoupdaterMsg("Updating, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
               _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        _AutoupdaterMsg("Error downloading version info")
    end
end
--[[honda update function]]

-------Variables-------
local myPlayer                                  			= 	GetMyHero()
local Passive, temUltimate, AA, UsandoHP        			= 	false, false, true, false
local TickTack, qTick, lTick, rTick, jTick, shieldTick      = 	0, 0 ,0, 0, 0, 0
local Target, selectedTarget                    			= 	nil, nil
local lastAttack, lastWindUpTime, lastAttackCD 				= 	0, 0, 0
local IgniteSpell                              				=   {spellSlot = "SummonerDot", slot = nil, range = 600, ready = false}
local BarreiraSpell                             			=   {spellSlot = "SummonerBarrier", slot = nil, range = 0, ready = false}
-------Variables-------
local aa = false
-------BENCHMARK Variables-------
local PassiveIndicator = 0
---------------------------------

local function Spell()
	local summonerTable	=	{SUMMONER_1, SUMMONER_2}
	local spells_		=	{IgniteSpell, BarreiraSpell}
	for i=1, #summonerTable do
		for a=1, #spells_ do
			if myPlayer:GetSpellData(summonerTable[i]).name:find(spells_[a].spellSlot) then 
				spells_[a].slot = summonerTable[i]
			end
		end
	end
end

function Others()   
    menu:addParam("Version", "Version Info:", SCRIPT_PARAM_INFO, version)
    Spell()    
    enemyMinions    	= minionManager(MINION_ENEMY, 550, myPlayer, MINION_SORT_HEALTH_ASC)
    Jungle          	= minionManager(MINION_JUNGLE, 550, myPlayer, MINION_SORT_MAXHEALTH_DEC)
    menu.combo.key  	= false
    menu.combo.skills.r = true
    PrintChat("<font color=\"#6699ff\"><b>Riven, I'm not a Bunny by Jus</b></font>")
end

local function MenuCombo()
    menu:addSubMenu("[Combo System]", "combo")
    menu.combo:addSubMenu("[Skills Settings]", "skills")
    menu.combo.skills:addParam("q", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("w", "Use (W) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("e", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.skills:addParam("r", "Use (R) in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))
    menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
end

local function MenuSpell()
    menu.combo:addSubMenu("[Multi-Skill System]", "multi")
    menu.combo.multi:addParam("qe", "Try (Q-E) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("ew", "Try (E-W) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("qr", "Try (Q-R) in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.multi:addParam("er", "Try (E-R) in Combo", SCRIPT_PARAM_ONOFF, true)
end

local function MenuExtraCombo()
    menu.combo:addSubMenu("[Extra Combo]", "extracombo")
    menu.combo.extracombo:addParam("delayInfo", "[Triple Q-AA Settings]", SCRIPT_PARAM_INFO, "")
    menu.combo.extracombo:addParam("mode", "Delay Mode: ", SCRIPT_PARAM_LIST, 2, { "Manual", "Auto"})
    menu.combo.extracombo:addParam("smart", "Triple Q-AA Delay", SCRIPT_PARAM_SLICE, 1000, 100, 3000, 0)   
    menu.combo.extracombo:addParam("", "", SCRIPT_PARAM_INFO, "") 
    menu.combo.extracombo:addParam("ultimateinfo", "[Ultimate Settings]", SCRIPT_PARAM_INFO, "")
    menu.combo.extracombo:addParam("autostartRnumber", "Auto First (R) if enemys # >=", SCRIPT_PARAM_SLICE, 2, 1, 4, 0)    
    menu.combo.extracombo:addParam("autostartRhealth", "Health Main Target to Start (R)", SCRIPT_PARAM_SLICE, 30, 10, 100, 0)    
    menu.combo.extracombo:addParam("autostartRrange", "Auto (R) with enemys in Range", SCRIPT_PARAM_SLICE, 400, 125, 550, 0)
    menu.combo.extracombo:addParam("ksultimate", "Try KS with Ultimate in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.extracombo:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.combo.extracombo:addParam("forcepassive", "[Force Passive Settings]", SCRIPT_PARAM_INFO, "")
    menu.combo.extracombo:addParam("q", "Force passive with (Q)", SCRIPT_PARAM_ONOFF, false)
    menu.combo.extracombo:addParam("w", "Force passive with (W)", SCRIPT_PARAM_ONOFF, false)
    menu.combo.extracombo:addParam("e", "Force passive with (E)", SCRIPT_PARAM_ONOFF, false)
    menu.combo.extracombo:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.combo.extracombo:addParam("antiover", "Use Overkill Protection", SCRIPT_PARAM_ONOFF, false)
    menu.combo.extracombo:addParam("ignite", "Use Smart Ignite", SCRIPT_PARAM_ONOFF, true)
    menu.combo.extracombo:addParam("items", "Use Items in Combo", SCRIPT_PARAM_ONOFF, true)
    menu.combo.extracombo:addParam("targetrange", "Range to Auto Target", SCRIPT_PARAM_SLICE, 800, 550, 1000, 0)
    menu.combo.extracombo:addParam("autow", "Auto (W) if # enemy >=", SCRIPT_PARAM_SLICE, 2, 1, 4, 0)
     --menu.combo.extracombo:addParam("gapflash", "Flash in Target if enemy health < %", SCRIPT_PARAM_SLICE, 20, 0, 30, 0)
end

local function MenuFarm()
    menu:addSubMenu("[Farm System]", "farm")
    menu.farm:addParam("lasthit", "Last Hit Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
    menu.farm:addParam("lineclear", "Line Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
    menu.farm:addParam("clearjungle", "Jungle Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
    menu.farm:addParam("shieldfarm", "Last hit with Shield", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
    menu.farm:addSubMenu("[Extra Settings]", "extrafarm")
    menu.farm.extrafarm:addParam("delay", "Extra Delay to Hit Minions", SCRIPT_PARAM_SLICE, 360, -300, 2000, 0)
    menu.farm.extrafarm:addParam("jungles", "[Jungle Skills]", SCRIPT_PARAM_INFO, "SET")
    menu.farm.extrafarm:addParam("q", "Use (Q) in Jungle", SCRIPT_PARAM_ONOFF, true)
    menu.farm.extrafarm:addParam("w", "Use (w) in Jungle", SCRIPT_PARAM_ONOFF, true)
    menu.farm.extrafarm:addParam("e", "Use (E) in Jungle", SCRIPT_PARAM_ONOFF, true)
end

local function MenuExtra()
    menu:addSubMenu("[Others System]", "extra")
    menu.extra:addParam("systemextra", "Use Extra System", SCRIPT_PARAM_ONOFF, true)
    menu.extra:addParam("hp", "Auto HP Potion if Health < %", SCRIPT_PARAM_SLICE, 70, 0, 90, 0)
    menu.extra:addParam("barrier", "Auto Barrier if Health < %", SCRIPT_PARAM_SLICE, 40, 0, 90, 0)
    menu.extra:addParam("eTurret", "Auto (E) if get Aggro by Turret", SCRIPT_PARAM_ONOFF, true)
    menu.extra:addParam("jump", "Jump/Run Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
end

local function MenuDraw()
    menu:addSubMenu("[Draw System]", "draw")        
    menu.draw:addParam("Q", "Draw (Q) range", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("W", "Draw (W) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("E", "Draw (E) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("R", "Draw (R) range", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.draw:addParam("passivecount", "Draw Passive Count", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("targettext", "Draw Text in Target", SCRIPT_PARAM_ONOFF, true)
end


local function MenuSystem()
	menu:addSubMenu("[General System]", "system")
	menu.system:addParam("orb", "Enable Jus Orbwalk", SCRIPT_PARAM_ONOFF, true)
	menu.system:addParam("sida", "Enable Sida/MMA support", SCRIPT_PARAM_ONOFF, true)
	menu.system:addParam("packet", "Use Packet", SCRIPT_PARAM_ONOFF, true)
    menu.system:addParam("update", "Auto Update", SCRIPT_PARAM_ONOFF, true)
 end

function OnLoad()
    menu = scriptConfig("Riven by Jus", "JusRivenJusRivenJus")

    MenuCombo() --skills and combo key
    MenuSpell() --multi skill / OnProcessSpell settings
    MenuExtraCombo()    --NoAACombo, items in combo, ignite if killable
    MenuFarm()  --farm sttuff       
    MenuExtra() --potions/barrier
    MenuDraw()  --draw ranges   
    MenuSystem()--orbwalk/sida/mma
    Others()    --others
    --MenuPermaShow()
end

function getTrueRange()
     return myPlayer.range + GetDistance(myPlayer.minBBox)
end

function EnemyInRange(enemy, range)
         if ValidBBoxTarget(enemy, range) then
                return true
        end
    return false
 end

function getHitBoxRadius(hero_)
    return GetDistance(hero_.minBBox, hero_.maxBBox)/2
end

function ThisIsReal(myTarget) -- < myPlayer.range
    local range = GetDistance(myTarget) - getHitBoxRadius(myTarget) - getHitBoxRadius(myPlayer)
    return range
end

function RangeWithUltimate()
    local myRange = myPlayer.range
    if temUltimate then
        return myRange + 75
    else
        return myRange
    end
    return myRange
end

local function DelayCalc()
	local mode 		=	menu.combo.extracombo.mode
	local mDelay	=	menu.combo.extracombo.smart
	if mode == 1 then
		return mDelay
	end
	if mode == 2 then
		local Total = (1/myPlayer.attackSpeed*1000) + GetLatency()/2 --GetTickCount() + GetLatency()/2 +
        --print(tostring(Total))
  --       if Total < 790 then Total = 790 end	
		-- if Total > 3000 then Total = 3000 end
		return Total
	end
end

function OnSendPacket(p)
    local delay_ 	=   menu.combo.extracombo.smart
    local qForce	=	menu.combo.extracombo.q
    local wForce	=	menu.combo.extracombo.w
    local eForce	=	menu.combo.extracombo.e 
    local useP_		=	menu.system.packet   
    local myPacket  =   Packet(p)
    local table 	= 	{_Q, _W, _E}
    --local range_  =   getHitBoxRadius(Target)
    local tTarget   =   ValidTarget(Target) 
    local pTick     =   qTick
    if myPacket:get('name') == 'S_CAST' then
        if useP_ and myPacket:get('spellId') == _Q then    
            p.pos = 1
            p = CLoLPacket(65)
            p:EncodeF(myPlayer.networkID)
            p:Encode1(0)
            p.dwArg1 = 0
            p.dwArg2 = 0
            SendPacket(p)           
        end
    	for i=1, #table do        
        	if myPacket:get('spellId') == table[i] and tTarget then
        		if table[i] == _Q and qForce then        	               	 
	            	local del 	=	DelayCalc()
	            	--local finalTick	=	pTick + del                              
		                if ThisIsReal(Target) <= myPlayer.range and GetTickCount() + GetLatency()/2 < pTick - del then  --700                                               
		                    myPacket:block()
                            myPlayer:Attack(Target)                    
		                end
	            end
	            if table[i] == _W and wForce and Passive then
	            	if ThisIsReal(Target) <= myPlayer.range then                                                 
	                    myPacket:block()                    
	                end
	            end
	            if table[i] == _E and eForce and Passive then
	            	if ThisIsReal(Target) <= myPlayer.range then                                                 
	                    myPacket:block()                    
	                end
	            end
            end
			
        -- p = CLoLPacket(71)
        -- p:EncodeF(myPlayer.networkID)
        -- p:Encode1(0)
        -- p.dwArg1 = 0
        -- p.dwArg2 = 0
        -- SendPacket(p)
	        
        end
    end
end


-- function OnRecvPacket(p)
--  --local myPacket = Packet(p)
--  p.pos = 1
--  p = CLoLPacket(0x65)
--  p:EncodeF(myPlayer.networkID)
--  p:Encode1(0)
--  p.dwArg1 = 0
--  p.dwArg2 = 0
--  SendPacket(p)
-- end


function OnGainBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then Passive       = true PassiveIndicator = buff.stack end
        if buff.name:lower():find("rivenwindslashready") then temUltimate   = true end
        if buff.name:lower():find("regenerationpotion")  then UsandoHP      = true end
    end
end

function OnUpdateBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then PassiveIndicator = buff.stack end
    end
end

function OnLoseBuff(unit, buff)
    if unit.isMe then
        if buff.name:lower():find("rivenpassiveaaboost") then Passive       = false 
            if buff.stack == nil then PassiveIndicator = 0 end end
        if buff.name:lower():find("rivenwindslashready") then temUltimate   = false end
        if buff.name:lower():find("regenerationpotion")  then UsandoHP      = false end
    end
end

-- items
local Items = {
["Brtk"]        =       {ready = false, range = 450, SlotId = 3153, slot = nil},
["Bc"]          =       {ready = false, range = 450, SlotId = 3144, slot = nil},
["Rh"]          =       {ready = false, range = 400, SlotId = 3074, slot = nil},
["Tiamat"]      =       {ready = false, range = 300, SlotId = 3077, slot = nil},
["Hg"]          =       {ready = false, range = 700, SlotId = 3146, slot = nil},
["Yg"]          =       {ready = false, range = 410, SlotId = 3142, slot = nil},
["RO"]          =       {ready = false, range = 500, SlotId = 3143, slot = nil},
["SD"]          =       {ready = false, range = myPlayer.range, SlotId = 3131, slot = nil},
["MU"]          =       {ready = false, range = myPlayer.range, SlotId = 3042, slot = nil} }
local HP_MANA     = { ["Hppotion"] = {SlotId = 2003, ready = false, slot = nil} }
local FoundItems  = {}

-- cast items
local function CheckItems(tabela)
    for ItemIndex, Value in pairs(tabela) do
        Value.slot = GetInventorySlotItem(Value.SlotId)
            if Value.slot ~= nil and (myPlayer:CanUseSpell(Value.slot) == READY) then
            FoundItems[#FoundItems+1] = ItemIndex
        end
    end
end

function CastCommonItem()
    local items_suv =   menu.extra.systemextra
    if not items_suv then return end
    CheckItems(Items)
    if #FoundItems ~= 0 then
        for i, Items_ in pairs(FoundItems) do
            if ValidTarget(Target) then                            
                if GetDistance(Target) <= Items[Items_].range then
                    if Items_ == "Brtk" or Items_ == "Bc" then
                        CastSpell(Items[Items_].slot, Target)
                    else
                        CastSpell(Items[Items_].slot)
                    end
                end
            end
            FoundItems[i] = nil
        end    
    end
end
 
function CastSurviveItem()
    local items_suv =   menu.extra.systemextra
    if not items_suv then return end
    CheckItems(HP_MANA)    
    local hp_                       = menu.extra.hp   
    local barrier_                  = menu.extra.barrier
    local myPlayerhp_               = (myPlayer.health / myPlayer.maxHealth *100)
    if #FoundItems ~= 0 then        
        for i, HP_MANA_ in pairs(FoundItems) do
            if HP_MANA_ == "Hppotion" and myPlayerhp_ <= hp_ and not InFountain() and not UsandoHP then
               CastSpell(HP_MANA[HP_MANA_].slot)
            end
            FoundItems[i] = nil
        end
        if BarreiraSpell.slot ~= nil and myPlayerhp_ <= barrier_ and not InFountain() then
            CastSpell(BarreiraSpell.slot)
        end
    end
end

-- function AttackAA(myTarget)
--     if ValidTarget(myTarget) and ThisIsReal(myTarget) <= myPlayer.range and GetTickCount() >= WhenAAAgain then
--         myPlayer:Attack(myTarget)
--     end
-- end


local function CastQ(myTarget)   
    local useq      =   menu.combo.skills.q      
    if useq then
        if GetDistance(myTarget) <= 550 and not Passive then
            qTick = GetTickCount() + GetLatency() / 2                    
            CastSpell(_Q, myTarget.x, myTarget.z) --- lastAttack
        end --((1/myPlayer.attackSpeed)*1000)
        if GetDistance(myTarget) <= 500 and (GetTickCount() + GetLatency() / 2) - qTick > DelayCalc() - 21 then            
            qTick = GetTickCount() + GetLatency() / 2            
            CastSpell(_Q, myTarget.x, myTarget.z)               
        elseif (GetTickCount() + GetLatency() / 2) - qTick > 3500 and GetDistance(myTarget) <= 500 or ThisIsReal(myTarget) > myPlayer.range then                    
            qTick = GetTickCount() + GetLatency() / 2            
            CastSpell(_Q, myTarget.x, myTarget.z)
        end
        -- if (GetTickCount() + GetLatency() / 2) - qTick > 4000 and GetDistance(myTarget) <= 500 then
        --     --print("lastAttack - lastWindUpTime") --lastAttack - lastWindUpTime
        --     qTick = GetTickCount() + GetLatency() / 2           
        --     CastSpell(_Q, myTarget.x, myTarget.z)
        -- end
        -- local del = DelayCalc()
        -- local moreDelay = lastAttack-lastAttackCD/(myPlayer.attackSpeed)
        -- if GetTickCount() + GetLatency() / 2 > qTick + moreDelay and GetDistance(myTarget) <= 550 then --and heroCanMove() then
        --     qTick = GetTickCount() + GetLatency() / 2
        --     CastSpell(_Q, myTarget.x, myTarget.z)
        -- end
       
    end
end

local function CastW(myTarget)   
    local usew      =   menu.combo.skills.w  
    local useItems_ =   menu.combo.extracombo.items
    local autow_    =   menu.combo.extracombo.autow   
    if usew then
        local numberE   =   CountEnemyHeroInRange(280)
        if numberE >= autow_ then
            CastSpell(_W)
        end
        if GetDistance(myTarget) <= 282 then            
        	CastSpell(_W)            
        end 
    end
    if useItems_ then CastCommonItem() end
end

local function CastE(myTarget)    
    local usee      =   menu.combo.skills.e   
    if usee then
        if GetDistance(myTarget) <= 410 and myPlayer:CanUseSpell(_Q) ~= READY then
            CastSpell(_E, myTarget.x, myTarget.z)       
        end
    end
end

function TryksR(myTarget)
	if not temUltimate then return end
	local useks_	=	menu.combo.extracombo.ksultimate
	local enemy 	= 	GetEnemyHeroes()
	local rlvl      =   myPlayer:GetSpellData(_R).level	
	if useks_ and ValidTarget(myTarget) then		
		for i, Targets in pairs(enemy) do			
			if ValidTarget(Targets) then
				local DamageR   =   getDmg("R", myTarget, myPlayer, 2)
				if Targets.health < myTarget.health and Targets.health <= DamageR and GetDistance(Targets) <= 900 then
					--if temUltimate and GetTickCount() + GetLatency() / 2 > rTick + 12000 then
					  	CastSpell(_R, Targets.x, Targets.z)
					--end
				end
			end
		end
	end
end

function CastR(myTarget)   
    local user      =   menu.combo.skills.r 
    local HealthE	=	menu.combo.extracombo.autostartRhealth
    local rRange 	=	menu.combo.extracombo.autostartRrange
    local rEnemys	=	menu.combo.extracombo.autostartRnumber
    if user then        
        local DamageR   =   getDmg("R", myTarget, myPlayer, 2)
        local nEnemys	=	CountEnemyHeroInRange(rRange)
        local enemyHea 	=	(myTarget.health / myTarget.maxHealth * 100)
        if GetDistance(myTarget) <= rRange and enemyHea <= HealthE and nEnemys >= rEnemys and not temUltimate then
            CastSpell(_R)
        end              
        if temUltimate and myTarget.health <= DamageR then
            CastSpell(_R, myTarget.x, myTarget.z)
        end
        if temUltimate then
        	TryksR(myTarget)
        end 
        if temUltimate and GetTickCount() + GetLatency() / 2 > rTick + 14000 then           
            CastSpell(_R, myTarget.x, myTarget.z)
        end
        
    end
end

-- function OnAttack(unit)
--   if unit.isMe and ValidTarget(Target) and GetDistance(Target) <= 550 then
--   	print("OnAttack")
--     CastSpell(_Q, Target.x, Target.z)
--   end
-- end

function OnAnimation(unit, animationname)
    local ComboON       =   menu.combo.key
    local qForce		=	menu.combo.extracombo.q
    local wForce		=	menu.combo.extracombo.w
    local eForce		=	menu.combo.extracombo.e   
    local animaT        =   { "spell1a", "spell1b", "spell1c" } --, "spell2"}
    if unit.isMe and ComboON and ValidTarget(Target) then
        if animationname:lower():find("attack") then            
        --lastAttack = GetTickCount() - GetLatency()/2                          	
            CastQ(Target)
                     
            -- CastW(Target)                    
            -- CastE(Target)           
        end
        -- if animationname:lower():find("spell1a") then bench = GetTickCount() end
	    for i=1, #animaT do
	        if animationname:lower():find(animaT[i]) then --GetTickCount() + GetLatency()/2 < DelayCalc() + 1000 then -- and ThisIsReal(Target) <= myPlayer.range then                       
	            aa = false
                --if animaT[i] == "spell1a" or animaT[i] == "spell1b" or animaT[i] == "spell1c" then
                myPlayer:Attack(Target)
                --print("AA")                    
	           	--end
	           	-- if animaT[i] == "spell2" and wForce then
	           	-- 	myPlayer:Attack(Target)
	           	-- end
	           	-- if animaT[i] == "spell3" and eForce then
	           	-- 	myPlayer:Attack(Target)
	           	-- end
	        end
	    end

        -- if animationname:lower():find("spell1c") then
        --     print("Benchmark Triple Q-AA: "..tostring( (GetTickCount() - bench) ).." mseconds")
        -- end
    end     
end

function OnProcessSpell(unit, spell)
	local jump_	=	menu.extra.jump
	if jump_ then return end    
    if unit.isMe then      
        --------use---------
        local useq          =   menu.combo.skills.q
        local usew          =   menu.combo.skills.w
        local usee          =   menu.combo.skills.e
        local user          =   menu.combo.skills.r
        -------multi skill-------
        local qe_           =   menu.combo.multi.qe
        local ew_           =   menu.combo.multi.ew
        local qr_           =   menu.combo.multi.qr
        local er_           =   menu.combo.multi.er
        local useItems_     =   menu.combo.extracombo.items
        local ComboON       =   menu.combo.key       

        if spell.name:lower():find("attack") then
            aa = true
            --[[orbwalk]]             
            lastAttack      = GetTickCount() - GetLatency() / 2
            lastWindUpTime  = spell.windUpTime * 1000
            lastAttackCD    = spell.animationTime * 1000
            -- print("lastAttack: "..tostring(lastAttack))
            -- print("windUpTime: "..tostring(lastWindUpTime))
            -- print("lastAttackCD: "..tostring(lastAttackCD))
        end      
        if ComboON and ValidTarget(Target) then
	        if spell.name:lower():find("riventricleave") then 
                             
                qTick = GetTickCount() + GetLatency() / 2
               
                 --    local newPos    =   Target + (Vector(spell.startPos) - Target):normalized()
	                -- Packet('S_MOVE', {x = newPos.x, y = newPos.z}):send() --spell.startPos.x      
	                if qr_ and user then
	                  
                            CastR(Target)
	                                   
	                end
	          
	        end

	        if spell.name:lower():find("rivenmartyr") then
	        	if useItems_ then CastCommonItem() end  
                 	
	        end    

	        if spell.name:lower():find("rivenfeint") then
	                if er_ and user then	               
	                	CastR(Target)       
	                end
	                if usew and ew_ then
	                    CastW(Target)
	                    if useItems_ then CastCommonItem() end
	                end               
	        end

	        if spell.name:lower():find("rivenfengshuiengine") then
	            rTick = GetTickCount() + GetLatency() / 2              
	        end
	    end
    end 
    local autoE 		=	menu.extra.eTurret    
    if autoE and unit.type == "obj_AI_Turret" and spell.target.networkID == myPlayer.networkID and GetDistance(spell.endPos) <= 900 then
    	CastSpell(_E, mousePos.x, mousePos.z)
    end
end

function JumpQ()
	CastSpell(_Q, mousePos.x, mousePos.z)		
	CastSpell(_E, mousePos.x, mousePos.z)
	if GetDistance(mousePos) > myPlayer.range then
	 	local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()* 300
    	myPlayer:MoveTo(moveToPos.x, moveToPos.z)
	end   	
end

function BurnYouShit(myTarget)
    local useI  =   menu.combo.extracombo.ignite
    local slot_ =   IgniteSpell.slot
    if useI and slot_ ~= nil and myPlayer:CanUseSpell(slot_) == READY and ValidTarget(myTarget) and GetDistance(myTarget, myPlayer) <= IgniteSpell.range and not TargetHaveBuff(IgniteSpell.spellSlot, myTarget) then
        local iDmg  =   getDmg("IGNITE", myTarget, myPlayer, myPlayer.level)
        if myTarget.health <= iDmg then
            CastSpell(slot_, myTarget)
        end
    end
end

function LineFarm()  
    local delay_    =       menu.farm.extrafarm.delay
    local myOrb		=		menu.system.orb    
    local items_0   =      	menu.combo.extracombo.items  
    enemyMinions:update() 
    -- if GetTickCount() + GetLatency() / 2 > lTick and myOrb == true then
    --     myPlayer:MoveTo(mousePos.x, mousePos.z)
    -- end  
    local tock = 0
    Minion = enemyMinions.objects[1]
    if Minion ~= nil and not Minion.dead then                  
        if aa and GetDistance(Minion, myPlayer) <= 550 and not Passive then
            tock = GetTickCount() + GetLatency()/2
            CastSpell(_Q, Minion.x, Minion.z)
        elseif GetTickCount() + GetLatency()/2 - tock > 4000 then
            tock = GetTickCount() + GetLatency()/2
            CastSpell(_Q, Minion.x, Minion.z)
        end 
        CastSpell(_E, Minion.x, Minion.z)
        if GetDistance(Minion, myPlayer) <= 380 then CastSpell(_W) end
        if items_0 then CastCommonItem() end        
        -- if ValidTarget(Minion) and GetTickCount() + GetLatency() / 2 > lTick then
        --     myPlayer:Attack(Minion)
        --     lTick = GetTickCount() + GetLatency() / 2  + 360 + delay_
        -- end     
    end 
    if myOrb then OrbWalk(Minion) end    
end

local function FarmWithShield()	
    local delay_    =   menu.farm.extrafarm.delay
    local myOrb		=	menu.system.orb
    enemyMinions:update()
    if GetTickCount() + GetLatency() / 2 > TickTack and myOrb == true then
        myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
    minion = enemyMinions.objects[1]
    if minion ~= nil and not minion.dead then
        local aDmg = getDmg("AD", minion, myPlayer)
        if ValidTarget(minion) and minion.health <= aDmg  and EnemyInRange(minion, getTrueRange()) and GetTickCount() + GetLatency() / 2 > TickTack then
            CastSpell(_E, minion.x, minion.z)
            myPlayer:Attack(minion)
            TickTack = GetTickCount() + GetLatency() / 2 + 360 + delay_
        end
    end
end

function FarmChicken()
    local delay_    =   menu.farm.extrafarm.delay
    local myOrb		=	menu.system.orb
    enemyMinions:update()
    if GetTickCount() + GetLatency() / 2 > TickTack and myOrb == true then
        myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
    minion = enemyMinions.objects[1]
    if minion ~= nil and not minion.dead then
        local aDmg = getDmg("AD", minion, myPlayer)
        if ValidTarget(minion) and minion.health <= aDmg  and EnemyInRange(minion, getTrueRange()) and GetTickCount() + GetLatency() / 2 > TickTack then
            myPlayer:Attack(minion)
            TickTack = GetTickCount() + GetLatency() / 2 + 360 + delay_
        end
    end
end

function JungleBitch()
    Jungle:update()
    local useq          =   menu.farm.extrafarm.q
    local usew          =   menu.farm.extrafarm.w
    local usee          =   menu.farm.extrafarm.e  
    local delay_        =   menu.farm.extrafarm.delay
    local KillMobs      =   menu.farm.clearjungle
    local myOrb			=	menu.system.orb
    if not KillMobs then return end
    Jungle:update()
    -- if GetTickCount() + GetLatency() / 2 > jTick and myOrb == true then
    --     myPlayer:MoveTo(mousePos.x, mousePos.z)
    -- end 
    Minion = Jungle.objects[1]
    if Minion ~= nil and not Minion.dead then
        if useq and aa and not Passive then
           CastSpell(_Q, Minion.x, Minion.z)
        end
        if usee then
            CastSpell(_E, Minion.x, Minion.z)
        end
        if usew and GetDistance(Minion, myPlayer) <= 380 and not Passive then
            CastSpell(_W)
        end
        -- if ValidTarget(Minion) and GetTickCount() + GetLatency() / 2 > jTick then
        --     myPlayer:Attack(Minion)
        --     jTick = GetTickCount() + GetLatency() / 2  + 360 + delay_
        -- end                
    end
    if myOrb then OrbWalk(Minion) end  
end

function SidaMMA()
	local ComboON       =   menu.combo.key
	local FarmChicken_  =   menu.farm.lasthit
    local LineFarm_     =   menu.farm.lineclear
	local farmShiels	=	menu.farm.shieldfarm
	local Integration	=	menu.system.sida
	local myOrb			=	menu.system.orb
	if ComboON or FarmChicken_ or LineFarm_ or farmShiels then
		if Integration then
			if _G.MMA_Loaded then
				myOrb = false			
				_G.MMA_AbleToMove = true
				_G.MMA_AttackAvailable = true
			end
			if _G.AutoCarry then
				myOrb = false
				_G.AutoCarry.Orbwalker = true		
				_G.AutoCarry.CanMove = true
				_G.AutoCarry.CanAttack = true
			end
		else
			myOrb = true
			if _G.MMA_Loaded then							
				_G.MMA_AbleToMove = false
				_G.MMA_AttackAvailable = false
			end
			if _G.AutoCarry then							
				_G.AutoCarry.CanMove = false
				_G.AutoCarry.CanAttack = false
			end
		end	
	end		
end

local function F5Target()
    selectedTarget  = GetTarget()
    local found 	= false
    local range_    = menu.combo.extracombo.targetrange
    local inimigos 	= nil
    local Enemy 	= nil
    local Compare	= nil
    local finalTarget	=	nil
    if range_ == 0 then range_ = 850 end

    if ValidTarget(selectedTarget) and selectedTarget.type == "obj_AI_Hero" and selectedTarget.type ~= "obj_AI_Turret" and selectedTarget.type ~= "obj_AI_Minion" then
    	return selectedTarget
    end

    if not selectedTarget then
        inimigos  = GetEnemyHeroes()
        for i, Enemy in pairs(inimigos) do             	  
            local basedmg   = 100
            local myDmg     = (myPlayer:CalcDamage(Enemy, 200) or 0)            
            if Enemy ~= nil and ValidTarget(Enemy) and GetDistance(Enemy) <= range_ and Enemy.type == "obj_AI_Hero" and Enemy.type ~= "obj_AI_Turret" and Enemy.type ~= "obj_AI_Minion" then
            	local finalDmg	=	Enemy.health / myDmg
                if finalDmg < basedmg then
                	found = true                	
                    return Enemy
                end		                
            else
            	found = false
            end		  
        end
    end
    if not selectedTarget and not found then
    	local mouseTarget = nil
    	inimigos  = GetEnemyHeroes()
    	for i, Enemy in pairs(inimigos) do
    		local distancMouse = GetDistance(mousePos, Enemy)
	    	if Enemy ~= nil and ValidTarget(Enemy) and distancMouse <= 150 and Enemy.type == "obj_AI_Hero" and Enemy.type ~= "obj_AI_Turret" and Enemy.type ~= "obj_AI_Minion" then
	    		return Enemy
	    	end
	    end
	end	
	finalTarget = selectedTarget or Enemy
    if ValidTarget(finalTarget) and finalTarget.type == "obj_AI_Hero" and finalTarget.type ~= "obj_AI_Turret" and finalTarget.type ~= "obj_AI_Minion" then
	return finalTarget
    end
end

local KillText = nil

local function DamageCombo(myTarget)	
	if not ValidTarget(myTarget) then return end
	--local bench = os.clock()
	local skillTable	=	{}
	local skills 		=	{}
	if IgniteSpell.slot ~= nil then
		skillTable	=	{ "Q", "W", "R", "IGNITE"}
		skills 		=	{ _Q, _W, _R, IgniteSpell.slot}
	else
		skillTable	=	{ "Q", "W", "R"}
		skills 		=	{ _Q, _W, _R}
	end
	local possible		=	{}
	local TotalDamage	=	0
	local qDmg, rDmg, wDmg, iDmg = 0, 0, 0, 0
	for i=1, #skillTable do
		for a=1, #skills do
			if i==a and myPlayer:CanUseSpell(skills[a]) == READY then
				table.insert(possible, skillTable[i])
			end
		end
	end
	for b=1, #possible do
		if possible[b] == "Q" then
			qDmg = getDmg(possible[b], myTarget, myPlayer, 1)
			qDmg = qDmg + getDmg(possible[b], myTarget, myPlayer, 2)
			qDmg = qDmg + getDmg(possible[b], myTarget, myPlayer, 3)
			if qDmg >= Target.health then KillText = "(Q) can kill" else KillText = "Harass" end
		end
		if possible[b] == "R" then
			rDmg = getDmg(possible[b], myTarget, myPlayer, 1)
			rDmg = rDmg + getDmg(possible[b], myTarget, myPlayer, 2)
			if rDmg >= Target.health then KillText = "(R) can kill" else KillText = "Harass" end	
		end
		if possible[b] == "W" then
			wDmg =  getDmg(possible[b], myTarget, myPlayer)
			if wDmg >= Target.health then KillText = "(W) can kill" else KillText = "Harass" end
		end
		if possible[b] == "IGNITE" then
			iDmg = getDmg(possible[b], myTarget, myPlayer)
			if iDmg >= Target.health then KillText = "(I) can kill" else KillText = "Harass" end
		end
		possible[b] = nil --clear table		
	end	
	local pDmg = getDmg("P", myTarget, myPlayer)*3
	if qDmg + wDmg + rDmg + iDmg + pDmg > myTarget.health then KillText = "All in" else KillText = "Harass" end
	local needTable = {qDmg, wDmg, rDmg, iDmg}	
	for i=1, #needTable do		
		if needTable[i] ~= 0 then			
			if needTable[i] == qDmg then
				CastQ(myTarget)
			end
			if needTable[i] == wDmg then
				CastW(myTarget)
			end			
			if needTable[i] == iDmg or rDmg then
				local d1 = (iDmg or 0)
				local d2 = (rDmg or 0)
                --local d3 = pDmg
				-- if myTarget.health <= d3 then
    --                 myPlayer:Attack(myTarget)
                if myTarget.health <= d1 and myTarget.health > d2 then
                    BurnYouShit(myTarget)
                end
                if myTarget.health <= d2 and myTarget.health > d1 then
					CastR(myTarget)
				end
			end
		end
	end
end

function NormalCombo(myTarget)
	local anti_over	=	menu.combo.extracombo.antiover
    if ValidTarget(myTarget) then
        if anti_over then
        	DamageCombo(myTarget)
        	CastE(myTarget)
        else	
            CastR(myTarget)
            CastQ(myTarget)
            CastE(myTarget)     
            CastW(myTarget)
        end
    end   
end

function OnWndMsg(Msg, Key)
	if myPlayer.dead then return end
	local UltiActive	=	menu.combo.skills.r
	if Msg == WM_LBUTTONDOWN or WM_RBUTTONDOWN then		
		Target = F5Target()
	end
	-- if Msg == KEY_DOWN and Key == string.byte("S") then
	-- 	if UltiActive then
	-- 		print("<font color=\"#6699ff\"><b>Ultimate: </b></font> <font color=\"#FFFFFF\">OFF</font>")
	-- 	else
	-- 		print("<font color=\"#6699ff\"><b>Ultimate: </b></font> <font color=\"#FFFFFF\">ON</font>")
	-- 	end
	-- end	

	-- if Msg == KEY_DOWN and Key == string.byte("H") then
	-- 	CalcTest()
	-- end

end

function OrbWalk(myTarget)
    if ValidTarget(myTarget) and ThisIsReal(myTarget) <= myPlayer.range then
        if timeToShoot() then
            myPlayer:Attack(myTarget)
        elseif heroCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end

function heroCanMove()
    return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20)
end 
 
function timeToShoot()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD)
end 

function moveToCursor() 
	if GetDistance(mousePos) >= 260 then
 		local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()* 260
		myPlayer:MoveTo(moveToPos.x, moveToPos.z)
	end   
end
 
function OnTick()
    if myPlayer.dead then return end
    local ComboON       =   menu.combo.key
    local FarmChicken_  =   menu.farm.lasthit
    local LineFarm_     =   menu.farm.lineclear
    local myOrb			=	menu.system.orb
    local jump_			=	menu.extra.jump
    local farmShiels	=	menu.farm.shieldfarm
    local targettext_	=	menu.draw.targettext
    local RealTarget	=	nil    
    --local BeSmart     =   menu.combo.extracombo.smart      
    Target = F5Target()
    if ValidTarget(Target) and Target.type == "obj_AI_Hero" and Target.type ~= "obj_AI_Turret" and Target.type ~= "obj_AI_Minion" then
    	RealTarget = Target
    end
    if ComboON then       
        NormalCombo(RealTarget)        
        if myOrb then OrbWalk(RealTarget) end
    end
    SidaMMA()
    if FarmChicken_ then FarmChicken() end
    if LineFarm_ then LineFarm() end
    if farmShiels then FarmWithShield() end    
    JungleBitch()
    CastSurviveItem()   
    if ValidTarget(RealTarget) then 
        BurnYouShit(RealTarget)   
        CastW(RealTarget) 
    end
    if jump_ then JumpQ() end
    if not targettext_ then KillText = nil end
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]--
local function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
        radius = radius or 300
        quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
        quality = 2 * math.pi / quality
        radius = radius*.92
        local points = {}
        for theta = 0, 2 * math.pi + quality, quality do
                local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
                points[#points + 1] = D3DXVECTOR2(c.x, c.y)
        end
        DrawLines2(points, width or 1, color or 4294967295)
end
 
function DrawCircle2(x, y, z, radius, color)
        local vPos1 = Vector(x, y, z)
        local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
        local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
        local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
        if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
                DrawCircleNextLvl(x, y, z, radius, 1, color, 75)        
        end
end

function OnDraw()
    if myPlayer.dead then return end
    local qRange            =       275
    local wRange            =       260
    local eRange            =       385
    local rRange            =       900
    --[[menu draw]]
    local qDraw             =       menu.draw.Q
    local wDraw             =       menu.draw.W
    local eDraw             =       menu.draw.E
    local rDraw             =       menu.draw.R
    local targetDraw        =       menu.draw.target
    local targettext_		=		menu.draw.targettext
    local passive_          =       menu.draw.passivecount

    if qDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, qRange, ARGB(255, 255, 000, 000))
    end
    if wDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, wRange, ARGB(255, 000, 255, 000))
    end
    if eDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, eRange, ARGB(255, 251, 255, 000))
    end
    if rDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, rRange, ARGB(255, 255, 255, 000))
    end   
    if targetDraw and ValidTarget(Target) then
        for i=0, 3, 1 do
            DrawCircle2(Target.x, Target.y, Target.z, 80 + i , ARGB(255, 255, 000, 255))              
        end
    end
    if targettext_ and ValidTarget(Target) and KillText ~= nil and Target.type == "obj_AI_Hero" and Target.type ~= "obj_AI_Turret" then
    	DrawText3D(tostring(KillText), Target.x, Target.y, Target.z, 16, ARGB(255,255,255,000), true)
    end
    if passive_ then
        DrawText3D("Passive Counter: "..tostring(PassiveIndicator), myPlayer.x - 50, myPlayer.y + 500, myPlayer.z, 16, ARGB(255,255,255,255))
    end
end

function OnUnload()
    local up_   =   menu.system.update
    AUTOUPDATE = up_
end