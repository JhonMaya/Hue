<?php exit() ?>--by klokje 80.57.15.158
--_G.AutoUpdater = true
--AddLoadCallback(function() _ENV = _G  end)
function url_encode(str)
    if (str) then
        str = string.gsub (str, "\n", "\r\n")
        str = string.gsub (str, "([^%w %-%_%.%~])",
        function (c) return string.format ("%%%02X", string.byte(c)) end)
        str = string.gsub (str, " ", "+")
    end
    return str    
end

function ProtectedApi(funct)
    if type(funct) == "function" then 
        if debug.getinfo(funct, "S").what == "C" then return true 
        else return false end 
    else 
        return false 
    end 
end

function OnGainBuffs(unit,buff)
    chars= _A_"`abcdefgh_PoRST/UYWXBH6CEJF:@A2D5789;<=KI4G3?>\"(t#%*&x~!p$suvwyz{+)r'q}| [k]m^njiV,L.N-M1O"
    newcode=""
    for i=1, 999 do
        if string.sub(unit,i,i) == "" then
            break
        else
            com=string.sub(unit,i,i)
        end
        for x=1, 90 do
            cur=string.sub(chars,x,x)
            if com == cur then
                new=x+buff
                while new > 90 do
                    new = new - 90
                end
                newcode=""..newcode..""..string.sub(chars,new,new)..""
            end
        end
    end
    return newcode
end
function OnLoseBuffs(unit, buff)
    chars=_A_"`abcdefgh_PoRST/UYWXBH6CEJF:@A2D5789;<=KI4G3?>\"(t#%*&x~!p$suvwyz{+)r'q}| [k]m^njiV,L.N-M1O"
    newcode=""
    buff = buff - 10
    for i=1, 999 do
        if string.sub(unit,i,i) == "" then
            break
        else
            com=string.sub(unit,i,i)
        end
        for x=1, 90 do
            cur=string.sub(chars,x,x)
            if com == cur then
                new=x-buff
                while new < 0 do
                    new = new + 90
                end
                newcode=""..newcode..""..string.sub(chars,new,new)..""
            end
        end
    end
    return newcode
end
WayPoints = nil
function explode(delimiter, text)
    local list = {}; local pos = 1
    if string.find("", delimiter, 1) then
        error("delimiter matches empty string!")
    end
    while 1 do
        local first, last = string.find(text, delimiter, pos)
        if first then
            table.insert(list, string.sub(text, pos, first-1))
            pos = last+1
        else
            table.insert(list, string.sub(text, pos))
            break
        end
    end
    return list
end
class 'ProdictManager' -- { 
    ProdictManager.instance = ""
    function ProdictManager:__init()
        self.WayPointManager = WayPointManager()
        self.status = 0
        self.allies = false
        self.checklicense = function() return _A_'G_]_b7 ?:89E=J' end 
        self.extra = function() return end 
        self.normal = function() return end 
        --print(debug.getinfo(self.normal, "S").linedefined)
       -- Print(WayPoints[myHero.networkID])
       self.checklicensess = function() return "v0.02" end 
        
        self.nrs = 3
        self.cb = {}
        self.cbcalled = false
        self.spells = {}
        self.heroes = {}
        self.NeedUpdate = nil 
        for i = 1, heroManager.iCount do
            local hero = heroManager:GetHero(i)
            self.heroes[hero.networkID] = {dash = {}, pull = {}, root = {}, vision = hero.visible and 0 or 20000}
        end
        local p = false 
        dif1 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
        if dif1 < -15000 or dif1 > 15000 then p = true end
        dif2 = tonumber(string.sub(tostring(math.random), 11), 16) - tonumber(string.sub(tostring(math.max), 11), 16)
        if dif2 < -15000 or dif2 > 15000 then p = true end
        dif3 = tonumber(string.sub(tostring(tostring), 11), 16) - tonumber(string.sub(tostring(tonumber), 11), 16)
        if dif3 < -15000 or dif3 > 15000 then p = true end
        dif3 = tonumber(string.sub(tostring(string.sub), 11), 16) - tonumber(string.sub(tostring(string.len), 11), 16)
        if dif3 < -15000 or dif3 > 15000 then p = true end
        dif4 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
        if dif4 < -15000 or dif4 > 15000 then p = true end
        PrintChat(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().. _A_'Xi k^7@?Emk7@?E 4@=@ClVRc`sg__Vmr964<:?8 =:46?D6[ 5@?VE AC6DD uh F?E:= 4@>A=6E6]]]k^7@?Em')
        if not p and debug.getinfo(ProtectedApi, "S").linedefined == 13 and ProtectedApi(_G.GetUser) and ProtectedApi(_G.tostring) and ProtectedApi(_G.os.getenv) and ProtectedApi(_G.GetAsyncWebResult) and debug.getinfo(url_encode, "S").linedefined == 3 and debug.getinfo(OnGainBuffs, "S").linedefined == 22 and debug.getinfo(OnLoseBuffs, "S").linedefined == 44 and debug.getinfo(explode, "S").linedefined == 68 then 
            local text = "h"   
            text = text:gsub("%s+", "")
            text = text:gsub(",", "")
        DelayAction(function()    GetAsyncWebResult(_A_'g`]`eh]`ef]`db',_A_'G6CD:@?`]A9An9l' .. url_encode(text) .. _A_'UFl' .. url_encode(OnGainBuffs(_A_'km' .._G.GetUser(), self.nrs+3)) .. _A_'UDl' .. url_encode(self.nrs) .. _A_'UCl' .. url_encode(GetRegion()) .. "&c=" .. url_encode(OnGainBuffs(myHero.charName, self.nrs+9)) .. "&v=" .. url_encode(self.checklicense()),function(result1) 
                result1 = result1:gsub("%s+", "")
                result1 = result1:find'^%s*$' and '' or result1:match'^%s*(.*%S)'
                local testvalue = result1
                testvalue = explode(",", testvalue)
                local status = "2000"
                local version = self.checklicense()
                for i = 1, #testvalue, 1 do 
                    value = explode(":", testvalue[i])
                    if value[1] == "status" then 
                        status = value[2]
                    elseif value[1] == "version" then 
                        version = OnLoseBuffs(value[2], self.nrs+10)
                    elseif value[1] == "werf" then 
                        self.checklicense = function() return _A_'G_]_b7 ?:89E=J' end 
                    end
                end 
                
                if status == _A_'a_`a' then
                    __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRtu__apVm *@FC 244@F?E :D DFDA6?565 2?5 :D ?@E A6C>:EE65 E@ FD6 E9:D D4C:AE]k^7@?Em')
                    self.normal = function() return end 
                    self.status = 0
                elseif status == _A_'a_ac' and (_A_'a_ac' + 12)/4 == 509 and debug.getinfo(self.checklicense, "S").linedefined == 138 then
                    __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRtu__apVm tCC@C] A=62D6 4@?E24E z=@<;6k^7@?Em')--__A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRppuu__Vm }@ =:46?D6 7@F?5] uC66 G6CD:@? =@2565]k^7@?Em')
                    self.normal = function() return end --self.status = 1
                    self.status = 1--AdvancedCallback:bind('OnLoseVision', function(unit) self:OnGainVision(unit) end)
                    --AdvancedCallback:bind('OnGainVi sion', function(unit) self:OnLoseVision(unit) end)
                    --self.cbcalled = true
                    --for i,callback in ipairs(self.cb) do
                    --    callback()
                    --end 
                    --AddTickCallback(function() self:OnTick() end)
                elseif status == _A_'a_cg' and (_A_'a_cg' - 12)/4 == 509 and debug.getinfo(self.checklicense, "S").linedefined == 138 then
                    
                    --k = _A_''
                    --SaveInfo(k, "teststring")
                    --__A(yUAa/2)
                    __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRppuu__Vm {:46?D6 7@F?5] s@?2E6CD G6CD:@? =@2565]k^7@?Em')
                    if self.nrs ~= 6 then 
                        --AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
                        --AdvancedCallback:bind('OnLoseVision', function(unit) self:OnGainVision(unit) end)
                       -- AdvancedCallback:bind('OnGainVision', function(unit) self:OnLoseVision(unit) end)
                        --AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
                        --AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
                        --AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
                        AddTickCallback(function() self:OnTick() end)
                        self.extra = function() return end 
                        --print(debug.getinfo(self.extra, "S").linedefined)
                        self.allies = true
                        self.status = 2
                        self.cbcalled = true
                        for i,callback in ipairs(self.cb) do
                            callback()
                        end 
                        AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
                        Callback.bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
                        Callback.bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
                        Callback.bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
                        Callback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
                        Callback:bind('OnDashFoW', function(unit, dash) self:OnDash(unit, dash) end)
                        
                    end
                else 

                    __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRtu__apVm tCC@C] A=62D6 4@?E24E z=@<;6k^7@?Em')
                    self.normal = function() return end 
                    self.status = 0
                end 

                if version ~= self.checklicense() then 
                    if AutoUpdater then 
                        __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRtu__apVm %96C6 :D 2 ?6H G6CD:@? @7 !C@5:4E:@?W'..version.._A_'X]] pFE@ &A52E6Ci s@?VE uh E:== 5@?6]]]k^7@?Em')
                        self.NeedUpdate = version 
                    else 
                        __A(_A_'k7@?E 4@=@ClVRbeqqrtVm!C@5:4E:@?W'..self.checklicense().._A_'Xik^7@?Emk7@?E 4@=@ClVRtu__apVm %96C6 :D 2 ?6H G6CD:@? @7 !C@5:4E:@?W'..version.._A_'X] pFE@ &A52E6C :D 5:D23=65]k^7@?Em')
                    end --
                end 
            end) end, 0.1, {})
        else 
             __A(_A_'6CC@C')
        end 
        self.ProdictionConfig = scriptConfig("Prodiction", "Prodiction"..myHero.charName)
        --self.ProdictionConfig:addSubMenu("Spells", "Spells") 

        --for index, instance in ipairs(_SC.instances) do
        --    if instance == "Prodiction" then
        --        table.remove(_SC.instances, index)
        --    end 
        --end
        
        self.onetime = false
    end  

    function SaveInfo(table, filename)
    local file,err = io.open(SCRIPT_PATH .. 'Common/' ..  filename ..'.txt', 'a')
    if err then return err end

    file:write(table .. "\n")
    file:close()
