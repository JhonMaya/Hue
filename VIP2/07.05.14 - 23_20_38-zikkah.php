<?php exit() ?>--by zikkah 83.84.221.160

----- Initialize lib

_G.ImBeastyLibVersion = 2

class 'ImBeasty'  -- {}

    function ImBeasty:__init(orbWalk, farm, damageCalc, autoPotion, autoSmite)
        -- Always load:
        LibMenu.Instance(myHero.charName, orbWalk, farm, damageCalc, autoPotion, autoSmite)
        _G.EnemyTable = {}
        _G.EnemysInTable = 0

        DamageManager.Instance(1)
        HighPrioritySpells()
        --    Arrange prioritys:

        -- Optional:
        if autoPotion then AutoPotion() end
        if autoSmite then AutoSmite() end
        if orbWalk then OrbWalking.Instance(myHero.charName) end
    end
-- } 

----- Global Callback Initialize
_G.AdvancedCallback:register('OnInterruptWindUp', 'OnSpellCast', 'OnAttack')

tempAA = {}
tempSpell = {}

function OnRecvPacket(p)
    if p.header == 0x1A or p.header == 0x0C then 
        p.pos = 10
        projectileID = p:DecodeF()
        tempAA[projectileID] = true
    elseif p.header == 0xB4 then 
        p.pos = 1
        local networkdID = p:DecodeF()
        local unit = objManager:GetObjectByNetworkId(networkdID)
        p.pos = 12
        local memoryID = tostring(p:Decode4())
        local objectID = p:DecodeF()
        local level = p:Decode1()
        p.pos = 37
        local projectileID = p:DecodeF()
        startPos = Vector(p:DecodeF(), p:DecodeF(), p:DecodeF())
        endPos = Vector(p:DecodeF(), p:DecodeF(), p:DecodeF())
        p.pos = 65 
        local nr = p:Decode1()
        if nr == 1 then 
            local targetnetworkdID = p:DecodeF()
            local target = objManager:GetObjectByNetworkId(targetnetworkdID)
            if target then 
                endPos = Vector(target.x, target.y, target.z)
            end
                local targetx = p:DecodeF()
                local targety = p:DecodeF()
                local targetz = p:DecodeF()
            nr = p:Decode1()
        end     
        local windUpTime = p:DecodeF()
        local unknow = p:DecodeF()
        local animationTime = p:DecodeF()
        local cooldown = p:DecodeF()
        local unknow2 = p:DecodeF()
        local autoattack = p:Decode1()
        local iSpell = p:Decode1()
        local mana = p:DecodeF()
        
        if autoattack == 1 or autoattack == 3 then 
            tempAA[projectileID] = true
            return 
        end

        if SummonerSpells[memoryID] then iSpell = iSpell + 12 end 
        tempSpell[projectileID] = {level = level+1, iSpell = iSpell, cooldown = cooldown, mana = mana}
    elseif p.header == 0x33 then
        p.pos = 1
        local unit = objManager:GetObjectByNetworkId(p:DecodeF())
        p.pos = 9

        if unit.isMe then 
             local n = p:Decode1()            
            if n == 17 then
                AdvancedCallback:OnInterruptWindUp(unit) 
            end
        end
    end
end

function OnProcessSpell(obj, spell)
    if tempAA[spell.projectileID] then 
        AdvancedCallback:OnAttack(obj, {target = spell.target, animationTime = spell.animationTime, windUpTime = spell.windUpTime, name = spell.name, projectileID = spell.projectileID})
        tempAA[spell.projectileID] = nil
    elseif tempSpell[spell.projectileID] then 
        AdvancedCallback:OnSpellCast(obj, {level = tempSpell[spell.projectileID].level, projectileID = spell.projectileID, animationTime = spell.animationTime, windUpTime = spell.windUpTime, iSpell = tempSpell[spell.projectileID].iSpell, name = spell.name, cooldown = tempSpell[spell.projectileID].cooldown, target = spell.target, mana = tempSpell[spell.projectileID].mana, startPos = spell.startPos, endPos = spell.endPos})
        tempSpell[spell.projectileID] = nil
    end 
end 

----- Menu class

class 'LibMenu'  -- {
    LibMenu.instance = ""

    function LibMenu.Instance(orbWalk, farm, damageCalc, autoPotion, autoSmite) 
        if LibMenu.instance == "" then LibMenu.instance = LibMenu(orbWalk, farm, damageCalc, autoPotion, autoSmite) end 
        return LibMenu.instance 
    end 

    function LibMenu:__init(orbWalk, farm, damageCalc, autoPotion, autoSmite)
                                            self.Config = scriptConfig("ImBeasty:" .. myHero.charName, "imbeasty"..myHero.charName)
                                            self.Config:addSubMenu("ImBeasty: " .. myHero.charName, myHero.charName)
        if orbWalk then                     self.Config:addSubMenu("ImBeasty: OrbWalking", "orbwalk") end
        if autoSmite or autoPotion then     self.Config:addSubMenu("ImBeasty: Uility", "utility") end
        if damageCalc then                  self.Config:addSubMenu("ImBeasty: Draw", "draw") end
    end 

    function LibMenu.GetSub(sub)
        return LibMenu.Instance():_GetSub(sub)
    end 

    function LibMenu:_GetSub(sub)
        return sub and self.Config[sub] or self.Config 
    end 
-- }


----- Spell class

SummonerSpells = {  ["104222500"] = "SummonerDot", ["145275620"] = "SummonerExhaust", ["105475752"] = "SummonerFlash", ["105565333"] = "SummonerHaste", ["56980513"] = "SummonerMana", ["97039269"] = "SummonerRevive", ["105717908"] = "SummonerBoost", ["56930076"] = "SummonerHeal", ["159999845"] = "SummonerClairvoyance", ["214940034"] = "SummonerBarrier", ["5182308"] = "SummonerTeleport",["106858133"] = "SummonerSmite", ["226996206"] = "SummonerOdinGarrison"}

