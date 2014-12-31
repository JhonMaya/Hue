<?php exit() ?>--by Hellsing 91.96.212.36
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

local myPlayer          =       GetMyHero()

--[[spells]]
local IgniteSpell   =   {spellSlot = "SummonerDot", slot = nil, range = 600, ready = false}
local BarreiraSpell =   {spellSlot = "SummonerBarrier", slot = nil, range = 0, ready = false}
--

function OnLoad()
        menu = scriptConfig("Riven by Jus", "JusRiven")
        menu:addParam("version", "Version info: ", SCRIPT_PARAM_INFO, "2.12")

        menu:addSubMenu("[Combo System]", "combo")
        menu.combo:addParam("", "", SCRIPT_PARAM_INFO, "")
        menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
        
        menu.combo:addSubMenu("[Multi Skill Settings]", "mskill")
        menu.combo.mskill:addParam("ew", "Use (E-W) gap closer", SCRIPT_PARAM_ONOFF, true)
        menu.combo.mskill:addParam("eq", "Use (E-Q) gap closer", SCRIPT_PARAM_ONOFF, true)
        menu.combo.mskill:addParam("er", "Use (E-R) in combo", SCRIPT_PARAM_ONOFF, true)
        
        menu.combo:addSubMenu("[Passive Usage Settings]", "passive")    
        menu.combo.passive:addParam("qp", "Force passive in every (Q)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.passive:addParam("ep", "Force passive in every (E)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.passive:addParam("wp", "Force passive in every (W)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.passive:addParam("rp", "Force passive with (R)", SCRIPT_PARAM_ONOFF, false)
        
        menu.combo:addSubMenu("[Gap Closer Settings]", "gap")   
        menu.combo.gap:addParam("qgap", "Range to start with (Q)", SCRIPT_PARAM_SLICE, 550, 275, 1100, 0)
        menu.combo.gap:addParam("qqg", "Range to stop with (Q)", SCRIPT_PARAM_SLICE, 127, 127, 500, 0)
        menu.combo.gap:addParam("eg", "Range to start with (E)", SCRIPT_PARAM_SLICE, 385, 126, 550, 0)
        menu.combo.gap:addParam("eeg", "Range to stop with (E)", SCRIPT_PARAM_SLICE, 126, 125, 550, 0)
        menu.combo.gap:addParam("wg", "Range to use (W)", SCRIPT_PARAM_SLICE, 260, 125, 260, 0)
        menu.combo.gap:addParam("rmin", "Range to start use (R)", SCRIPT_PARAM_SLICE, 125, 125, 900, 0)

        menu.combo:addSubMenu("[Animation Canceling]", "cancel")
        menu.combo.cancel:addParam("q", "Use Movement in (Q)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.cancel:addParam("packet", "Use Packet in (Q)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.cancel:addParam("packettype", "Choose Packet Type", SCRIPT_PARAM_LIST, 1, { "Type 1", "Type 2", "Type 3", "Type 4"})
        menu.combo.cancel:addParam("advanced", "Use Auxiliary System", SCRIPT_PARAM_ONOFF, true)
        menu.combo.cancel:addParam("advsmooth", "Auxiliary Smoothness", SCRIPT_PARAM_SLICE, 0.2, 0, 10, 2)
        
        menu.combo:addSubMenu("[Skills Settings]", "skills")    
        menu.combo.skills:addParam("q", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
        menu.combo.skills:addParam("w", "Use (W) in Combo", SCRIPT_PARAM_ONOFF, true)
        menu.combo.skills:addParam("e", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
        menu.combo.skills:addParam("r", "Use (R) in Combo", SCRIPT_PARAM_ONOFF, true)
        
        menu.combo:addSubMenu("[Extra Settings]", "extra")      
        menu.combo.extra:addParam("items", "Use Items after (W)", SCRIPT_PARAM_ONOFF, true)
        menu.combo.extra:addParam("antiDi", "Anti Double Ignite", SCRIPT_PARAM_ONOFF, true)
        menu.combo.extra:addParam("ignite", "Ignite if enemy health < %", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
        menu.combo.extra:addParam("ulthealth", "(R) if enemy health < %", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)    

        menu:addSubMenu("[Extra System]", "extra")
        menu.extra:addParam("items", "Use Extra Items System", SCRIPT_PARAM_ONOFF, true)                        
        menu.extra:addParam("hp", "Auto Use HP Potions", SCRIPT_PARAM_ONOFF, true)
        menu.extra:addParam("hppercent", "Use HP if my Health < %", SCRIPT_PARAM_SLICE, 60, 10, 90, -1)
        menu.extra:addParam("barrier", "Auto Barrier", SCRIPT_PARAM_ONOFF, true)
        menu.extra:addParam("barrierPercent", "Use Barrier if my Health < %", SCRIPT_PARAM_SLICE, 30, 10, 90, -1)
        menu.extra:addParam("", "", SCRIPT_PARAM_INFO, "")
        menu.extra:addParam("jump", "Jump Wall with (Q) [HOLD]", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
        --menu.extra:addParam("jungleKey", "Cast Combo in Jungle", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
        menu.extra:addParam("linerange", "Lane Minions Range", SCRIPT_PARAM_SLICE, 550, 250, 750, 0)
        menu.extra:addParam("lineKey", "Cast Combo in Lane Minions", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
        menu.extra:addParam("harass", "Manual Harass Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
        --[[draw]]
        menu:addSubMenu("[Draw System]", "draw")        
        menu.draw:addParam("Q", "Draw (Q) range", SCRIPT_PARAM_ONOFF, false)
        menu.draw:addParam("W", "Draw (W) range", SCRIPT_PARAM_ONOFF, true)
        menu.draw:addParam("E", "Draw (E) range", SCRIPT_PARAM_ONOFF, true)
        menu.draw:addParam("R", "Draw (R) range", SCRIPT_PARAM_ONOFF, false)    
        menu.draw:addParam("", "", SCRIPT_PARAM_INFO, "")
        menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
        --[[system]]
        menu:addSubMenu("[General System Settings]", "system")  
        menu.system:addParam("orbwalker", "Use Orbwalk", SCRIPT_PARAM_ONOFF, true)
        menu.system:addParam("orbsmooth", "Orbwalk Smoothness", SCRIPT_PARAM_SLICE, 130, 1, 260, 0)
        --[[spells]]
        if myPlayer:GetSpellData(SUMMONER_1).name:find(IgniteSpell.spellSlot) then IgniteSpell.slot = SUMMONER_1
        elseif myPlayer:GetSpellData(SUMMONER_2).name:find(IgniteSpell.spellSlot) then IgniteSpell.slot = SUMMONER_2 end        
        if myPlayer:GetSpellData(SUMMONER_1).name:find(BarreiraSpell.spellSlot) then BarreiraSpell.slot = SUMMONER_1
        elseif myPlayer:GetSpellData(SUMMONER_2).name:find(BarreiraSpell.spellSlot) then BarreiraSpell.slot = SUMMONER_2 end
        --[[target]]
        Ts                      =       TargetSelector(TARGET_NEAR_MOUSE, 900, DAMAGE_PHYSICAL, true)
        Ts.name         =       "Riven"
        menu:addTS(Ts)
        --JungleMinions         = minionManager(MINION_JUNGLE, 550, myPlayer, MINION_SORT_MAXHEALTH_DEC)
        MinionsInimigos = minionManager(MINION_ENEMY, 850, myPlayer, MINION_SORT_HEALTH_ASC)
        --

        --[[permaShow]]
        --menu:permaShow("version")
        menu.combo:permaShow("key")
        menu.extra:permaShow("harass")
        menu.extra:permaShow("lineKey") 
        menu.extra:permaShow("jump")

        PrintChat("<font color=\"#6699ff\"><b>Riven, I'm not a Bunny by Jus</b></font>")
end

local Target                                           	= nil
local aaboost, UsandoHP, UsandoRecall, temUltimate      = false, false, false, false
local lastAttack, lastWindUpTime, lastAttackCD          = 0, 0, 0
local myTrueRange                                                                       = myPlayer.range + GetDistance(myPlayer.minBBox)
local rTick                                                                                     = 0

function _OrbWalk(myTarget)
        local orbsmooth_        =       menu.system.orbsmooth   
        if ValidTarget(myTarget, orbsmooth_) then               
                if timeToShoot() then
                        myPlayer:Attack(myTarget)                       
                elseif heroCanMove()  then
                        moveToCursor()
                end
        else            
                moveToCursor() 
        end
end

function OwYew(myTarget)
        local orbsmooth_        =       menu.system.orbsmooth
        return aaboost and myTarget ~= nil and ValidTarget(myTarget, orbsmooth_ )
end

function heroCanMove()
        local orbsmooth_        =       menu.system.orbsmooth
        return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20 ) --and ValidTarget(Target) and GetDistance(Target) > orbsmooth_
end 
 
function timeToShoot()
        local orbsmooth_        =       menu.system.orbsmooth
        return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD) or timeToShoot2() --and ValidTarget(Target) and GetDistance(Target) <= orbsmooth_ 
end
function timeToShoot2()
        return OwYew(Target)
end 

function moveToCursor()
        local orbsmooth_        =       menu.system.orbsmooth
        if GetDistance(mousePos) > orbsmooth_ then
                local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized() * 250
                myPlayer:MoveTo(moveToPos.x, moveToPos.z)
        end 
end

--[[my send packet
function OnSendPacket(packet)
        local PacketCast        = Packet(packet)
        local PacketToSend      = CLoLPacket(71)
        if PacketCast:get('name') == 'S_CAST' and PacketCast:get('spellId') == _Q then
                PacketToSend.pos = 1
                PacketToSend:EncodeF(myPlayer.networkID)
                PacketToSend:Encode1(2)
                PacketToSend:Encode1(0)                 
                SendPacket(PacketToSend)
        end
end
]]

function OnSendPacket(p)
        local packetQ                   =       menu.combo.cancel.packet
        local qp_               =       menu.combo.passive.qp
        local ep_               =       menu.combo.passive.ep
        local wp_               =       menu.combo.passive.wp
        local rp_               =       menu.combo.passive.rp
        local orbsmooth_        =       menu.system.orbsmooth
        local valid                     =       ValidTarget(Target, orbsmooth_)
        local Packet                    =               Packet(p)
        
        if p.header == Packet.headers.S_CAST then                                  
                if Packet:get('spellId') == _Q then
                        if packetQ then                         
                                Emote() 
                        end
                        if qp_ and valid and OwYew(Target) then
                                Packet:block() 
                        end
                end
                if valid and OwYew(Target) then                 
                        if Packet:get('spellId') == _E and ep_ then Packet:block()  end
                        if Packet:get('spellId') == _W and wp_ then Packet:block()  end
                        if Packet:get('spellId') == _R and rp_ then Packet:block()  end
                end             
        end
end

function Emote()
        local packettype_       =       menu.combo.cancel.packettype
        
        if packettype_ == 1 then
                        
        p = CLoLPacket(65)
        p:EncodeF(myPlayer.networkID)
        p:Encode1(0)
        p.dwArg1 = 0
        p.dwArg2 = 0
        SendPacket(p)
    end
    if packettype_ == 2 then
        
        p = CLoLPacket(65)
        p:EncodeF(myPlayer.networkID)
        p:Encode1(2)
        p.dwArg1 = 1
        p.dwArg2 = 0
        SendPacket(p)
    end
    if packettype_ == 3 then
        
        p = CLoLPacket(0x47) --0x47
        p:EncodeF(myPlayer.networkID)
        p:Encode1(2)
        p.dwArg1 = 1
        p.dwArg2 = 0
        SendPacket(p)
    end
    if packettype_ == 4 then
        
        p = CLoLPacket(71)
        p:EncodeF(myPlayer.networkID)
        p:Encode1(2)
        p.dwArg1 = 1
        p.dwArg2 = 0
        SendPacket(p)
    end

end

function CastQ(myTarget)
        local valid     = ValidTarget(myTarget)
        local qReady    = myPlayer:CanUseSpell(_Q) == READY
        local qgap_             = menu.combo.gap.qgap --default 550
        local qgapStop_ = menu.combo.gap.qqg
        local useq_             = menu.combo.skills.q
        if valid and qReady and useq_ then
                --if not timeToShoot() then
                        --Packet('S_CAST', { spellId = _Q, x=myTarget.x, y=myTarget.z }):send()
                        --CastSpell(_Q, myTarget.x, myTarget.z)
                --end
                if GetDistance(myTarget) >= qgapStop_ and GetDistance(myTarget) <= qgap_ or not timeToShoot() then
                        --Packet('S_CAST', { spellId = _Q, x=myTarget.x, y=myTarget.z }):send()
                        CastSpell(_Q, myTarget.x, myTarget.z)
                end
        end
end

function KillEverybodyMothaFucker(myTarget)     
        local valid     =       ValidTarget(myTarget)
        --[[e skill]]
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local eg1               =       menu.combo.gap.eg
        local egStop_   =       menu.combo.gap.eeg
        local usee_             =       menu.combo.skills.e
        --[[w skill]]
        local wReady    =       myPlayer:CanUseSpell(_W) == READY
        local usew_             =       menu.combo.skills.w
        local wRange_   =       menu.combo.gap.wg
        --[[r skill]]
        local rReady    =       myPlayer:CanUseSpell(_R) == READY
        local eHealth   =       menu.combo.extra.ulthealth
        local user_             =       menu.combo.skills.r
        local rmin_             =       menu.combo.gap.rmin     
        if valid then

                local enemyHealth = (myTarget.health / myTarget.maxHealth * 100)

                CastQ(myTarget)

                if rReady and user_ then 

                        if temUltimate and enemyHealth <= eHealth then
                                --Packet('S_CAST', { spellId = _R, x=myTarget.x, y=myTarget.z }):send()
                                CastSpell(_R, myTarget.x, myTarget.z)                           
                        end     

                if GetDistance(myTarget) <= rmin_ then                          
                                CastSpell(_R)                                   
                        end             
                end

                if eReady and usee_ then
                        --if GetDistance(myTarget) <= eg1 then
                        --Packet('S_CAST', { spellId = _E, x=myTarget.x, y=myTarget.z }):send()
                ----    CastSpell(_E, myTarget.x, myTarget.z)
                --end
                if GetDistance(myTarget) >= egStop_ and GetDistance(myTarget) <= eg1 then
                        --Packet('S_CAST', { spellId = _E, x=myTarget.x, y=myTarget.z }):send()
                        CastSpell(_E, myTarget.x, myTarget.z)
                end
        end

                if wReady and usew_ and GetDistance(myTarget) <= wRange_ then
                CastSpell(_W)
        end 

        end
end

function updateTargetPlease()
        Ts:update()
        if Ts.target ~= nil and Ts.target.team ~= myPlayer.team and Ts.target.type == myPlayer.type then
                return Ts.target
        end
end

--[[ITEMS]]--
local Items = {
                        ["Brtk"]        =       {ready = false, range = 450, SlotId = 3153, slot = nil},
                        ["Bc"]          =       {ready = false, range = 450, SlotId = 3144, slot = nil},
                        ["Rh"]          =       {ready = false, range = 400, SlotId = 3074, slot = nil},
                        ["Tiamat"]      =       {ready = false, range = 400, SlotId = 3077, slot = nil},
                        ["Hg"]          =       {ready = false, range = 700, SlotId = 3146, slot = nil},
                        ["Yg"]          =       {ready = false, range = 150, SlotId = 3142, slot = nil},
                        ["RO"]          =       {ready = false, range = 500, SlotId = 3143, slot = nil}, 
                        ["SD"]          =       {ready = false, range = 150, SlotId = 3131, slot = nil},
                        ["MU"]          =       {ready = false, range = 150, SlotId = 3042, slot = nil}         
                        }
local HP_MANA                           = { ["Hppotion"] = {SlotId = 2003, ready = false, slot = nil}   }
local FoundItems                        = {}
--[[buffs]]
local BuffNames                         = {"rivenpassiveaaboost",
                                                                "regenerationpotion",                                                           
                                                                "recall",
                                                                "rivenwindslashready" }                                                         

function OnGainBuff(unit, buff) 
        if unit.isMe then                       
                for i=1, #BuffNames do
                        if buff.name:lower():find(BuffNames[i]) then
                                if BuffNames[i] == "rivenpassiveaaboost"                then    aaboost                 = true end
                                if BuffNames[i] == "regenerationpotion"                 then    UsandoHP                = true end                              
                                if BuffNames[i] == "recall"                                     then    UsandoRecall    = true end
                                if BuffNames[i] == "rivenwindslashready"                then    temUltimate             = true end      
                        end     
                end
        end
end

function OnLoseBuff(unit, buff) 
        if unit.isMe then                       
                for i=1, #BuffNames do
                        if buff.name:lower():find(BuffNames[i]) then
                                if BuffNames[i] == "rivenpassiveaaboost"                then    aaboost                 = false end
                                if BuffNames[i] == "regenerationpotion"                 then    UsandoHP                = false end                             
                                if BuffNames[i] == "recall"                                     then    UsandoRecall    = false end     
                                if BuffNames[i] == "rivenwindslashready"                then    temUltimate             = false end                     
                        end     
                end
        end
end

--[[cast Spells/items]]
function CheckItems(tabela)
        for ItemIndex, Value in pairs(tabela) do
                Value.slot = GetInventorySlotItem(Value.SlotId)                 
                        if Value.slot ~= nil and (myPlayer:CanUseSpell(Value.slot) == READY) then                               
                        FoundItems[#FoundItems+1] = ItemIndex   
                end
        end
end

function CastCommonItem()
        CheckItems(Items)
        if #FoundItems ~= 0 then
                for i, Items_ in pairs(FoundItems) do
                        if ValidTarget(Target) then                             
                                if GetDistance(Target) <= Items[Items_].range then 
                                        if      Items_ == "Brtk" or Items_ == "Bc" then
                                                CastSpell(Items[Items_].slot, Target)
                                        else                                    
                                                CastSpell(Items[Items_].slot)                                   
                                        end
                                end
                        end
                        FoundItems[i] = nil --clear table to optimaze
                end     
        end
end

function CastSurviveItem()
        CheckItems(HP_MANA)     
        local hp_                                       = menu.extra.hp 
        local hppercent_                        = menu.extra.hppercent  
        local myPlayerhp_                       = (myPlayer.health / myPlayer.maxHealth *100)   
        local barrier_                          = menu.extra.barrier
        local barrierPercent_           = menu.extra.barrierPercent     
        if #FoundItems ~= 0 then        
                for i, HP_MANA_ in pairs(FoundItems) do
                        if HP_MANA_ == "Hppotion" and myPlayerhp_ <= hppercent_ and not InFountain() and not UsandoHP then
                                CastSpell(HP_MANA[HP_MANA_].slot)
                        end                                     
                FoundItems[i] = nil
                end
                if BarreiraSpell.slot ~= nil and barrier_ and myPlayerhp_ <= barrierPercent_ and not InFountain() then
                        CastSpell(BarreiraSpell.slot)
                end 
        end
end

function CastIgnite(myTarget)           
        local AntiDoubleIgnite_ = menu.combo.extra.antiDi
        local ignite_                   =       menu.combo.extra.ignite
        if IgniteSpell.slot ~= nil and ValidTarget(myTarget, IgniteSpell.range) then    
                if AntiDoubleIgnite_ and TargetHaveBuff("SummonerDot", myTarget) then return end
                if AntiDoubleIgnite_ and not TargetHaveBuff("SummonerDot", myTarget) and myPlayer:CanUseSpell(IgniteSpell.slot) == READY then                   
                        local thealth = (myTarget.health / myTarget.maxHealth * 100)
                        if thealth <= ignite_ then
                                Packet('S_CAST', { spellId = IgniteSpell.slot, targetNetworkId = myTarget.networkID }):send()
                        end                     
                end
        end
end

--[[
function JungleFarm()   
        local qReady    =       myPlayer:CanUseSpell(_Q) == READY
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local wReady    =       myPlayer:CanUseSpell(_W) == READY
        JungleMinions:update()  
        if CountEnemyHeroInRange(550, myPlayer) == 0 then               
                for i, MinionJ in pairs(JungleMinions.objects) do                       
                        if ValidTarget(MinionJ, 550) then
                                myPlayer:Attack(Minion)
                                if eReady and GetDistance(MinionJ) <= 380 then
                                        Packet('S_CAST', { spellId = _E, x=MinionJ.x, y=MinionJ.z }):send()
                                end
                                if qReady and GetDistance(Minion) <= 550 then
                                        Packet('S_CAST', { spellId = _Q, x=MinionJ.x, y=MinionJ.z }):send()
                                end     
                                if wReady and GetDistance(Minion) <= 125 then
                                        Packet('S_CAST', { spellId = _W, }):send()
                                end                             
                        end     
                end             
        end
end
]]

function LineFarm()     
        if UnderTurret(myPlayer, true) then return end
        local qReady    =       myPlayer:CanUseSpell(_Q) == READY
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local wReady    =       myPlayer:CanUseSpell(_W) == READY
        local lineR_    =       menu.extra.linerange
        --[[items]]
        local items_0   =       menu.combo.extra.items  
        MinionsInimigos:update()
        for i, Minion in pairs(MinionsInimigos.objects) do              
                if Minion ~= nil and GetDistance(Minion) <= lineR_ then                         
                        myPlayer:Attack(Minion)
                        if eReady and GetDistance(Minion) <= 380 then
                                Packet('S_CAST', { spellId = _E, x=Minion.x, y=Minion.z }):send()
                        end
                        if qReady and GetDistance(Minion) <= 550 then
                                Packet('S_CAST', { spellId = _Q, x=Minion.x, y=Minion.z }):send()
                        end     
                        if wReady and GetDistance(Minion) <= 260 then
                                Packet('S_CAST', { spellId = _W, }):send()                              
                        end     
                        if items_0 then
                                CastCommonItem()
                        end     
                end
        end
end

function JumpQ(myTarget)
        local qReady    =       myPlayer:CanUseSpell(_Q) == READY
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local orb_              =       menu.system.orbwalker
        if eReady then
                Packet('S_CAST', { spellId = _E, x=mousePos.x, y=mousePos.z }):send()
        end
        if qReady then
                Packet('S_CAST', { spellId = _Q, x=mousePos.x, y=mousePos.z }):send()
        end
        if orb_ then _OrbWalk(myTarget) end
end

function Harass_Key(myTarget)
        local useq_             =       menu.combo.skills.q
        local qReady    =       myPlayer:CanUseSpell(_Q) == READY
        local usee_             =       menu.combo.skills.e
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local usew_             =       menu.combo.skills.w
        local wReady    =       myPlayer:CanUseSpell(_W) == READY
        local valid     =       ValidTarget(myTarget)
        local orb_              =       menu.system.orbwalker
        if valid then
                if useq_ and qReady and GetDistance(myTarget) <= 550  then
                        Packet('S_CAST', { spellId = _Q, x=myTarget.x, y=myTarget.z }):send()
                end
                if usee_ and eReady and GetDistance(myTarget) <= 380 then
                        Packet('S_CAST', { spellId = _E, x=mousePos.x, y=mousePos.z }):send()
                end
                if usew_ and wReady and GetDistance(myTarget) <= 260 then
                        Packet('S_CAST', { spellId = _W }):send()
                end             
        end
        if orb_ then _OrbWalk(Target) end
end

--[[
_G.AdvancedCallback:register('AfterWindup')
 
function AfterWindup(unit, spell)
        local orbsmooth_        =       menu.system.orbsmooth
        local qgap_                     =       menu.combo.gap.qgap --default 550
        local qgapStop_         =       menu.combo.gap.qqg
        local qReady            =       myPlayer:CanUseSpell(_Q) == READY
        local eReady            =       myPlayer:CanUseSpell(_E) == READY
        local eg                        =       menu.combo.gap.eg
        local egStop_           =       menu.combo.gap.eeg

    if unit.type =="obj_AI_Hero" and ValidTarget(Target) then
        --if eReady and GetDistance(Target) >= egStop_ and GetDistance(Target) <= eg then
        --      CastSpell(_E, Target.x, Target.z)
        --end           
        if qReady and GetDistance(Target) >= qgapStop_ and GetDistance(Target) <= qgap_ then
            CastQ(Target)
        end 
        myPlayer:Attack(Target)  
    end
end
]]
 
function OnAttack(unit, attack)
        local advanced_         =       menu.combo.cancel.advanced
        local qReady            =       myPlayer:CanUseSpell(_Q) == READY               

    if unit.isMe and advanced_ and ValidTarget(Target) then          
        if qReady then          
            CastQ(Target)
        end       
    end
end

function OnProcessSpell(unit, spell)    
        --[[e skill]]
        local usee_             =       menu.combo.skills.e
        local eq_               =       menu.combo.mskill.eq
        local eReady    =       myPlayer:CanUseSpell(_E) == READY
        local eg                =       menu.combo.gap.eg
        local egStop_   =       menu.combo.gap.eeg
        --[[r skill]]
        local user_             =       menu.combo.skills.r
        local rReady    =       myPlayer:CanUseSpell(_R) == READY
        local er_               =       menu.combo.mskill.er
        local rmin_             =       menu.combo.gap.rmin
        local eHealth   =       menu.combo.extra.ulthealth
        --[[w skill]]
        local usew_             =       menu.combo.skills.w
        local wReady    =       myPlayer:CanUseSpell(_W) == READY
        local ew_               =       menu.combo.mskill.ew
        local wRange_   =       menu.combo.gap.wg
        --[[q skill]]
        local useq_             =       menu.combo.skills.q
        local qReady    =       myPlayer:CanUseSpell(_Q) == READY       
        local qgap_             =       menu.combo.gap.qgap
        local qgapStop_ =       menu.combo.gap.qqg
        local qCancel   =       menu.combo.cancel.q
        --[[items]]
        local items_0           =       menu.combo.extra.items
        local advanced_         =       menu.combo.cancel.advanced
        local adv_smooth_       =       menu.combo.cancel.advsmooth  

        --[[target]]
        local valid     =       ValidTarget(Target)

        if unit.IsMe then
                --Target = updateTargetPlease()

                if spell.name:lower():find("attack") then                       
                        lastAttack = GetTickCount() - GetLatency() / 2
                        lastWindUpTime = spell.windUpTime * 1000
                        lastAttackCD = spell.animationTime * 1000
                        if qReady and valid and advanced_ then
                                DelayAction( CastQ(Target) , adv_smooth_)
                        end     
                end 
        

                if spell.name == "RivenTriCleave" then  --q     
                        if valid then 

                                if eReady and usee_     and eq_ and GetDistance(Target) >= egStop_ and GetDistance(Target) <= eg then
                                CastSpell(_E, Target.x, Target.z)
                                --Packet('S_CAST', { spellId = _E, x=Target.x, y=Target.z }):send()
                        end

                        if qCancel then        
                                local movePos = Target + (Vector(myPlayer) - Target):normalized()
                        if movePos then
                                Packet('S_MOVE', {x = movePos.x, y = movePos.z}):send()
                                end
                        end

                        
                end
        end     

                if spell.name == "RivenFeint" and valid then --e

                        if rReady and user_ and er_ then
                                local enemyHealth = (Target.health / Target.maxHealth * 100)

                                if temUltimate and enemyHealth <= eHealth then
                                        --Packet('S_CAST', { spellId = _R, x=Target.x, y=Target.z }):send()
                                        CastSpell(_R, Target.x, Target.z)                               
                                end     

                                if GetDistance(Target) <= rmin_ then
                                        Packet('S_CAST', { spellId = _R }):send()                                       
                                end                             
                        end

                        if wReady and usew_ and ew_ and GetDistance(Target) <= wRange_ then
                        --Packet('S_CAST', { spellId = _W }):send()
                        CastSpell(_W)
                end     

                        if qReady and eq_ and GetDistance(Target) >= qgapStop_ and GetDistance(Target) <= qgap_ then
                                CastSpell(_Q, Target.x, Target.z)
                        end                     

                end

                if spell.name == "RivenMartyr" and valid then --w
                        if items_0 then
                                CastCommonItem()
                        end
                end

                if spell.name == "RivenFengShuiEngine" then --r
                        rTick = os.clock()
                end

        end
end

function OnTick()
        if myPlayer.dead then return end
        local orb_                      =       menu.system.orbwalker
        local items_            =       menu.extra.items
        --local jungle_ =       menu.extra.jungleKey
        local jump_                     =       menu.extra.jump
        local lineKey_          =       menu.extra.lineKey
        local comboKey          =       menu.combo.key
        local Harass_Key_       =       menu.extra.harass
        Target = updateTargetPlease()
        if comboKey then                        
                KillEverybodyMothaFucker(Target)
                CastCommonItem()                
                if orb_ then _OrbWalk(Target) end               
        end
        if items_ then CastSurviveItem() end
        CastIgnite(Target)
        if lineKey_ then LineFarm() end
        --if jungle_ then JungleFarm() end
        if jump_ then JumpQ(Target) end
        if Harass_Key_ then Harass_Key(Target) end
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]--
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
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
--

function OnDraw()
        if myPlayer.dead then return end
        local qRange    =       275
        local wRange    =       260
        local eRange    =       385     
        local rRange    =       900
        --[[menu draw]]
        local qDraw                     =       menu.draw.Q
        local wDraw                     =       menu.draw.W
        local eDraw                     =       menu.draw.E
        local rDraw                     =       menu.draw.R
        local targetDraw        =       menu.draw.target
        --[[line draw]]
        local lineKey_          =       menu.extra.lineKey
        local lineRange_        =       menu.extra.linerange

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
        if lineKey_ then
                DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, lineRange_, ARGB(255, 255, 255, 255))
        end
        if targetDraw and Target ~= nil and ValidTarget(Target) then
                for i=0, 3, 1 do
                        DrawCircle2(Target.x, Target.y, Target.z, 80 + i , ARGB(255, 255, 000, 255))    
                end
        end
end