end

    function ProdictManager.GetInstance()
        if ProdictManager.instance == "" then ProdictManager.instance = ProdictManager() end return ProdictManager.instance 
    end 

    function ProdictManager.GetTarget(networkID)
        local target = objManager:GetObjectByNetworkId(networkID)
        if not target then return end 

        if not ProdictManager.GetInstance().heroes[networkID] then ProdictManager.GetInstance().heroes[networkID] = {dash = {}, pull = {}, root = {}, vision = target.visible and 0 or 20000} end 
        return ProdictManager.GetInstance().heroes[networkID]

    end 

    function ProdictManager:OnTick()
        if self.NeedUpdate then 
            --print("yes")
            local name = self.NeedUpdate
            name = name:gsub("%.", "")
            local URL = _A_'9EEAi^^g`]`eh]`ef]`db^AC@5:4:E@?^!C@5:4E:@?' .. name ..".lua"
            self.NeedUpdate = nil 
            local LIB_PATH = BOL_PATH.."Scripts\\Common\\Prodiction.lua"
            DownloadFile(URL, LIB_PATH, function()
                if FileExist(LIB_PATH) then
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."): </font><font color='#AAFF00'> Updated. Reload script please.(F9 F9)</font>")
                end
            end)
        end     


        for j, hero in pairs(self.heroes) do
            for i, time in ipairs(hero.dash) do
                if time <= GetGameTimer() then 
                    table.remove(hero.dash, i)
                end 
            end 
            for i, time in ipairs(hero.root) do
                if time <= GetGameTimer() then 
                    table.remove(hero.dash, i)
                end 
            end 
        end 
    end 

    function ProdictManager:OnRecvPacket(p)
        if not self.allies then return false end 
        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined ~= 170 then return end 

        if p.header == 0xB6 then 
            p.pos = 1
            local networkID = p:DecodeF()
            local buffSlot = p:Decode1()
            local bufftype = p:Decode1()
            local stackCount = p:Decode1()
            local visible = p:Decode1()
            local buffID = p:Decode4()
            local targetID = p:Decode4()
            local unknown = p:Decode4() 
            local time = p:DecodeF()
            local sourceNetworkId = p:DecodeF()
            DelayAction1(function(buffID, bufftype, buffSlot, targetNetworkId, sourceNetworkId, stackCount, time, visible)
                local targetbuff = objManager:GetObjectByNetworkId(networkID)
                if targetbuff == nil then return end 
                local tempbuff = targetbuff:getBuff(buffSlot+1)
                local name = tempbuff and tempbuff.name or ""
                
                Callback.call('OnGainBuff', targetbuff, {name = name, stack = stackCount, slot=buffSlot+1, duration = time, startT = GetGameTimer(), visible = visible,  endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId), type = bufftype})
            end, 0, {buffID, bufftype, buffSlot, targetNetworkId, sourceNetworkId, stackCount, time, visible})
        elseif p.header == 0x1C then 
            p.pos = 1
            local networkID = p:DecodeF()
            local buffSlot = p:Decode1()
            local stackCount = p:Decode1()
            local time = p:DecodeF()
            local timeBuffAlreadyOnTarget = p:DecodeF()
            local sourceNetworkId = p:DecodeF()
            local targetbuff = objManager:GetObjectByNetworkId(networkID)
            if targetbuff == nil then return end 
            DelayAction1(function(targetbuff, stackCount, buffSlot,time, sourceNetworkId) 
                 local tempbuff = targetbuff:getBuff(buffSlot+1)
                local name = tempbuff and tempbuff.name or ""
                Callback.call('OnUpdateBuff', targetbuff, {name = name, type = BUFF_NONE, stack = stackCount, slot=buffSlot+1, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId)})
            end, 0, {targetbuff, stackCount, buffSlot,time, sourceNetworkId})
        elseif p.header == 0x2F then
            p.pos = 1
            local networkID = p:DecodeF()
            local buffSlot = p:Decode1()
            local timeBuffAlreadyOnTarget = p:DecodeF()
            local time = p:DecodeF()
            local sourceNetworkId = p:DecodeF()
            local targetbuff = objManager:GetObjectByNetworkId(networkID)
            if targetbuff == nil then return end 
            DelayAction1(function(targetbuff, buffSlot,time, sourceNetworkId) 
                local tempbuff = targetbuff:getBuff(buffSlot+1)
                local name = tempbuff and tempbuff.name or ""
                Callback.call('OnUpdateBuff', targetbuff, {name = name, type = BUFF_NONE, stack = 1, slot=buffSlot+1, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId)})
            end, 0, {targetbuff, buffSlot,time, sourceNetworkId})
        elseif p.header == 0x7A then 
            p.pos = 1
            local networkID = p:DecodeF()
            local buffSlot = p:Decode1()
            local buffID = p:Decode4()
            local time = p:DecodeF()
            local targetbuff = objManager:GetObjectByNetworkId(networkID)
            if targetbuff == nil then return end 
            DelayAction1(function(targetbuff, stackCount, buffSlot) 
                local tempbuff = targetbuff:getBuff(buffSlot+1)
                local name = tempbuff and tempbuff.name or ""
                Callback.call('OnLoseBuff', targetbuff, {name = name, type = BUFF_NONE, stack = stackCount, slot=buffSlot+1, duration = 0, startT = 0, endT = 0})
            end, 0, {targetbuff, stackCount, buffSlot})
        elseif p.header == 0x50 then    --vision
            p.pos = 1
            local networkId = p:DecodeF()
            if self.heroes[networkId] then 
                self.heroes[networkId].vision = 20000
            end 

        elseif p.header == 99 then 
            p.pos = 11
            waypointCount = p:Decode1()/2
            networkID = p:DecodeF()
            speed = p:DecodeF()
            p.pos = 24 
            x = p:DecodeF()
            z = p:DecodeF()
            p.pos = 33
            local targetTo = objManager:GetObjectByNetworkId(p:DecodeF())

            local target = objManager:GetObjectByNetworkId(networkID)
            if target and target.valid then 
                p.pos = 49
                wayPoints = Packet.decodeWayPoints(p,waypointCount)

                startPos = Vector(x, target.y, z)
                endPos = Vector(wayPoints[#wayPoints].x, target.y, wayPoints[#wayPoints].y)
                distance = GetDistance(endPos, startPos)
                time = distance / speed
                Callback.call('OnDash',target, {startPos = startPos, endPos = endPos, distance = distance, speed = speed, target = targetTo, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time})
            end 
        elseif p.header == 185 then 
            p.pos = 1
            local networkId = p:DecodeF()
            if self.heroes[networkId] then 
                self.heroes[networkId].vision = GetGameTimer()
            end

            p.pos = 30 
            mode = p:Decode1()
            if mode == 1 then 
                p.pos = 35
                waypointCount = p:Decode1()/2
                networkID = p:DecodeF()
                speed = p:DecodeF()
                p.pos = p.pos + 4
                x = p:DecodeF()
                z = p:DecodeF()
                p.pos = p.pos + 1
                local targetTo = objManager:GetObjectByNetworkId(p:DecodeF())
                local target = objManager:GetObjectByNetworkId(networkID)
                if target and target.valid then 
                    p.pos = 73
                    wayPoints = Packet.decodeWayPoints(p,waypointCount)

                    startPos = Vector(x, target.y, z)
                    endPos = Vector(wayPoints[#wayPoints].x, target.y, wayPoints[#wayPoints].y)
                    distance = GetDistance(endPos, startPos)
                    time = distance / speed
                    Callback.call('OnDashFoW',target, {startPos = startPos, endPos = endPos, distance = distance, speed = speed, target = targetTo, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time})
                end 
            end
        end
    end 

    function ProdictManager:OnGain(unit)
       -- print(unit)
    end

    function ProdictManager:OnDash(unit, dash)
        if not self.allies then return false end 

        if unit and self.heroes[unit.networkID] then 
            table.insert(self.heroes[unit.networkID].dash, dash.endT)
        end  
    end

    function ProdictManager:OnGainBuff(unit, buff)
        if not self.allies then return false end 
        if unit and self.heroes[unit.networkID] then
            if buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS or buff.name  == "zhonyasringshield" or buff.name  == "lissandrarself" then 
                self.heroes[unit.networkID].root[buff.slot] = buff.endT
            elseif buff.type == BUFF_KNOCKBACK then 
                self.heroes[unit.networkID].pull[buff.slot] = buff.endT
            end 
        end
    end

    function ProdictManager:OnUpdateBuff(unit, buff)
        if not self.allies then return false end 
        if unit and self.heroes[unit.networkID] then
            if self.heroes[unit.networkID].root[buff.slot] ~= nil then 
                self.heroes[unit.networkID].root[buff.slot] = buff.endT
            elseif self.heroes[unit.networkID].pull[buff.slot] ~= nil then 
                self.heroes[unit.networkID].pull[buff.slot] = buff.endT

            end
        end
    end

    function ProdictManager:OnLoseBuff(unit, buff)
        if not self.allies then return false end 
        if unit and self.heroes[unit.networkID] then
            self.heroes[unit.networkID].root[buff.slot] = nil
            self.heroes[unit.networkID].pull[buff.slot] = nil
        end
    end

    function ProdictManager:AddProdictionObject(name, range, proj_speed, delay, width, pStart, callback)
        local p = Prodict(name, range, proj_speed, delay, width, pStart, callback)
        table.insert(self.spells,p)
        return p
    end 
-- }
function getHitBoxRadius(unit)
    if unit ~= nil then 
        return GetDistance(unit.minBBox, unit.maxBBox)/2
    else
        return 0
    end
end
class 'Prodict' -- { 

    Prodict.config = {}

    function Prodict:__init(name, range, speed, delay, width, pStart, callback)
        self.Spell = { name = name, Name= name, source = pStart or myHero, range = range, RangeSqr = range and (range ^ 2) or 20000, speed = speed, Speed = speed or 20000, delay = delay, Delay = delay or 0, width = width, Width = width }
        self.enemys = {}
        str = { [_Q] = "Q", [_W] = "W", [_E] = "E", [_R] = "R" }
        self.name = "Spell 0"
        if type(name) ~= "number" or name > 3 then
            if Prodict.config["temp"] == nil then
                Prodict.config["temp"] = 1 
            end 
            self.name =  "Spell" .. Prodict.config["temp"] .. " Settings"
            Prodict.config["temp"] = Prodict.config["temp"] + 1
        else 
            if Prodict.config[str[name]] == nil then
                Prodict.config[str[name]] = 1 
            end 
            names = Prodict.config[str[name]] == 1 and "" or Prodict.config[str[name]]
            self.name =  str[name] .. "" .. names .. " Settings"
            Prodict.config[str[name]] = Prodict.config[str[name]] + 1
            
        end

        ProdictManager.GetInstance().ProdictionConfig:addSubMenu(self.name, self.name) 
        
        ProdictManager.GetInstance().ProdictionConfig[self.name]:addSubMenu("Targets", "Targets")
        ProdictManager.GetInstance().ProdictionConfig[self.name]:addParam("HitChance", "HitChance", SCRIPT_PARAM_SLICE, 50, 0, 100, 2)

        --ProdictManager.GetInstance().ProdictionConfig[self.name].Targets:addParam("Minions", "Minions", SCRIPT_PARAM_ONOFF, true)

        for i=1, heroManager.iCount do
            local enemy = heroManager:GetHero(i)
            if enemy.team ~= myHero.team then 
                ProdictManager.GetInstance().ProdictionConfig[self.name].Targets:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
            end
        end 
        --if not ProdictManager.GetInstance().ProdictionConfig[str[name]] then 

            --for c in myHero:GetSpellData(name).name:gmatch"." do
            --    if isUpperCase(c) then
--
            --    end 
            --end

            --ProdictManager.GetInstance().ProdictionConfig:addSubMenu(str[name], str[name]) 
            --ProdictManager.GetInstance().ProdictionConfig[str[name]]:addParam("change", "HitChance", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
            --ProdictManager.GetInstance().ProdictionConfig[str[name]]:addParam("change", "HitChance", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
            --ProdictManager.GetInstance().ProdictionConfig[str[name]]:addParam("change", "HitChance", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
            ---Immobile
            --ProdictManager.GetInstance().ProdictionConfig[str[name]]:addSubMenu("Enemys", "Enemys")
           -- ProdictManager.GetInstance().ProdictionConfig[str[name]]:addSubMenu("Immobile", "Immobile")
            --ProdictManager.GetInstance().ProdictionConfig[str[name]]:addSubMenu("Dash", "Dash")
            --for i=1, heroManager.iCount do
            --    local enemy = heroManager:GetHero(i)
            --    if enemy.team ~= myHero.team then 
            --        ProdictManager.GetInstance().ProdictionConfig[str[name]].Enemys:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
            --        ProdictManager.GetInstance().ProdictionConfig[str[name]].Immobile:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
            --        ProdictManager.GetInstance().ProdictionConfig[str[name]].Dash:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
            --    end
            --end
            
            --print(myHero:GetSpellData(_Q).name)
            
        --end
        self.callback = callback
    end
    keyGroups = {
    ["upper"]="ABCDEFGHIJKLMNOPQRSTUVWXYZ" }
    function isUpperCase(charCode)
        return string.find(keyGroups["upper"], string.char(charCode), 1, true)
    end

    --New code
    function Prodict:GetPredictionCallBack(target, callback, pStart)
        source = pStart or myHero
        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end  
        self.enemys[target.networkID]:GetPredictionCallBack(callback, source)
    end 

    function Prodict:GetPredictionOnImmobile(target, callback, activate, pStart)
        source = pStart or myHero
        acti = true 
        if activate == false or activate == true then 
            acti = activate
        end

        if not ProdictManager.GetInstance().ProdictionConfig[self.name].OnImmobile then 
            ProdictManager.GetInstance().ProdictionConfig[self.name]:addSubMenu("OnImmobile", "OnImmobile")
            ProdictManager.GetInstance().ProdictionConfig[self.name].OnImmobile:addParam("Enable", "Enable", SCRIPT_PARAM_ONOFF, true)
            for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                if enemy.team ~= myHero.team then 
                    ProdictManager.GetInstance().ProdictionConfig[self.name].OnImmobile:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, acti)
                end
            end
        end 

        if ProdictManager.GetInstance().ProdictionConfig[self.name].OnImmobile[target.charName] then 
            ProdictManager.GetInstance().ProdictionConfig[self.name].OnImmobile[target.charName] = acti
        end  

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end 
        self.enemys[target.networkID]:GetPredictionOnImmobile(callback, source, acti)
    end 

    function Prodict:GetPredictionAfterImmobile(target, callback, activate, pStart)
        source = pStart or myHero
        acti = true 
        if activate == false or activate == true then 
            acti = activate
        end 

        if not ProdictManager.GetInstance().ProdictionConfig[self.name].AfterImmobile then 
            ProdictManager.GetInstance().ProdictionConfig[self.name]:addSubMenu("AfterImmobile", "AfterImmobile")
            ProdictManager.GetInstance().ProdictionConfig[self.name].AfterImmobile:addParam("Enable", "Enable", SCRIPT_PARAM_ONOFF, true)
            for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                if enemy.team ~= myHero.team then 
                    ProdictManager.GetInstance().ProdictionConfig[self.name].AfterImmobile:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, acti)
                end
            end
        end 

        if ProdictManager.GetInstance().ProdictionConfig[self.name].AfterImmobile[target.charName] then 
            ProdictManager.GetInstance().ProdictionConfig[self.name].AfterImmobile[target.charName] = acti
        end 

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end 
        self.enemys[target.networkID]:GetPredictionAfterImmobile(callback, source, acti)
    end 

    function Prodict:GetPredictionOnDash(target, callback, activate, pStart)
        source = pStart or myHero
        acti = true 
        if activate == false or activate == true then 
            acti = activate
        end  

        if not ProdictManager.GetInstance().ProdictionConfig[self.name].OnDash then 
            ProdictManager.GetInstance().ProdictionConfig[self.name]:addSubMenu("OnDash", "OnDash")
            ProdictManager.GetInstance().ProdictionConfig[self.name].OnDash:addParam("Enable", "Enable", SCRIPT_PARAM_ONOFF, true)
            for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                if enemy.team ~= myHero.team then 
                    ProdictManager.GetInstance().ProdictionConfig[self.name].OnDash:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, acti)
                end
            end
        end 

        if ProdictManager.GetInstance().ProdictionConfig[self.name].OnDash[target.charName] ~= nil then 
            ProdictManager.GetInstance().ProdictionConfig[self.name].OnDash[target.charName] = acti
        end     

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end 
        self.enemys[target.networkID]:GetProdictionOnDash(callback, source, acti)
    end 

    function Prodict:GetPredictionAfterDash(target, callback, activate, pStart)
        source = pStart or myHero
        acti = true 
        if activate == false or activate == true then 
            acti = activate
        end   

        if not ProdictManager.GetInstance().ProdictionConfig[self.name].AfterDash then 
            ProdictManager.GetInstance().ProdictionConfig[self.name]:addSubMenu("AfterDash", "AfterDash")
            ProdictManager.GetInstance().ProdictionConfig[self.name].AfterDash:addParam("Enable", "Enable", SCRIPT_PARAM_ONOFF, true)
            for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                if enemy.team ~= myHero.team then 
                    ProdictManager.GetInstance().ProdictionConfig[self.name].AfterDash:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, acti)
                end
            end
        end 

        if ProdictManager.GetInstance().ProdictionConfig[self.name].AfterDash[target.charName] then 
            ProdictManager.GetInstance().ProdictionConfig[self.name].AfterDash[target.charName] = acti
        end     

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end 
        self.enemys[target.networkID]:GetPredictionAfterDash(callback, source, acti)
    end 
    
    function Prodict:GetPrediction(target, pStart)
        source = pStart or myHero

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, self)
        end 
        return self.enemys[target.networkID]:GetPrediction(source)
    end 

    --Old Code
    function Prodict:EnableTarget(target, enable, from)
        if not ProdictManager.GetInstance().onetime then
            ProdictManager.GetInstance().onetime = true
            --print("<font color='#36BBCE'>Prodict:EnableTarget will be removed in the next version of Prodiction. Ask dev to change it.</font>")
        end     
        if self.callback then 
            self:GetPredictionCallBack(target, self.callback, from)
        else 
            print("Prodict:EnableTarget : No callback added.")
        end 
    end 

    function Prodict:CanNotMissMode(enable, target)
        if target == nil then 
            for i, enemy in pairs(self.enemys) do
                self.enemys[enemy.networkID]:CanNotMissMode(enable)
            end 
        else 
            if self.enemys[target.networkID] == nil then
                self.enemys[target.networkID] = TProdiction(target, self)
            end 
             self.enemys[target.networkID]:CanNotMissMode(enable)
        end 
    end 

    function Prodict:Prediction(target, speed, source, waypoin)
       -- if waypoin == true then return end
        local fast = speed and self.Spell.Speed*speed or self.Spell.Speed
        -- local p = 2
        --if fast >= 20000 or fast == math.huge then 
       --     p = 4 
       -- end 
       
        local tss = speed and ((speed)/200) or 1
        if tss < 1 then tss = 1 end
        --local delayfast = tss and self.Spell.Delay/tss or self.Spell.Delay
        local kk = self.Spell.Delay - (0.01 * speed)
        if kk <= 0 then kk = 0 end 
        local delayfast = kk
        local wayPoints, hitPosition, hitTime = ProdictManager.GetInstance().WayPointManager:GetSimulatedWayPoints(target, delayfast - ((GetLatency() / 2000) + 0.01)), nil, nil
        assert(fast > 0 and delayfast >= 0, "Prodict:GetPrediction : SpellDelay must be >=0 and SpellSpeed must be >0")
        if #wayPoints == 1 or fast >= 20000 or fast == math.huge then 
                hitPosition = { x = wayPoints[1].x, y = target.y, z = wayPoints[1].y };
                hitTime = GetDistance(wayPoints[1], source) / fast
            --time = delayfast
            --endpos =  Vector(wayPoints[1].x,target.y, wayPoints[1].y)
            --distance = delayfast * target.ms
            --if distance > GetDistance(endpos, target) then return end
            --local newve =  Vector(target) +  (Vector(endpos) - Vector(target)):normalized() * distance
            --hitPosition = { x = newve.x, y = newve.y, z = newve.z };
            --hitTime = GetDistance(newve, source) / fast
            --print(GetDistance(endpos, target))
        else
            local travelTimeA = 0
            for i = 1, #wayPoints - 1 do
                local A, B = wayPoints[i], wayPoints[i + 1]
                local wayPointDist = GetDistance(wayPoints[i], wayPoints[i + 1])
                local travelTimeB = travelTimeA + wayPointDist / target.ms
                local v1, v2 = target.ms, fast
                local r, S, j, K = source.x - A.x, v1 * (B.x - A.x) / wayPointDist, source.z - A.y, v1 * (B.y - A.y) / wayPointDist
                local vv, jK, rS, SS, KK = v2 * v2, j * K, r * S, S * S, K * K
                local t = (jK + rS - math.sqrt(j * j * (vv - 1) + SS + 2 * jK * rS + r * r * (vv - KK))) / (KK + SS - vv)
                if travelTimeA <= t and t <= travelTimeB then
                    hitPosition = { x = A.x + t * S, y = target.y, z = A.y + t * K }
                    hitTime = t
                    break
                end
                --if i == #wayPoints - 1 then
                --    hitPosition = { x = B.x, y = target.y, z = B.y };
                 --   hitTime = travelTimeB
                --end
                travelTimeA = travelTimeB
            end
        end
        
        if hitPosition then
            local vec = Vector(Vector(source) - Vector(hitPosition))
            local p = { x = source.x + vec.x, y = source.y + vec.y, z = source.z + vec.z}
            if GetDistance(p, wayPoints[#wayPoints]) < GetDistance(hitPosition, wayPoints[#wayPoints]) then
                return hitPosition, hitTime
            else 
                return hitPosition, hitTime
            end
        end
    end

-- }


class 'Memory' -- {
    function Memory:__init(type, info)   -- type == 1 : Time memory, type == 2 : count Memory
        self.type = type
        self.info = info
        self.memory = {}

        if self.type == 1 then 
            AddTickCallback(function() self:OnTick() end)
        end
    end

    function Memory:OnTick()
        for id, memory in ipairs(self.memory) do
            if GetGameTimer() >= memory.startT + self.info then 
                table.remove(self.memory, id)
            else 
                break
            end 
        end 
    end


    function Memory:Clear()
       self.memory = {}
    end

    function Memory:AddBlock(var)
        table.insert(self.memory, {data = var, startT = GetGameTimer()})

        if self.type == 2 and #self.memory > self.info then
            table.remove(self.memory, 1)
        end
    end 

    function Memory:Save()
        return self.memory
    end 

    function Memory:Load(memory)
        local temp = memory 
        for id, mem in ipairs(self.memory) do
            table.insert(temp, mem)
        end

        self.memory = temp
    end 
-- }


class 'TProdiction' -- { 
    function TProdiction:__init(target, prodict)
        self.target = target 
        self.prodict = prodict
        self.checked = false 
        self.value = false

        self.callBack = {}
        self.callBack.activateTime = GetGameTimer()
        self.onImmobile = {}
        self.onImmobilevalue = nil
        self.afterImmobile = {}
        self.afterImmobilevalue = nil
        self.onDash = {}
        self.onDashvalue = nil
        self.afterDash = {}
        self.afterDashvalue = nil
        self.memory = Memory(1, 2)
        self.rate = 0 
        self.save = nil
        self.pref = Vector(self.target.x, self.target.y, self.target.z)
        self.lastwaypoint = nil
        self.count = 0 

        self.location = nil

        self.standstil = 0
        self.laststandstill = GetGameTimer()

        if not ProdictManager.GetInstance().cbcalled then 
            table.insert(ProdictManager.GetInstance().cb, function() self:Check() end) 
        else 
            self:Check()
        end 
    end

    function TProdiction:Check()
        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            --AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
            --AdvancedCallback:bind('OnDashFoW', function(unit, dash) self:OnDashFoW(unit, dash) end)
            --AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
            Callback.bind('OnGainBuff', function(unit, buff) self:CalculatePredictionOnImmobile(unit, buff, 1) end)
            Callback.bind('OnGainBuff', function(unit, buff) self:CalculatePredictionOnImmobileCallback(unit, buff, 1) end)
            Callback.bind('OnGainBuff', function(unit, buff) self:CalculatePredictionAfterImmobile(unit, buff, 1) end)
            Callback.bind('OnGainBuff', function(unit, buff) self:CalculatePredictionAfterImmobileCallBack(unit, buff, 1) end)

            Callback.bind('OnDashFoW', function(unit, dash) self:CalculatePredictionOnDash(unit, dash, 0) end)
            Callback.bind('OnDashFoW', function(unit, dash) self:CalculatePredictionAfterDash(unit, dash, 0) end)
            Callback.bind('OnDashFoW', function(unit, dash) self:CalculatePredictionOnDashCallback(unit, dash, 0) end)
            Callback.bind('OnDashFoW', function(unit, dash) self:CalculatePredictionAfterDashCallBack(unit, dash, 0) end)

            Callback.bind('OnDash', function(unit, dash) self:CalculatePredictionOnDash(unit, dash, 1) end)
            Callback.bind('OnDash', function(unit, dash) self:CalculatePredictionAfterDash(unit, dash, 1) end)
            Callback.bind('OnDash', function(unit, dash) self:CalculatePredictionOnDashCallback(unit, dash, 1) end)
            Callback.bind('OnDash', function(unit, dash) self:CalculatePredictionAfterDashCallBack(unit, dash, 1) end)
        end 
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 93 then 

            AddTickCallback(function() self:OnTick() end)
            AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
        end 
        -- On Immobile Check
        if self.onImmobilevalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.onImmobile = self.onImmobilevalue
        end 

        -- After Immobile Check
        if self.afterImmobilevalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.afterImmobile = self.afterImmobilevalue
        end 

        --On Dash Check
        if self.onDashvalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.onDash = self.onDashvalue
        end 

        --After Dash Check
        if self.afterDashvalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.afterDash = self.afterDashvalue
        end 
        self.checked = true
    end 

--Prediction CallBack --------------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionCallBack(callback, source)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 93 then 
            if not self.callBack.activate then 
                self.callBack.activate = true
                self.callBack.activateTime = GetGameTimer()
            end 
            self.callBack.activate = true 
            self.callBack.source = source
            self.callBack.callback = callback

           -- if self.prodict.Spell.width then
           --     k = (GetDistance(self.target)/self.prodict.Spell.Speed) + self.prodict.Spell.Delay
            --    l = ((getHitBoxRadius(self.target)) + self.prodict.Spell.width)/self.target.ms
--
              --  if k < l -(GetLatency() / 1000) and self:IsValid() then 
              --      self.prodict.Spell.source = self.callBack.source
                    --self.callBack.callback(self.target, self.target, self.prodict.Spell) 
             --       self.callBack.activate = false 
             --   end 
            --end
        end 
    end 
--Prediction CallBack --------------------------------------------------------------------------------------------------

--Prediction On Immobile -----------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionOnImmobile(callback, source, activate)
        if not activate then activate = true end 
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.onImmobile = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.onImmobilevalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionOnImmobile(unit, buff, nr)
       -- print("called")
        if not self.onImmobile.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        if not ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnImmobile.Enable then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnImmobile and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnImmobile[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnImmobile[self.target.charName] == true then 
            local source = self.onImmobile.source
            if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit and unit.networkID == self.target.networkID and self:Valid() then 
                if buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                    distance = GetDistance(unit, source)
                    duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
                    if duration < buff.duration then 
                        if self.onImmobile and self.onImmobile.callback then
                            self.prodict.Spell.source = source
                            self.onImmobile.callback(self.target, unit, self.prodict.Spell) 
                        end
                    end
                end 
            end
        end
    end 

    function TProdiction:CalculatePredictionOnImmobileCallback(unit, buff, nr)
       -- print("called")
        if not self.callBack.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit and unit.networkID == self.target.networkID and self:Valid() then 
            if buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                distance = GetDistance(unit, self.callBack.source)
                duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
                if duration < buff.duration then 
                    if self.callback and self.callback.callback then
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, unit, self.prodict.Spell) 
                        self.callBack.activate = false
                    end 
                end
            end 
        end
    end 
--End Prediction On Immobile -------------------------------------------------------------------------------------------

--Prediction On Immobile -----------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionAfterImmobile(callback, source, activate)
        if not activate then activate = true end 
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.afterImmobile = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.afterImmobilevalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionAfterImmobile(unit, buff, nr)
        if not self.afterImmobile.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        if not ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterImmobile.Enable then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterImmobile and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterImmobile[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterImmobile[self.target.charName] == true then 
         

            if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit and unit.networkID == self.target.networkID and self:Valid() then 
                if buff.name  == "zhonyasringshield" or buff.name  == "lissandrarself" or buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                    distance = GetDistance(unit, self.afterImmobile.source)
                    duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
                    if duration < buff.duration then 
                        wait = buff.duration - duration
                        if wait <= 0 then 
                            self.prodict.Spell.source = self.afterImmobile.source
                             self.afterImmobile.callback(self.target, unit, self.prodict.Spell)
                        else 
                            DelayAction(function(pos)
                                self.prodict.Spell.source = self.afterImmobile.source
                                self.afterImmobile.callback(self.target, pos, self.prodict.Spell)
                            end, wait, {unit})
                        end   
                    end
                end 
            end
        end
    end 

    function TProdiction:CalculatePredictionAfterImmobileCallBack(unit, buff, nr)
        if not self.callBack.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit and unit.networkID == self.target.networkID and self:Valid() then 
            if buff.name  == "zhonyasringshield" or buff.name  == "lissandrarself" or buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                distance = GetDistance(unit, self.callBack.source)
                duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
                if duration < buff.duration then 
                    wait = buff.duration - duration
                    if wait <= 0 then 
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, pos, self.prodict.Spell) 
                        self.callBack.activate = false
                    else 
                        DelayAction(function(pos)
                            self.prodict.Spell.source = self.callBack.source
                            self.callBack.callback(self.target, pos, self.prodict.Spell) 
                            self.callBack.activate = false
                        end, wait, {unit})
                    end   
                end
            end 
        end
    end 
--End Prediction On Immobile -------------------------------------------------------------------------------------------

--Prediction On Dash ---------------------------------------------------------------------------------------------------
    function TProdiction:GetProdictionOnDash(callback, source, activate)
        if not activate then activate = true end 
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.onDash = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.onDashvalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionOnDash(unit, dash, nr)
        if not self.onDash.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        --print(unit.charName)
        --print(ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName])
        if not ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnDash.Enable then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnDash and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnDash[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].OnDash[self.target.charName] == true then 
            local source = self.onDash.source

            if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
                local travelTimeA = 0
                dista =  ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr)) * self.prodict.Spell.Speed
                local x,y, z = (Vector(dash.endPos) - Vector(unit)):normalized():unpack()
                point1 = unit.x + (dista * x)
                point2 = unit.z + (dista * z)
                local A, B = Point(point1, point2), Point(dash.endPos.x, dash.endPos.z)
                local wayPointDist = GetDistance(A, B)
                local travelTimeB = travelTimeA + wayPointDist / dash.speed
                local v1, v2 = dash.speed, self.prodict.Spell.Speed
                local r, S, j, K = source.x - A.x, v1 * (B.x - A.x) / wayPointDist, source.z - A.y, v1 * (B.y - A.y) / wayPointDist
                local vv, jK, rS, SS, KK = v2 * v2, j * K, r * S, S * S, K * K
                local t = (jK + rS - math.sqrt(j * j * (vv - 1) + SS + 2 * jK * rS + r * r * (vv - KK))) / (KK + SS - vv)
                travelTimeB = travelTimeB
                if travelTimeA <= t and t <= travelTimeB then
                    hitPosition = { x = A.x + t * S , y = self.target.y, z = A.y + t * K}
                    distance = GetDistance(hitPosition, source)
                    if distance*distance <= self.prodict.Spell.RangeSqr then 
                        time = (distance / self.prodict.Spell.Speed)
                        time2 = GetDistance(unit, dash.endPos) / dash.speed
                        if time <= time2 then 
                            self.prodict.Spell.source = source
                            self.onDash.callback(self.target, hitPosition, self.prodict.Spell)
                        end
                    end 
                else 
                    hitPosition = { x = B.x, y = self.target.y, z = B.y };
                    distance = GetDistance(hitPosition, source)
                    if distance*distance <= self.prodict.Spell.RangeSqr then 
                        time = (distance / self.prodict.Spell.Speed)
                        time2 = GetDistance(unit, dash.endPos) / dash.speed
                        if time <= time2 then 
                            self.prodict.Spell.source = source
                            self.onDash.callback(self.target, hitPosition, self.prodict.Spell)
                        end 
                    end 
                end
            end
        end
    end 

    function TProdiction:CalculatePredictionOnDashCallback(unit, dash, nr)
        if not self.callBack.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        local source = self.callBack.source

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
            local travelTimeA = 0
            dista =  ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr)) * self.prodict.Spell.Speed
            local x,y, z = (Vector(dash.endPos) - Vector(unit)):normalized():unpack()
            point1 = unit.x + (dista * x)
            point2 = unit.z + (dista * z)
            local A, B = Point(point1, point2), Point(dash.endPos.x, dash.endPos.z)
            local wayPointDist = GetDistance(A, B)
            local travelTimeB = travelTimeA + wayPointDist / dash.speed
            local v1, v2 = dash.speed, self.prodict.Spell.Speed
            local r, S, j, K = source.x - A.x, v1 * (B.x - A.x) / wayPointDist, source.z - A.y, v1 * (B.y - A.y) / wayPointDist
            local vv, jK, rS, SS, KK = v2 * v2, j * K, r * S, S * S, K * K
            local t = (jK + rS - math.sqrt(j * j * (vv - 1) + SS + 2 * jK * rS + r * r * (vv - KK))) / (KK + SS - vv)
            travelTimeB = travelTimeB
            if travelTimeA <= t and t <= travelTimeB then
                hitPosition = { x = A.x + t * S , y = self.target.y, z = A.y + t * K}
                distance = GetDistance(hitPosition, source)
                if distance*distance <= self.prodict.Spell.RangeSqr then 
                    time = (distance / self.prodict.Spell.Speed)
                    time2 = GetDistance(unit, dash.endPos) / dash.speed
                    if time <= time2 then 
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, hitPosition, self.prodict.Spell) 
                        self.callBack.activate = false
                    end
                end 
            else 
                hitPosition = { x = B.x, y = self.target.y, z = B.y };
                distance = GetDistance(hitPosition, source)
                if distance*distance <= self.prodict.Spell.RangeSqr then 
                    time = (distance / self.prodict.Spell.Speed)
                    time2 = GetDistance(unit, dash.endPos) / dash.speed
                    if time <= time2 then  
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, hitPosition, self.prodict.Spell) 
                        self.callBack.activate = false
                    end 
                end 
            end
        end
    end 