class 'SpellManager' -- {
    function SpellManager:__init(iSpell)
        self.iSpell = iSpell
        if self.iSpell == "IGNITE" then
            self.iSlot =  ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and 64) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and 65) or nil)
            if self.iSlot then self.iSpell = self.iSlot end
        end

        self.receive = true
        self.receivetime = math.huge

        self.currentStage = 1
        --self.casttime = 0
        self.stage = {
            [1] = {cooldown = 0},
            [2] = {cooldown = 0}
        }
        self.starttime = 0
       
        self.packet = nil 

        AddTickCallback(function(obj) self:OnTick() end)
        AdvancedCallback:bind('OnSpellCast', function(unit, spell) self:OnSpellCast(unit,spell) end)
        AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
    end

    function SpellManager:OnTick()

    end 

    function SpellManager:OnRecvPacket(p) 
        if p.header == 0x85 then 
            p.pos = 1
            if myHero.networkID == p:DecodeF() and self.iSpell == p:Decode1() then 
                self.currentStage = self.currentStage == 1 and 2 or 1
                self.stage[self.currentStage].cooldown = GetGameTimer() + p:DecodeF()
            end 
        end 
    end 

    function SpellManager:OnSpellCast(unit, spell)
        if unit.isMe and spell.iSpell == self.iSpell then 
            self.stage[self.currentStage].cooldown = GetGameTimer() + spell.cooldown

            self.receivetime = math.huge
            self.receive = true
        end
    end 


    function SpellManager:Ready(stage) -- 0 == false, 1 == true, 2 == wait for packet, 3 == ready but not right stage
        stage = stage or 1
        if stage > 2 and stage < 1 then stage = 1 end   
        if myHero:GetSpellData(self.iSpell).level < 1 then return 0 end

        if not self.receive then return 2 end 

        if self.stage[stage].cooldown <= GetGameTimer() then 
            if stage == self.currentStage then 
                return 1
            else    
                return 3
            end 
        end 

        return 0
    end 

    function SpellManager:_IsReady() 
        if self.iSpell == nil then return false end 
        if not self.receive then return false end
        if self.stage[self.currentStage].cooldown > GetGameTimer() then return false end
        return true
    end 

    function SpellManager:Cast(p1, p2)
        
        local target = 0
        local x = 0
        local z = 0
        if x == nil and z == nil then
            x = myHero.x
            z = myHero.z
            target = myHero.networkID
        elseif x and z == nil then
            x = 0
            z = 0
            target = p1.networkID
        end

        spellCastPacket = CLoLPacket(0x99)
        spellCastPacket.dwArg1 = 1
        spellCastPacket.dwArg2 = 0
        spellCastPacket:EncodeF(myHero.networkID)
        spellCastPacket:Encode1(self.iSpell)
        spellCastPacket:EncodeF(myHero.x)
        spellCastPacket:EncodeF(myHero.z)
        spellCastPacket:EncodeF(x)
        spellCastPacket:EncodeF(z)
        spellCastPacket:EncodeF(target)
        SendPacket(spellCastPacket)


        self.receive = false
        self.receivetime = GetGameTimer() + GetLatency()/1000 + 0.075
        return true 
    end 

    function SpellManager:Damage(target, n) 
        local spell = self.iSpell
        if self.iSpell == _Q then spell = "q" 
        elseif self.iSpell == _W then spell = "w" 
        elseif self.iSpell == _E then spell = "e" 
        elseif self.iSpell == _R then spell = "r" 
        end
        if n then
            spell = spell .. n
        end
        return DamageManager.Damage(spell, target)
    end     
-- } 

    
_G.Spell = {
    [_Q] = SpellManager(_Q),
    [_W] = SpellManager(_W), 
    [_E] = SpellManager(_E),
    [_R] = SpellManager(_R),
    ["P"] = SpellManager("p"),
    ["IGNITE"] = SpellManager("IGNITE"),
    [ITEM_1] = SpellManager(ITEM_1),
    [ITEM_2] = SpellManager(ITEM_2),
    [ITEM_3] = SpellManager(ITEM_3),
    [ITEM_4] = SpellManager(ITEM_4),
    [ITEM_5] = SpellManager(ITEM_5),
    [ITEM_6] = SpellManager(ITEM_6),
    [ITEM_7] = SpellManager(ITEM_7),
    [SUMMONER_1] = SpellManager(SUMMONER_1),
    [SUMMONER_2] = SpellManager(SUMMONER_2),
    [RECALL] = SpellManager(RECALL)
}


----- Item class


class 'ItemHandler' -- {


    function ItemHandler:__init(itemId, name)
        self.name = name
        self.id = itemId
        if range == nil then self.range = math.huge end 
    end


    function ItemHandler:Ready()
        local slot = self:Slot()
        return slot ~= nil and myHero:CanUseSpell(slot) == READY
    end


    function ItemHandler:Slot()
        return GetInventorySlotItem(self.id)
    end

    function ItemHandler:ID()
        return self.id
    end

    function ItemHandler:InInventory()

        return GetInventorySlotItem(self.id) ~= nil
    end 


    function ItemHandler:Cast(target, pot)
        if pot then
            local slot = self:Slot()
            if target and GetDistance(target) <= self.range then  
                if self:Ready() then CastSpell(slot, target) return true else return false end
            else 
                if self:Ready() then CastSpell(slot) return true else return false end
            end
        end
        local slot = self:Slot()
        if self:Ready() then
            if slot == 4 then
                Spell[ITEM_1]:Cast(target)
                return true
            elseif slot == 5 then
                Spell[ITEM_2]:Cast(target)
                return true
            elseif slot == 6 then
                Spell[ITEM_3]:Cast(target)
                return true
            elseif slot == 7 then
                Spell[ITEM_4]:Cast(target)
                return true
            elseif slot == 8 then
                Spell[ITEM_5]:Cast(target)
                return true
            elseif slot == 9 then
                Spell[ITEM_6]:Cast(target)
                return true
            end
        end
        return false
    end

    function ItemHandler:Damage(target)
        return DamageManager.Damage(self.name, target)

    end
-- }





class 'OrbWalking' -- {

    OrbWalking.instance = ""

    function OrbWalking:__init()
        _G.AdvancedCallback:register('OnWindUp')

        self.attacking = false
        self.attackinfo = nil
        self.bufferAttack = false 
        self.nextAttack = 0
        self.windUp = 0
        self.windUpTime = 0
        self.target = nil 
        self.lastPacket = nil
        self.time = 0
        self.interrupted = false
        self.spell = {}
        
        LibMenu.GetSub("orbwalk"):addParam("oWalk", "OrbWalk", SCRIPT_PARAM_ONOFF, true)
        LibMenu.GetSub("orbwalk"):addParam("aAttacks", "Auto Attack", SCRIPT_PARAM_ONOFF, true)
        LibMenu.GetSub("orbwalk"):addParam("ftMouse", "Follow Mouse", SCRIPT_PARAM_ONOFF, true)

        AddProcessSpellCallback(function(obj, spell) self:OnProcessSpell(obj, spell) end)
        AddSendPacketCallback(function(obj) self:OnSendPacket(obj) end)
        AddTickCallback(function() self:OnTick() end)
        AdvancedCallback:bind('OnInterruptWindUp', function(unit) self:OnInterruptWindUp(unit) end)
        AdvancedCallback:bind('OnAttack', function(unit, attack) self:OnAttack(unit, attack) end)
    end

    function OrbWalking.Instance(name)
        if OrbWalking.instance == "" then OrbWalking.instance = OrbWalking() end return OrbWalking.instance 
    end

    function OrbWalking:OnTick()
        if self.attackinfo and self.attackinfo.time <= os.clock() then
            self.attackinfo.func(table.unpack(self.attackinfo.args or {}))
            self.attackinfo = nil

        end 
    end

    function OrbWalking:DelayAttack(func, delay, args)
        local t = os.clock() + (delay or 0)
        self.attackinfo = {time = t, func = func, args = args }
    end 

    function OrbWalking:OnAttack(unit, attack)
        if unit.isMe then
            self.interrupted = false
            self.lastPacket = nil
            self.attacking = true 
            self.time = GetGameTimer()
            self:DelayAttack(function()
                self.attacking = false
                if self.lastPacket then 
                    SendPacket(self.lastPacket)
                end 
                self.lastPacket = nil
                if not self.interrupted then
                    AdvancedCallback:OnWindUp(attack.target, attack) 
                end
                end,  attack.windUpTime - GetLatency() / 2000, {})
            self.target = attack.target
            self.windUpTime = attack.windUpTime
            self.nextAttack =  GetGameTimer() + (attack.animationTime - GetLatency() / 2000) - 0.2
        end
    end


    function OrbWalking:OnInterruptWindUp(unit)
        if unit.isMe then
            self.attacking = false
            self.lastPacket = nil
            self.nextAttack = 0
            self.interrupted = true
            self.attackinfo = nil
        end 
    end

    function OrbWalking:OnSendPacket(p)

        if not LibMenu.GetSub("orbwalk").oWalk then return end

        if p.header == 0x99 then
            p.pos = 1
            networkId = p:DecodeF() 
            spellID = p:Decode1()
            for i, spell in pairs(self.spell) do
                if networkId == myHero.networkID and i == spellID then
                    self.spell[i] = nil
                    return 
                end 
            end 
        end 

--        if (self.attacking or self.bufferAttack) and (p.header == 0x71 or p.header == 0x9A) then
        if (self.attacking or self.bufferAttack) and p.header == 0x71 then
          --  self.lastPacket =  copyPacket(p)
            p.pos = 1
            p:Block()
            return
        end    
    end

    function OrbWalking:OnProcessSpell(object,spell)
        if object== nil or spell == nil then return end 

        if object.isMe and ResetAttack[spell.name] then
            self.attacking = false
            self.lastPacket = nil
            self.nextAttack = 0
            self.interrupted = true
            self.attackinfo = nil 
        end 
    end

    function OrbWalking.Target()
        return OrbWalking.Instance().target
    end 

    function OrbWalking.CastSpell(...)
        return OrbWalking.Instance():_CastSpell(...)
    end 

    function OrbWalking:_CastSpell(...)
        tables = {...}
        self.spell[tables[1]] = tables
        CastSpell(...)
    end 

    function OrbWalking.CanAttack()
        return OrbWalking.Instance():_CanAttack()
    end

    function OrbWalking:_CanAttack()
        return self.nextAttack <= GetGameTimer() and not self.bufferAttack and not self.attacking
    end

    function OrbWalking.NextAttack()
        return OrbWalking.Instance():_NextAttack()
    end

    function OrbWalking:_NextAttack()
        return self.nextAttack
    end

    function OrbWalking.WindUpTime()
        return OrbWalking.Instance().windUp
    end

    function OrbWalking._WindUpTime()
        return OrbWalking.Instance().windUpTime
    end

    function OrbWalking.ResetAA()
        OrbWalking.Instance().nextAttack = 0
    end

    function OrbWalking.ManualReset()
        OrbWalking.Instance():_ManualReset()
    end

    function OrbWalking:_ManualReset()
                self.attacking = false
                self.lastPacket = nil
                self.nextAttack = 0
    end    

    function OrbWalking.InAaRange(target) 
        if ValidTarget(target) and GetDistance(target) >= GetDistance(target, target.minBBox) + myHero.range+40 then
            return false
        else
            return true
        end
    end

    function OrbWalking.Activate(target)
        return OrbWalking.Instance():_Activate(target)
    end

    function OrbWalking:_Activate(target)
        if not LibMenu.GetSub("orbwalk").oWalk then return end
        if ValidTarget(target) and self:_CanAttack() and self.InAaRange(target) and LibMenu.GetSub("orbwalk").aAttacks then
            myHero:Attack(target)
            self.bufferAttack = true
            DelayAction1(function()
                self.bufferAttack = false
            end, GetLatency() / 1000, {})
        else 
            if LibMenu.GetSub("orbwalk").ftMouse and GetDistance(mousePos) >  GetDistance(myHero.minBBox) then
                myHero:MoveTo(mousePos.x, mousePos.z)
            end
        end
        return
    end

    function OrbWalking.Attack(target)
        return OrbWalking.Instance():_Attack(target)
    end

    function OrbWalking:_Attack(target)
        if self:_CanAttack() and ValidTarget(Target) then
            myHero:Attack(target)
            self.attacking = true
            return true 
        end
        return false
    end 
-- }

local delayedActions, delayedActionsExecuter = {}, nil 
function DelayAction1(func, delay, args) --delay in seconds
    if not delayedActionsExecuter then
        function delayedActionsExecuter()
            for t, funcs in ipairs(delayedActions) do
                if funcs.time <= os.clock() then
                    funcs.func(table.unpack(funcs.args or {}))
                    --delayedActions[t] = nil
                    table.remove(delayedActions, t)
                end
            end
        end

        AddTickCallback(delayedActionsExecuter)
    end
    local t = os.clock() + (delay or 0)
    table.insert(delayedActions, {time = t, func = func, args = args })
end

function _G.copyPacket(packet)
      packet.pos = 1
      p = CLoLPacket(packet.header)
      for i=1,packet.size-1,1 do
        p:Encode1(packet:Decode1())
      end
      p.dwArg1 = packet.dwArg1
      p.dwArg2 = packet.dwArg2
      return p