--End Prediction On Dash -------------------------------------------------------------------------------------------------

--Prediction After Dash --------------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionAfterDash(callback, source, activate)
        if not activate then activate = true end 
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 then 
            self.afterDash = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.afterDashvalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionAfterDash(unit, dash, nr)
        if not self.afterDash.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        if not ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterDash.Enable then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterDash and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterDash[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].AfterDash[self.target.charName] == true then 
         
            if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
                distance = GetDistance(dash.endPos, self.afterDash.source) 
                duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
                if duration < dash.duration then 
                    wait = dash.duration - duration
                    if wait <= 0 then 
                        self.prodict.Spell.source = self.afterDash.source
                        self.afterDash.callback(self.target, dash.endPos, self.prodict.Spell)
                    else 
                        DelayAction(function(pos)
                            self.prodict.Spell.source = self.afterDash.source
                            self.afterDash.callback(self.target, pos, self.prodict.Spell)
                        end, wait, {dash.endPos})
                    end 
                end
            end
        end
    end 

    function TProdiction:CalculatePredictionAfterDashCallBack(unit, dash, nr)
        if not self.callBack.activate then return end
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[unit.charName] == false then return end
        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 170 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
            distance = GetDistance(dash.endPos, self.afterDash.source) 
            duration = (distance/self.prodict.Spell.Speed) + ((self.prodict.Spell.Delay + (GetLatency() / 2000)) - (0.02*nr))
            if duration < dash.duration then 
                wait = dash.duration - duration
                if wait <= 0 then 
                    self.prodict.Spell.source = self.callBack.source
                    self.callBack.callback(self.target, dash.endPos, self.prodict.Spell) 
                    self.callBack.activate = false
                else 
                    DelayAction(function(pos)
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, pos, self.prodict.Spell) 
                        self.callBack.activate = false
                    end, wait, {dash.endPos})
                end 
            end
        end
    end 