end


class 'IObjects' -- {

    IObjects.instance = ""

    function IObjects:__init()
        self.tables = {}
        AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
    end

    function IObjects.GetInstance()
        if IObjects.instance == "" then IObjects.instance = IObjects() end return IObjects.instance 
    end

    function IObjects.GetObjects(...)
        return IObjects.GetInstance():PrivateGetObjects(...) 
    end

    function IObjects:OnCreateObj(obj)
        self:AddObject(obj)
    end

    function IObjects:AddObject(obj)
        if not obj or not obj.valid then return end
        for i, tableType in pairs(self.tables) do
            if obj.type == i then
                DelayAction(function(obj, tableType)
                    for j, team in pairs(tableType) do    
                        if obj.team == j then self.tables[i][j][obj.networkID] = obj return end
                    end
                end, 0, {obj, tableType})
            end
        end
    end

    function IObjects:GetObjectsByFilter(filter)
        local values = {}
        values.type = filter.type or {'obj_AI_Minion', 'obj_AI_Hero','obj_AI_Turrets'} 
        values.team = filter.team or {myHero.team, TEAM_ENEMY, TEAM_NEUTRAL}
        values.name = filter.name or {}
        values.range = filter.range or math.huge
        values.source = filter.source or myHero 
        values.networkID = filter.networkID or nil 
        values.level = filter.level or nil 
        values.table = {}
        values.visible = filter.visible ~= false
        values.Invulnerable = filter.Invulnerable ~= true
        values.bTargetable = filter.bTargetable ~= false
        values.bTargetableToTeam = filter.bTargetableToTeam == true 
        values.dead = false

        local AddTable = {}

        for i, mode in pairs(values.type) do    
            if type(self.tables[mode]) ~= "table" then self.tables[mode] = {} end
            for j, team in pairs(values.team) do
                if type(self.tables[mode][team]) ~= "table" then
                    self.tables[mode][team] = {}
                    if type(AddTable[mode]) ~= "table" then AddTable[mode] = {} end
                    AddTable[mode][team] = {}
                end
            end
        end  
  
        if HasValues(AddTable) then 
            for i = 1, objManager.maxObjects, 1 do
                local obj = objManager:GetObject(i)
                for k, tableType in pairs(AddTable) do 
                    if obj and obj.type == k then 
                        for j, team in pairs(tableType) do  
                            if obj.team == j then self.tables[k][j][obj.networkID] = obj end
                        end
                    end
                end
            end
        end

        for i, tableType in pairs(values.type) do 
            for j, team in pairs(values.team) do
                for k,v in pairs(self.tables[tableType][team]) do  
                    if v and v.valid then 
                        if (values.dead or not v.dead) 
                            and (not values.visible or v.visible)    
                            and GetDistance(v, values.source) <= values.range 
                            and (not values.bTargetable or v.bTargetable)  
                            and (not values.bTargetableToTeam or v.bTargetableToTeam)  
                            and (values.Invulnerable or (v.team == myHero.team or v.bInvulnerable == 0))then 

                            if (#values.name == 0) then
                                values.table[v.networkID] = v  
                            else 
                                for w, name in pairs(values.name) do 
                                    if v.name == name then values.table[v.networkID] = v break end
                                end 
                            end 
                        end
                    end
                end
            end 
        end 

        return values.table
    end

    local mt = {
      __add = function (left, right) 
            local returntable = {}
            for i, obj in ipairs(left) do
                table.insert(returntable, obj)
            end 
            for i, obj in ipairs(right) do
                table.insert(returntable, obj)
            end 

            return  setmetatable(returntable, mt)--Objects({left.filters, right.filters})
      end
    }

    function IObjects:PrivateGetObjects(...) 
        local temptable = {}
        local filters = ...
        for i, filter in ipairs(filters) do
            temptable = MergeTable(temptable, self:GetObjectsByFilter(filter))
        end
        local returntable = {}
        for i, obj in pairs(temptable) do
            table.insert(returntable, obj)
        end 
        returntable.filters = ...
        setmetatable(returntable, mt)
        return returntable
    end 
-- }

function _G.Objects(...)
    return IObjects.GetObjects(...)
end 


function _G.Minions(...)
    filters = {...}
    if #filters == 0 then filters[1] = {} filters[1].type = {'obj_AI_Minion'} end

    for i, filter in ipairs(filters) do
        filter.type = {'obj_AI_Minion'}
    end
    return Objects(filters)
end

function _G.Heroes(...)
    filters = {...}
    if #filters == 0 then filters[1] = {} filters[1].type = {'obj_AI_Hero'} end

    for i, filter in ipairs(filters) do
        filter.type = {'obj_AI_Hero'}
    end
    return Objects(filters)
end

function _G.Turrets(...)
    filters = {...}
    if #filters == 0 then filters[1] = {} filters[1].type = {'obj_AI_Turret'} end

    for i, filter in ipairs(filters) do
        filter.type = {'obj_AI_Turret'}
    end
    return Objects(filters)
end

function _G.HasValues(T)
    for _ in pairs(T) do return true end
    return false
end
function _G.TableCount(T)
    local count = 0
    for _ in pairs(T) do count = count + 1 end
    return count
end

function MergeTable(firstTable, SecondTable)
    for k,v in pairs(SecondTable) do firstTable[k] = v end
    return firstTable
end
----- Buff class

class 'Buffs' -- {

    function Buffs:__init()
        self.activebuffs = {}
        AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
    end

    function Buffs.HasBuff(unit, buff)
        for i, currentbuff in pairs(EnemyTable[unit].buffs) do
            if currentbuff ~= nil and currentbuff == buff then
                return true
            end
        end
        return false
    end

    function Buffs:OnGainBuff(unit, buff)
        if EnemyTable[unit] then 
            table.insert(EnemyTable[unit].buffs, buff.name)
        end
    end

    function Buffs:OnLoseBuff(unit, buff)
        if EnemyTable[unit] then 
            for i, currentbuff in pairs(EnemyTable[unit].buffs) do
                if currentbuff ~= nil and currentbuff == buff.name then
                    table.remove(EnemyTable[unit].buffs, i)
                end
            end
        end
    end
-- }


----- Ward class

class 'Ward' -- {

    function Ward.Avaible()
        if Ward.GetWardSlot() ~= nil then return true end
        return false
    end

    function Ward.GetWardSlot()

        local WardIDs = { Item["RubySightstone"], Item["Sightstone"], Item["WrigglesLantern"], Item["ExplorersWard"], Item["SightWard"], Item["VisionWard"] }
        for _, ward in ipairs(WardIDs) do
            if ward:Ready() then return ward:Slot() end
        end
        PrintChat("No ward found")
        return nil
    end

    function Ward.Place(x, y, force)
        local slot = Ward.GetWardSlot()
        if slot == nil then return false end
        
        CastSpell(slot, x, y)
        --self.casted = true
    end
-- }

-- Auto Potion / Elixer
class 'AutoPotion' -- }

    function AutoPotion:__init()
        LibMenu.GetSub("utility"):addParam("aPotion", "Auto Potion", SCRIPT_PARAM_ONOFF, true)
        LibMenu.GetSub("utility"):addParam("aPotionElixer", "Auto Elixer: % Health", SCRIPT_PARAM_SLICE, 75, 0, 100, 0)
        
        potionActive = { healthPot = false,  flask = false, biscuit = false, manaPot = false,  }
        self.healthPotion = nil
        self.manaPotion = nil

        AddTickCallback(function() self:OnTick() end)
        AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
    end

    function AutoPotion:OnTick()
        if LibMenu.GetSub("utility").aPotion and not InFountain() then 
            if self:GetHealthPotion() and not self:IsPotionActive(false) then
                Item[self.healthPotion]:Cast(nil, true)
            end
            if myHero.parType == 0 and self:GetManaPotion() and not self:IsPotionActive(true) then
                Item[self.manaPotion]:Cast(nil, true)
            end
        end
    end

    function AutoPotion:GetHealthPotion()
        if myHero.health/myHero.maxHealth*100 <= LibMenu.GetSub("utility").aPotionElixer and Item["Elixer"]:Ready() then 
            Item["Elixer"]:Cast()
        end
        if myHero.maxHealth - myHero.health >= 150 and Item["HealthPotion"]:Ready() then
            self.healthPotion = "HealthPotion"
            return true
        elseif myHero.maxHealth - myHero.health >= 80 and Item["Biscuit"]:Ready() then
            self.healthPotion = "Biscuit"
            return true
        end
    end

    function AutoPotion:GetManaPotion()
        if myHero.maxMana - myHero.mana >= 180 then
            if Item["ManaPotion"]:Ready() then self.manaPotion = "ManaPotion" return true end
            if Item["Flask"]:Ready() then self.manaPotion = "Flask" return true end
        end
    end 

    
    function AutoPotion:IsPotionActive(checkManaPot)
        if checkManaPot then
            if TargetHaveBuff("FlaskOfCrystalWater")  then return true end
        elseif TargetHaveBuff("RegenerationPotion", myHero) or TargetHaveBuff("ItemCrystalFlask", myHero) or TargetHaveBuff("ItemMiniRegenPotion", myHero) then return true end
        return false
    end

    function AutoPotion:OnGainBuff(unit, buff)
        if unit.isMe then
            if buff.name:find("RegenerationPotion") then potionActive.healthPot = true return end
            if buff.name:find("ItemCrystalFlask") then potionActive.flask = true return end
            if buff.name:find("ItemMiniRegenPotion") then potionActive.biscuit = true return end
            if buff.name:find("FlaskOfCrystalWater") then potionActive.manaPot = true return end
        end
    end
    function AutoPotion:OnLoseBuff(unit,buff)
        if unit.isMe then
            if buff.name:find("RegenerationPotion") then potionActive.healthPot = false return end
            if buff.name:find("ItemCrystalFlask") then potionActive.flask = false return end
            if buff.name:find("ItemMiniRegenPotion") then potionActive.biscuit = false return end
            if buff.name:find("FlaskOfCrystalWater") then potionActive.manaPot = false return end
        end
    end
-- }

----- AutoSmite 

class 'AutoSmite' -- {

    function AutoSmite:__init()
        self.smiteSlot = (myHero:GetSpellData(SUMMONER_1).name:find("Smite") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("Smite") and SUMMONER_2) or nil
        if self.smiteSlot then  
            LibMenu.GetSub("utility"):addParam("aSmiteAlways", "AutoSmite: Always", SCRIPT_PARAM_ONOFF, true)
            LibMenu.GetSub("utility"):addParam("aSmiteKey", "AutoSmite: On hotkey", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("N"))
            self.Vilemaw = nil
            self.Nashor = nil
            self.Dragon = nil
            self.Golem1 = nil
            self.Golem2 = nil
            self.Lizard1 = nil
            self.Lizard2 = nil
            self.smiteDamage = 0
            self.damageTable = { 390, 410, 430, 450, 480, 510, 540, 570, 600, 640, 680, 720, 760, 800, 850, 900, 950, 1000 }
            for i = 1, objManager.maxObjects do
            local obj = objManager:getObject(i)
                if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
                    if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = obj end
                    if obj.name == "Worm12.1.1" then self.Nashor = obj end-- Nashor
                    if obj.name == "Dragon6.1.1" then self.Dragon = obj end-- Dragon
                    if obj.name == "AncientGolem1.1.1" then self.Golem1 = obj end-- Blue side blue
                    if obj.name == "AncientGolem7.1.1" then self.Golem2 = obj end-- Purple side blue
                    if obj.name == "LizardElder4.1.1" then self.Lizard1 = obj end-- Blue side red
                    if obj.name == "LizardElder10.1.1" then self.Lizard2 = obj  end-- Purple side red
                    
                end
            end
        AddTickCallback(function() self:OnTick() end)
        AddCreateObjCallback(function(obj) self:OnCreateObj(obj) end)
        AddDeleteObjCallback(function(obj) self:OnDeleteObj(obj) end)
        end
    end