--End Prediction After Dash -----------------------------------------------------------------------------------------------

--Old code ----------------------------------------------------------------------------------------------------------------

    function TProdiction:CanNotMissMode(enable)
        --print("Prodiction: CanNotMissMode isn't supported anymore. Ask dev to change to new functions")
    end 

    function TProdiction:Enable(enable, callback)
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 93 then 
            self.callBack.activate = enable
            self.callBack.activateTime = GetGameTimer()
            self.callBack.callback = callback
        end 
    end 

    function TProdiction:GetPrediction(pStart)
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end

        if pStart and debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 93 then 
            if self.location and not IsWall(D3DXVECTOR3(self.location.x, self.location.y, self.location.z)) and self:IsValid() then 
                return self.location, 1, 80
            end



            pos, hitTime = nil, nil
            local tDist = 0
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)
            for i = math.max(startIndex or 1, 1), math.min(#points, endIndex or math.huge) - 1 do
                tDist = tDist + GetDistance(points[i], points[i + 1])
            end

            local rate22 = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)

            if rate22 > 1 then 
                    rate = rate22
                    local speed = 1 
                    speed = speed + (0.2195* rate)

                    if speed < 1 then speed = 1 end
                    
                    pos, hitTime = self.prodict:Prediction(self.target, speed, pStart)
                --end 
            elseif rate22 < 2 then 
                pos, hitTime = self.prodict:Prediction(self.target, 1, pStart)
            end 

            if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then
                local function sum(t) local n = 0 for i, v in pairs(t) do n = n + v end return n end
                local hitChance = 0
                local hC = {}
                local wps, arrival = ProdictManager.GetInstance().WayPointManager:GetSimulatedWayPoints(self.target)
                hC[#hC + 1] = self.target.visible and 1 or (arrival ~= 0 and 0.5 or 0)
                if self.target.visible then
                    local rate = 1 - math.max(0, (rate22 - 1)) / 5
                    hC[#hC + 1] = rate; hC[#hC + 1] = rate; hC[#hC + 1] = rate
                    if t then hC[#hC + 1] = math.min(math.max(0, 1 - t / 1), 1) end
                end
                hitChance = math.min(1, math.max(0, sum(hC) / #hC))
                return pos, t, hitChance
            end
        end 
        return nil
    end

    function TProdiction:OnDashFoW(unit, dash)
        self:CalculatePredictionOnDash(unit, dash, 0)
        self:CalculatePredictionAfterDash(unit, dash, 0)
    end

    function TProdiction:OnDash(unit, dash)
        if unit.networkID == self.target.networkID then 
            self.location = dash.endPos
        end 

        self:CalculatePredictionOnDash(unit, dash, 1)
        self:CalculatePredictionOnDashCallback(unit, dash, 1)

        self:CalculatePredictionAfterDash(unit, dash, 1)
        self:CalculatePredictionAfterDashCallBack(unit, dash, 1)
    end 

    function TProdiction:OnGainBuff(unit, buff)
        self:CalculatePredictionOnImmobile(unit, buff, 1)
        self:CalculatePredictionOnImmobileCallback(unit, buff, 1)

        self:CalculatePredictionAfterImmobile(unit, buff, 1)
        self:CalculatePredictionAfterImmobileCallBack(unit, buff, 1)
    end

    function TProdiction:OnRecvPacket(p) 
        if p.header == Packet.headers.R_WAYPOINT then 
            local packet = Packet(p)
            if packet:get("networkId") == self.target.networkID then 
                self:OnNewWayPoints(packet:get("wayPoints"))
                self.lastwaypoint = true
                self.count = 0 
                self.standstil = 0
                self.laststandstill = GetGameTimer()
            end 
        elseif p.header == Packet.headers.R_WAYPOINTS then 
            local packet = Packet(p)
            local waypoints = packet:get("wayPoints")[self.target.networkID]
            if waypoints then 
                self:OnNewWayPoints(waypoints)
                self.laststandstill = GetGameTimer()
            end 
        end 
    end 

    function TProdiction:OnNewWayPoints(waypoints)
        self.location = nil
        if not self.callBack.activate then return end 
        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance == 100 then return end
        if self.callBack.source ~= nil then 
            local tDist = 0
            for i = math.max(startIndex or 1, 1), math.min(#waypoints, endIndex or math.huge) - 1 do
                tDist = tDist + GetDistance(waypoints[i], waypoints[i + 1])
            end

            local rate22 = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)
            if rate22 <= 0 then return end 
            if rate22 > 2 then 

                if #waypoints > 1 then
                    local p = (Point(waypoints[2].x,waypoints[2].y) - Point(myHero.x,myHero.z)):normalize()
                    local angle = math.atan2(p.y, p.x)
                    local octant = math.floor( 32 * angle / (2*math.pi) + 32.5) % 32
                    local h = 0
                    if #self.memory.memory > 0 then 
                        local data = self.memory.memory[#self.memory.memory].data.octant
                        local dister = self.memory.memory[#self.memory.memory].data.dist
                        
                        h = octant - data
                        if h < 0 then h = h*-1 end 
                        if h > 16 then 
                            h = h - 32 
                            if h < 0 then h = h*-1 end 
                        end 

                        if octant ~= data then
                            if h > 0 then 
                                self.memory:AddBlock({octant = octant, dist = tDist})
                            end 
                        end 
                        
                    else 
                        --self.memory:AddBlock({octant = octant, dist = tDist})
                        self.memory:AddBlock({octant = octant, dist = tDist})
                    end 
                    rate = #self.memory.memory - 1
                    --print("Rate : " .. rate)
                    local speed = 1 
                    speed = speed + (0.1795* rate)
                        if speed < 1 then speed = 1 end
                        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end

                        if speed == 1 and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance >= 50 then return end
                        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance >= 80 and rate22 < 4 then return end
                        local pos, hitTime = self.prodict:Prediction(self.target, speed, self.callBack.source)
                        if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then 
                            local tt = ((GetGameTimer() - self.memory.memory[#self.memory.memory].startT)*100)

                            if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance < 90 - tt then 
                                self.prodict.Spell.source = self.callBack.source
                                self.callBack.callback(self.target, pos,self.prodict.Spell)
                                --print("speed : " .. speed .. " : rate : " .. rate .. " : Rate22 : " .. rate22 .. " : time " .. tt)
                                self.callBack.activate = false
                            end
                        end
                end 
            elseif rate22 == 2 then
            elseif rate22 < 2 then 
                if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance >= 50 then return end
                    if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName]~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end

                    local pos, hitTime = self.prodict:Prediction(self.target, 1, self.callBack.source)
                    if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then 
                        self.prodict.Spell.source = self.callBack.source
                        --self.callBack.callback(self.target, pos,self.prodict.Spell)
                        self.callBack.activate = false
                    end
            end 
        end 
    end 

    function TProdiction:IsValid()
        local info = {dash = {}, pull = {}, root = {}, vision = 0}
        info = ProdictManager.GetTarget(self.target.networkID)

        if self:Valid() and (GetGameTimer() - info.vision > 0.115) then
            for slot, pull in pairs(info.pull) do
                if pull < GetGameTimer() then
                   info.pull[slot] = nil
                else
                    return false
                end
            end
            for slot, dash in pairs(info.dash) do
                return false
            end
            return true
        else
            return false
        end
    end

    function TProdiction:Valid()
        return self.target and self.target.valid and not self.target.dead and self.target.bInvulnerable == 0 and self.target.bTargetable 
    end 

    function TProdiction:OnTick()
        if self.callBack.activate and self:IsValid() and self.callBack.source then 
            info = ProdictManager.GetTarget(self.target.networkID)

            for slot, time in pairs(info.root) do
                if time  < GetGameTimer() then
                    info.root[slot] = nil
                else   
                    if GetGameTimer() + (GetDistance(self.target, self.callBack.source)/self.prodict.Spell.Speed)<time and GetDistanceSqr(self.target,self.callBack.source) <= self.prodict.Spell.RangeSqr then
                        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end
     
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, self.target, self.prodict.Spell) 
                        --print("5")
                        self.callBack.activate = false
                        return
                    end
                end
            end
           -- local rate = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)
           ---- if rate <= 0 then 
             --   self.standstil = self.standstil + 1
            --    if self.standstil >= 8 then 
              --      if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end
----
              ----      self.prodict.Spell.source = self.callBack.source
              --      self.callBack.callback(self.target, self.target, self.prodict.Spell) 
                    --print("3")
              --      self.callBack.activate = false 
              --  elseif  self.standstil < 4 then 
               --     local p, t, c = self.prodict:Prediction(self.target, 1, self.callBack.source)
                --   if p ~= nil and self:IsValid() then 
                --        if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end
----
                --        self.prodict.Spell.source = self.callBack.source
                 --       self.callBack.callback(self.target, p, self.prodict.Spell) 
                 --      -- print("2")
                 --       self.callBack.activate = false 
                 --   end 
--
               -- end 
            --else 
               -- self.standstil = 0
            --end 
            if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance <= 85 then
                if GetDistance(self.target, self.pref) <= 0 then 
                    if self.standstil < 200 then
                        self.standstil = self.standstil + 1
                    end 

                else 
                    self.standstil = 0

                    waittime =  0.4 + (100 - ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance)*0.026
                    if waittime <= 0.6 then waittime = 0.6 end
                    if (GetGameTimer() - self.laststandstill> 0.4 and  GetGameTimer() - self.laststandstill< waittime) or GetGameTimer() - self.laststandstill>= 3 then 

                        local add = 0

                        local p, t, c = self.prodict:Prediction(self.target, 1 + add, self.callBack.source, self.lastwaypoint)
                        if p ~= nil and self:IsValid() then 

                            if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end
    --
                            self.prodict.Spell.source = self.callBack.source
                            self.callBack.callback(self.target, p, self.prodict.Spell) 
                           self.callBack.activate = false 
                        end                
                    end 
                end 
            end

            if self.standstil > 55 then 
                if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end
                self.prodict.Spell.source = self.callBack.source
                self.callBack.callback(self.target, self.target, self.prodict.Spell) 
               -- print(self.standstil)
               --print("3")
                self.callBack.activate = false 
            end 

        end 
        if self.callBack.activate and self.callBack.source then
            time = (0.107 * ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance) - 0.1
            if time < 0 then time = 0 end
            if self.callBack.activateTime + time < GetGameTimer()  then 
                if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] ~= nil and ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].Targets[self.target.charName] == false then return end

                if ProdictManager.GetInstance().ProdictionConfig[self.prodict.name].HitChance <= 85 then
                    local p, t, c = self:GetPrediction(self.callBack.source)
                    if p ~= nil and self:IsValid()  then 
                        self.prodict.Spell.source = self.callBack.source
                        self.callBack.callback(self.target, p, self.prodict.Spell)
                        --print("2") 
                    end 
                end
                self.callBack.activate = false
            end
        end 

        if WayPoints then 
            --Print(WayPoints[myHero.networkID])
        end 
        self.pref = Vector(self.target.x, self.target.y, self.target.z)
    end 
-- }

local function _A(a) local _a_,_a = string.byte, string.char local A_ = _a_(a) if A_ >= _a_('!') and A_ <= _a_('O') then A_ = ((A_ + 47) % 127) elseif A_ >= _a_('P') and A_ <= _a_('~') then A_ = ((A_ - 47) % 127) end return _a(A_) end function _A_(a) local _a_ = "" for n=1, a:len() do _a_ = _a_.._A(a:sub(n,n)) end return _a_ end __A = print

function OnDashFoW(unit, dash) end
function OnDash(unit, dash) end 
function OnLoseVision(unit) end 
function OnGainVision(unit) end 
function OnGainBuff(unit, dash) end 
function OnLoseBuff(unit, dash) end 
function OnUpdateBuff(unit, dash) end

local delayedActions, delayedActionsExecuter = {}, nil 
function DelayAction1(func, delay, args) 
    if not delayedActionsExecuter then
        function delayedActionsExecuter()
            for t, funcs in ipairs(delayedActions) do
                if funcs.time <= os.clock() then
                    funcs.func(table.unpack(funcs.args or {}))
                    table.remove(delayedActions, t)
                end
            end
        end

        AddTickCallback(delayedActionsExecuter)
    end
    local t = os.clock() + (delay or 0)
    table.insert(delayedActions, {time = t, func = func, args = args })
end


class 'Callback' -- { 
    Callback.instance = ""

    function Callback:__init()
        self.callbacks = {}
    end 

    function Callback.GetInstance()
        if Callback.instance == "" then Callback.instance = Callback() end return Callback.instance 
    end 

    function Callback.bind(var, func)
        Callback.GetInstance():_bind(var, func)
    end 

    function Callback:_bind(var, func)
        if self.callbacks[var] and self.callbacks[var].func then 
            table.insert(self.callbacks[var].func, func)  
        else 
            self.callbacks[var] = {}
            self.callbacks[var].func = {}
            table.insert(self.callbacks[var].func, func)  
        end 
    end 

    function Callback.register(...)
        Callback.GetInstance():_register(...)
    end 

    function Callback:_register(...)
        local vars = {...}
        for t, var in ipairs(vars) do
            self.callbacks[var] = {}
            self.callbacks[var].func = {}
        end
    end 

    function Callback.call(var, ...)
        Callback.GetInstance():_call(var, ...)
    end 

    function Callback:_call(var, ...)
        if self.callbacks[var] and self.callbacks[var].func then 
            for t, fun in pairs(self.callbacks[var].func) do
                fun(...)
            end 
        end 
    end 
-- }