    function AutoSmite:OnTick()
        self.smiteDamage = self.damageTable[myHero.level]
        self.smiteReady = myHero:CanUseSpell(self.smiteSlot) == READY
        self:ScanJungle()
        if LibMenu.GetSub("utility").aSmiteAlways or LibMenu.GetSub("utility").aSmiteKey then
            if self.smiteReady then
                if self.Vilemaw ~= nil and GetDistance(self.VileMaw) <= 820 then self:Smite(self.Vilemaw) end
                if self.Nashor ~= nil and GetDistance(self.Nashor) <= 820  then self:Smite(self.Nashor) end
                if self.Dragon ~= nil and GetDistance(self.Dragon) <= 820 then self:Smite(self.Dragon) end
                if self.Golem1 ~= nil and GetDistance(self.Golem1) <= 820 then self:Smite(self.Golem1) end
                if self.Golem2 ~= nil and GetDistance(self.Golem2) <= 820 then self:Smite(self.Golem2) end
                if self.Lizard1 ~= nil and GetDistance(self.Lizard1) <= 820 then self:Smite(self.Lizard1) end
                if self.Lizard2 ~= nil and GetDistance(self.Lizard2) <= 820 then self:Smite(self.Lizard2) end
            end
        end
    end

    function AutoSmite:Smite(Creep)
        
        if Creep.health <= self.smiteDamage then
            Packet('S_CAST', {spellId = self.smiteSlot, targetNetworkId = Creep.networkID}):send()
        end
    end

    function AutoSmite:ScanJungle()
        if self.Vilemaw ~= nil then if not self.Vilemaw.valid or self.Vilemaw.dead or self.Vilemaw.health <= 0 then self.Vilemaw = nil end end
        if self.Nashor ~= nil then if not self.Nashor.valid or self.Nashor.dead or self.Nashor.health <= 0 then self.Nashor = nil end end
        if self.Dragon ~= nil then if not self.Dragon.valid or self.Dragon.dead or self.Dragon.health <= 0 then self.Dragon = nil end end
        if self.Golem1 ~= nil then if not self.Golem1.valid or self.Golem1.dead or self.Golem1.health <= 0 then self.Golem1 = nil end end
        if self.Golem2 ~= nil then if not self.Golem2.valid or self.Golem2.dead or self.Golem2.health <= 0 then self.Golem2 = nil end end
        if self.Lizard1 ~= nil then if not self.Lizard1.valid or self.Lizard1.dead or self.Lizard1.health <= 0 then self.Lizard1 = nil end end
        if self.Lizard2 ~= nil then if not self.Lizard2.valid or self.Lizard2.dead or self.Lizard2.health <= 0 then self.Lizard2 = nil end end
    end

    function AutoSmite:OnCreateObj(obj)
        if obj ~= nil and obj.type == "obj_AI_Minion" and obj.name ~= nil then
            if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = obj
            elseif obj.name == "Worm12.1.1" then self.Nashor = obj
            elseif obj.name == "Dragon6.1.1" then self.Dragon = obj
            elseif obj.name == "AncientGolem1.1.1" then self.Golem1 = obj
            elseif obj.name == "AncientGolem7.1.1" then self.Golem2 = obj
            elseif obj.name == "LizardElder4.1.1" then self.Lizard1 = obj
            elseif obj.name == "LizardElder10.1.1" then self.Lizard2 = obj 
            end
        end
    end

    function AutoSmite:OnDeleteObj(obj)
        if obj ~= nil and obj.name ~= nil then
            if obj.name == "TT_Spiderboss7.1.1" then self.Vilemaw = nil
            elseif obj.name == "Worm12.1.1" then self.Nashor = nil
            elseif obj.name == "Dragon6.1.1" then self.Dragon = nil
            elseif obj.name == "AncientGolem1.1.1" then self.Golem1 = nil
            elseif obj.name == "AncientGolem7.1.1" then self.Golem2 = nil
            elseif obj.name == "LizardElder4.1.1" then self.Lizard1 = nil
            elseif obj.name == "LizardElder10.1.1" then self.Lizard2 = nil 
            end
        end
    end

-- }



class "DamageManager" -- {

    DamageManager.instance = ""

    function DamageManager:__init() 
            _G.EnemyTable = {}     
            for i, enemy in pairs(GetEnemyHeroes()) do
                    EnemyTable[enemy.networkID] = {     pos = enemy, 
                                                       name = enemy.charName, 
                                                       name = enemy.charName, 
                                                        q=0,q2=0,q3=0,w=0,w2=0,w3=0,e=0,e2=0,e3=0,r=0,r2=0,r3=0,p=0,AA=0,
                                                        HextechGunblade=0,BilgewaterCutlass=0,BladeOfTheRuinedKing=0,Tiamat=0,RavenousHydra=0,items=0,IGNITE=0,
                                                        harass=0,
                                                        IndicatorText = "", IndicatorPos=0, NotReady = false, Pct = 0 
                                                        }
            end
            LibMenu.GetSub("draw"):addParam("drawIndicator", "Draw Damage Indicators", SCRIPT_PARAM_ONOFF, true)
            AddTickCallback(function() self:OnTick() end)
            AddDrawCallback(function() self:OnDraw() end)
            self.KillCombos = {}
            
    end

    function DamageManager.Instance(name)
        if DamageManager.instance == "" then DamageManager.instance = DamageManager() end return DamageManager.instance 
    end

    function DamageManager:OnDraw()

        if LibMenu.GetSub("draw").drawIndicator then
            for i, enemy in pairs(GetEnemyHeroes()) do
                if enemy.visible and not enemy.dead then
           --[[         enemy.barData = GetEnemyBarData()
                    local barPos = GetUnitHPBarPos(enemy)
                    local barPosOffset = GetUnitHPBarOffset(enemy)
                    local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
                    local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
                    local BarPosOffsetX = 0
                    local BarPosOffsetY = 140
                    local CorrectionY =  14.5
                    local StartHpPos = -80
                    local IndicatorPos = EnemyTable[enemy.networkID].IndicatorPos
                    barPos.x = barPos.x+ barPosPercentageOffset.x
                    barPos.y = barPos.y +barPosPercentageOffset.y
                    local Text = EnemyTable[enemy.networkID].IndicatorText
                    if IndicatorPos ~= nil then
                        DrawText(tostring(Text),13,barPos.x+IndicatorPos - 10 ,barPos.y-27 ,ARGB(255,0,255,0))     
                        DrawText("|",13,barPos.x+IndicatorPos ,barPos.y ,ARGB(255,0,255,0))
                        DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-9 ,ARGB(255,0,255,0))
                        DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-18 ,ARGB(255,0,255,0))
                    end
            ]]

                    -- Temp fix for ondraw bugsplat
                    local Text = EnemyTable[enemy.networkID].IndicatorText
                    local pos= WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
                    local posX = pos.x - 35
                    local posY = pos.y - 60
                    DrawText(tostring(Text),15,posX ,posY  ,ARGB(255,0,255,0))   



                end
            end
        end
    end

    function DamageManager:OnTick()

        for i, enemy in pairs(GetEnemyHeroes()) do

            if ValidTarget(enemy) and enemy.visible and GetDistance(enemy, cameraPos) < 3000 then
                EnemyTable[enemy.networkID].q = getDmg("Q", enemy, myHero)
                EnemyTable[enemy.networkID].q2 = getDmg("Q", enemy, myHero,2)
                EnemyTable[enemy.networkID].q3 = getDmg("Q", enemy, myHero,3)
                EnemyTable[enemy.networkID].w = getDmg("W", enemy, myHero)
                EnemyTable[enemy.networkID].w2 = getDmg("W", enemy, myHero,2)
                EnemyTable[enemy.networkID].w3 = getDmg("W", enemy, myHero,3)

                EnemyTable[enemy.networkID].e = getDmg("E", enemy, myHero)
                EnemyTable[enemy.networkID].e2 = getDmg("E", enemy, myHero,2)
                EnemyTable[enemy.networkID].e3 = getDmg("E", enemy, myHero,3)


                EnemyTable[enemy.networkID].r = getDmg("R", enemy, myHero)
                EnemyTable[enemy.networkID].r2 = getDmg("R", enemy, myHero,2)
                EnemyTable[enemy.networkID].r3 = getDmg("R", enemy, myHero,3)

                EnemyTable[enemy.networkID].p = getDmg("P", enemy, myHero)
                EnemyTable[enemy.networkID].aa = getDmg("AD", enemy, myHero)

                EnemyTable[enemy.networkID].HextechGunblade = GetInventoryItemIsCastable(3146) and getDmg("HXG", enemy, myHero) or 0
                EnemyTable[enemy.networkID].BilgewaterCutlass = GetInventoryItemIsCastable(3144) and getDmg("BWC", enemy, myHero) or 0
                EnemyTable[enemy.networkID].BladeOfTheRuinedKing = GetInventoryItemIsCastable(3153) and getDmg("RUINEDKING", enemy, myHero) or 0
                EnemyTable[enemy.networkID].Tiamat = GetInventoryItemIsCastable(3077) and getDmg("TIAMAT", enemy, myHero) or 0
                EnemyTable[enemy.networkID].RavenousHydra = GetInventoryItemIsCastable(3128) and getDmg("HYDRA", enemy, myHero) or 0
                EnemyTable[enemy.networkID].Dfg = GetInventoryItemIsCastable(3074) and getDmg("DFG", enemy, myHero) or 0



                EnemyTable[enemy.networkID].items =             EnemyTable[enemy.networkID].HextechGunblade +
                                                                EnemyTable[enemy.networkID].BilgewaterCutlass +
                                                                EnemyTable[enemy.networkID].BladeOfTheRuinedKing +
                                                                EnemyTable[enemy.networkID].Tiamat +
                                                                EnemyTable[enemy.networkID].RavenousHydra

                EnemyTable[enemy.networkID].IGNITE = 50+(myHero.level*20)


            end
        end
    end

    function DamageManager.Damage(spell, enemy)
        
        return DamageManager.Instance():_Damage(spell, enemy)
    end

    function DamageManager:_Damage(spell, enemy)
        if EnemyTable[enemy.networkID] then
            return EnemyTable[enemy.networkID][spell]
        else
            return 0
        end

    end

    function DamageManager.KillCombo(enemy, damage, text)
        return DamageManager.Instance():_KillCombo(enemy, damage, text)
    end

    function DamageManager:_KillCombo(enemy, damage, text)
        if ValidTarget(enemy) and enemy.visible and GetDistance(enemy, cameraPos) < 3000 and enemy.health <= damage then
            EnemyTable[enemy.networkID].IndicatorPos = 0
            EnemyTable[enemy.networkID].IndicatorText = text
            return true
        else
            return false
        end
    end


    function DamageManager.HarassCombo(enemy, damage)
        DamageManager.Instance():_HarassCombo(enemy, damage)
    end

    function DamageManager:_HarassCombo(enemy, damage)
        if ValidTarget(enemy) and enemy.visible and GetDistance(enemy, cameraPos) < 3000 then
            local HealthLeft = math.round(enemy.health - damage)
            local PctLeft = math.round(HealthLeft / enemy.maxHealth * 100)
            local BarPct = PctLeft / 103 * 100
            EnemyTable[enemy.networkID].Pct = PctLeft
            EnemyTable[enemy.networkID].IndicatorPos = BarPct
            EnemyTable[enemy.networkID].IndicatorText = PctLeft .. "% Harass"
        end   
    end

-- }



class 'HighPrioritySpells' -- {

    function HighPrioritySpells:__init()
        self.needInterrupt = {}
        AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
        AddTickCallback(function() self:OnTick() end)
        _G.AdvancedCallback:register('OnCanInterrupt')
    end

    function HighPrioritySpells:OnTick()
            for nwid, spell in pairs(self.needInterrupt) do
             if self.needInterrupt[nwid] then
               AdvancedCallback:OnCanInterrupt(spell.unit, spell.buff) 
             end
            end
    end

    function HighPrioritySpells:OnGainBuff(unit, buff)
        if unit.team ~= myHero.team and InterruptBuffs[buff.name]  then
            local nwid = unit.networkID
            self.needInterrupt[nwid] = { unit = unit, buff = buff}
        end    
    end

    function HighPrioritySpells:OnLoseBuff(unit, buff)
        if unit.team ~= myHero.team and InterruptBuffs[buff.name] then
            local nwid = unit.networkID
            self.needInterrupt[nwid] = nil
        end
    end


function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end     
function arrangePrioritys(enemies)
    local priorityOrder = {
        [2] = {1,1,2,2,2},
        [3] = {1,1,2,3,3},
        [4] = {1,2,3,4,4},
        [5] = {1,2,3,4,5},
    }
    for i, enemy in ipairs(GetEnemyHeroes()) do
        SetPriority(priorityTable.AD_Carry, enemy, priorityOrder[enemies][1])
        SetPriority(priorityTable.AP,       enemy, priorityOrder[enemies][2])
        SetPriority(priorityTable.Support,  enemy, priorityOrder[enemies][3])
        SetPriority(priorityTable.Bruiser,  enemy, priorityOrder[enemies][4])
        SetPriority(priorityTable.Tank,     enemy, priorityOrder[enemies][5])
    end
end

function LoadTargets()
    if #GetEnemyHeroes() <= 1 then
        PrintChat("Not enough enemies, can't arrange priority's!")
    else
        arrangePrioritys(#GetEnemyHeroes())
        PrintChat(" >> Arranged priority's!")
    end
end



function getHitBoxRadius(unit)
    if unit ~= nil then 
        return GetDistance(unit.minBBox, unit.maxBBox)/2
    else
        return 0
    end
end

-- Global Tables

_G.Item = {
    -- Shields Self
    ["ZhonyasHourglass"] = ItemHandler(3157),
    ["SeraphsEmbrace"] = ItemHandler(3040),
    ["QuicksilverSash"] = ItemHandler(3140),
    ["MercurialScimitar"] = ItemHandler(3139),

    -- Shields Hero
    ["Locket"] = ItemHandler(3190),

    -- Active
    ["Tiamat"] = ItemHandler(3077,"Tiamat"), -- Resets AA
    ["RavenousHydra"] = ItemHandler(3074,"RavenousHydra"), -- Resets AA
    ["BilgewaterCutlass"] = ItemHandler(3144, "BilgewaterCutlass"), 
    ["BladeOfTheRuinedKing"] = ItemHandler(3153,"BladeOfTheRuinedKing"),
    ["HextechGunblade"] = ItemHandler(3146, "HextechGunblade"),    
    ["DeathfireGrasp"] = ItemHandler(3128),
    ["Entropy"] = ItemHandler(3184),
    ["YoumuusGhostblade"] = ItemHandler(3142),
    ["BlackfireTorch"] = ItemHandler(3188),   
    ["RanduinsOmen"] = ItemHandler(3143),

    -- Toggle 
    ["Muramana"] = ItemHandler(3042),

    -- Aura effect
    ["SunfireCape"] = ItemHandler(3068),

    -- OnHit effect
    ["KitaesBloodrazor"] = ItemHandler(3186),   
    ["SpiritOfTheElderLizard"] = ItemHandler(3209),
    ["NashorsTooth"] = ItemHandler(3115),
    ["RunaansHurricane"] = ItemHandler(3085),
    ["LiandrysTorment"] = ItemHandler(3151),
    ["Lightbringer"] = ItemHandler(3185),
    ["WitsEnd"] = ItemHandler(3091),   
    ["StatikkShiv"] = ItemHandler(3087),

    -- Proc effect
    ["Sheen"] = ItemHandler(3057), 
    ["LichBane"] = ItemHandler(3100), 
    ["TrinityForce"] = ItemHandler(3087),
    ["IcebornGauntlet"] = ItemHandler(3025), 

    -- Wards
    ["RubySightstone"] = ItemHandler(2045), 
    ["Sightstone"] = ItemHandler(2049), 
    ["WrigglesLantern"] = ItemHandler(3154), 
    ["ExplorersWard"] = ItemHandler(2050), 
    ["SightWard"] = ItemHandler(2044), 
    ["VisionWard"] = ItemHandler(2043),

    -- Potions
    ["HealthPotion"] = ItemHandler(2003),
    ["Biscuit"] = ItemHandler(2009),
    ["Flask"] = ItemHandler(2041), 
    ["Elixer"] = ItemHandler(2037),
    ["ManaPotion"] = ItemHandler(2004)

}

IsAttack = {
    ["frostarrow"] = true, 
    ["CaitlynHeadshotMissile"] = true, 
    ["QuinnWEnhanced"] = true, 
    ["TrundleQ"] = true, 
    ["XenZhaoThrust"] = true, 
    ["XenZhaoThrust"] = true, 
    ["XenZhaoThrust3"] = true, 
    ["RenektonExecute"] = true, 
    ["GarenSlash2"] = true, 
    ["RenektonSuperExecute"] = true, 
    ["KennenMegaProc"] = true
}
IsNotAttack = {
    ["shyvanadoubleattackdragon"] = true, 
    ["ShyvanaDoubleAttack"] = true, 
    ["MonkeyKingDoubleAttack"] = true
}
ResetAttack = { 
    ["PowerFist"] = true, 
    ["DariusNoxianTacticsONH"] = true, 
    ["Takedown"] = true,
    ["Ricochet"] = true, 
    ["BlindingDart"] = true, 
    ["VayneTumble"] = true, 
    ["JaxEmpowerTwo"] = true, 
    ["MordekaiserMaceOfSpades"] = true, 
    ["SiphoningStrikeNew"] = true, 
    ["RengarQ"] = true, 
    ["MonkeyKingDoubleAttack"] = true, 
    ["YorickSpectral"] = true, 
    ["ViE"] = true, 
    ["GarenSlash3"] = true, 
    ["XenZhaoComboTarget"] = true, 
    ["LeonaShieldOfDaybreak"] = true, 
    ["ShyvanaDoubleAttack"] = true, 
    ["shyvanadoubleattackdragon"] = true, 
    ["TalonNoxianDiplomacy"] = true, 
    ["TrundleTrollSmash"] = true, 
    ["VolibearQ"] = true, 
    ["PoppyDevastatingBlow"] = true, 
    ["SivirW"] = true, 
    ["FioraFlurry"] = true, 
    ["NautilusPiercingGaze"] = true, 
    ["Parley"] = true, 
    ["jaycehypercharge"] = true,
    ["Tiamat"] = true,
    ["RavenousHydra"] = true
}

    InterruptBuffs = {
    ["katarinasound"] = true, -- Katarina R
    ["GalioIdolOfDurand"] = true, 
    ["fearmonger_marker"] = true, --fiddle W
    ["AbsoluteZero"] = true, 
    ["gate"] = true, -- Twisted fate R
    ["ShenStandUnited"] = true, 
    ["UrgotSwap2"] = true, 
    ["alzaharnethergraspsound"] = true, 
    ["FallenOne"] = true, 
    ["Pantheon_GrandSkyfall_Jump"] = true, 
    ["CaitlynAceintheHole"] = true, 
    ["MissFortuneBulletTime"] = true, 
    ["VarusQ"] = true, 
    ["Meditate"] = true,  
    ["ZacE"] = true,  
    ["InfiniteDuress"] = true  
    }
    InterruptSpells = {
    ["Crowstorm"] = true, 
}


priorityTable = {
    AP = {
        "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
    },
    Support = {
        "Blitzcrank", "Janna", "Karma", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
    },
     
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar", "Leona",
    },
     
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "MasterYi", "Yasuo",
    },
     
    Bruiser = {
        "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
    },
}
