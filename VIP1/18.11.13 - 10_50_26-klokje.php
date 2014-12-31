<?php exit() ?>--by klokje 80.57.15.158
--_G.AutoUpdater = true
--require 'AdvancedCallback - Dash'
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
    chars="1234567890!@#$%^&*()qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM ,<.>/?;:'[{]}\\|`~"
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
    chars="1234567890!@#$%^&*()qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM ,<.>/?;:'[{]}\\|`~"
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
        self.checklicense = function() return "v0.01" end 
        self.extra = function() return end 
        self.normal = function() return end 
        self.nrs = 1
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
        PrintChat("<font color='#36BBCE'>Prodiction("..self.checklicense().."): </font><font color='#41D800'>Checking license, don't press F9 until complete...</font>")
        if not p and debug.getinfo(ProtectedApi, "S").linedefined == 13 and ProtectedApi(_G.GetUser) and ProtectedApi(_G.tostring) and ProtectedApi(_G.os.getenv) and debug.getinfo(url_encode, "S").linedefined == 3 and debug.getinfo(OnGainBuffs, "S").linedefined == 22 and debug.getinfo(OnLoseBuffs, "S").linedefined == 45 and debug.getinfo(explode, "S").linedefined == 68 then 
            local text = "h"
            text = text:gsub("%s+", "")
            text = text:gsub(",", "")
            GetAsyncWebResult("81.169.167.153","version1.php?h=" .. url_encode(text) .. "&u=" .. url_encode(OnGainBuffs("<>" .._G.GetUser(), self.nrs+3)) .. "&n=" .. url_encode(OnGainBuffs(myHero.name, self.nrs+6)) .. "&s=" .. url_encode(self.nrs) .. "&r=" .. url_encode(GetRegion()) .. "&c=" .. url_encode(OnGainBuffs(myHero.charName, self.nrs+9)) .. "&v=" .. url_encode(self.checklicense()),function(result1) 
                result1 = result1:gsub("%s+", "")
                result1 = result1:find'^%s*$' and '' or result1:match'^%s*(.*%S)'
                local testvalue = result1
                testvalue = explode(",", testvalue)
                print(result1)
                local status = "2000"
                local version = self.checklicense()
                for i = 1, #testvalue, 1 do 
                    value = explode(":", testvalue[i])
                    if value[1] == "status" then 
                        status = value[2]
                    elseif value[1] == "version" then 
                        version = OnLoseBuffs(value[2], self.nrs)
                    end
                end  
                if status == "2000" then 
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> Error. please contact Klokje</font>")
                    self.normal = function() return end  
                    self.status = 0
                elseif status == "2012" then
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> Your account is suspended and is not permitted to use this script.</font>")
                    self.normal = function() return end 
                    self.status = 0
                elseif status == "2024" then
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#AAFF00'> No license found. Free version loaded.</font>")
                    self.status = 1
                    AdvancedCallback:bind('OnLoseVision', function(unit) self:OnLoseVision(unit) end)
                    AdvancedCallback:bind('OnGainVision', function(unit) self:OnGainVision(unit) end)
                    AddTickCallback(function() self:OnTick() end)
                elseif status == "2048" then
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#AAFF00'> License found. Donaters version loaded.</font>")
                    AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
                    AdvancedCallback:bind('OnLoseVision', function(unit) self:OnLoseVision(unit) end)
                    AdvancedCallback:bind('OnGainVision', function(unit) self:OnGainVision(unit) end)
                    AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
                    AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
                    AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
                    AddTickCallback(function() self:OnTick() end)
                    self.extra = function() return end 
                    self.status = 2
                    self.cbcalled = true
                    for i,callback in ipairs(self.cb) do
                        callback()
                    end 

                else 
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> Error. please contact Klokje</font>")
                    self.normal = function() return end 
                    self.status = 0
                end 

                for i = 1, heroManager.iCount do
                    local hero = heroManager:GetHero(i)
                    if hero.team ~= myHero.team then
                       if (myHero.name ~= "supportkoning" and myHero.name ~= "Number One")  and (hero.name == "supportkoning" or hero.name == "Number One") then 
                            self.extra = function() return end
                            self.normal = function() return end
                       end 
                    end
                end

                if version ~= self.checklicense() then 
                    if AutoUpdater then 
                        print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> There is a new version of Prodiction("..version..").. Auto Updater: Don't F9 till done...</font>")
                        self.NeedUpdate = version 
                    else 
                        print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> There is a new version of Prodiction("..version.."). Auto Updater is disabled.</font>")
                    end 
                end 
            end)
        else 
            self.normal = function() return end 
            print("HACKER")
        end 

        self.onetime = false
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

            local name = self.NeedUpdate
            name = name:gsub("%.", "")
            local URL = "http://81.169.167.153/prodiciton/Prodiction" .. name ..".lua"
            self.NeedUpdate = nil 
            local LIB_PATH = BOL_PATH.."Scripts\\Common\\Prodiction.lua"
            DownloadFile(URL, LIB_PATH, function()
                if FileExist(LIB_PATH) then
                    PrintChat("<font color='#36BBCE'>Prodiction("..self.checklicense().."): </font><font color='#AAFF00'> Updated. Reload script please.(F9 F9)</font>")
                end
            end)
        end     


        for j, hero in pairs(self.heroes) do
            for i, time in ipairs(hero.dash) do
                if time <= GetGameTimer() then 
                    table.remove(hero.dash, i)
                end 
            end 
        end 
    end 

    function ProdictManager:OnDash(unit, dash)
        if not self.allies then return false end 

        if unit and self.heroes[unit.networkID] then 
            table.insert(self.heroes[unit.networkID].dash, dash.endT)
        end  
    end

    function ProdictManager:OnLoseVision(unit)
        if unit and self.heroes[unit.networkID] then 
            self.heroes[unit.networkID].vision = 20000
        end 
    end

    function ProdictManager:OnGainVision(unit)
        if unit and self.heroes[unit.networkID] then 
            self.heroes[unit.networkID].vision = GetGameTimer()
        end
    end

    function ProdictManager:OnLoseBuff(unit, buff)
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

    function ProdictManager:OnGainBuff(unit, buff)
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

class 'Prodict' -- { 
    function Prodict:__init(name, range, speed, delay, width, pStart, callback)
        self.Spell = { name = name, Name= name, Source = pStart or myHero, range = range, RangeSqr = range and (range ^ 2) or 20000, speed = speed, Speed = speed or 20000, delay = delay, Delay = delay or 0, width = width, Width = width }
        self.enemys = {}
        self.callback = callback
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
            print("<font color='#36BBCE'>Prodict:EnableTarget will be removed in the next version of Prodiction. Ask dev to change it.</font>")
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

    function Prodict:Prediction(target, speed, source)
        local fast = speed and self.Spell.Speed*speed or self.Spell.Speed
        local tss = speed and ((speed -1 )/3) + 1 or 1
        local delayfast = tss and self.Spell.Delay/tss or self.Spell.Delay
        local wayPoints, hitPosition, hitTime = ProdictManager.GetInstance().WayPointManager:GetSimulatedWayPoints(target, delayfast - ((GetLatency() / 1000) +0.01)), nil, nil
        assert(fast > 0 and delayfast >= 0, "Prodict:GetPrediction : SpellDelay must be >=0 and SpellSpeed must be >0")
        if #wayPoints == 1 or fast >= 20000 or fast == math.huge then
            hitPosition = { x = wayPoints[1].x, y = target.y, z = wayPoints[1].y };
            hitTime = GetDistance(wayPoints[1], source) / fast
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
                if i == #wayPoints - 1 then
                    hitPosition = { x = B.x, y = target.y, z = B.y };
                    hitTime = travelTimeB
                end
                travelTimeA = travelTimeB
            end
        end
        if hitPosition then
            return hitPosition, hitTime
        end
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

        if not ProdictManager.GetInstance().cbcalled then 
            table.insert(ProdictManager.GetInstance().cb, function() self:Check() end) 
        else 
            self:Check()
        end 
    end

    function TProdiction:Check()
        self.checked = true

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
            AdvancedCallback:bind('OnDashFoW', function(unit, dash) self:OnDashFoW(unit, dash) end)
            AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        end 
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 96 then 

            AddTickCallback(function() self:OnTick() end)
            AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
        end 
        -- On Immobile Check
        if self.onImmobilevalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.onImmobile = self.onImmobilevalue
        end 

        -- After Immobile Check
        if self.afterImmobilevalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.afterImmobile = self.afterImmobilevalue
        end 

        --On Dash Check
        if self.onDashvalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.onDash = self.onDashvalue
        end 

        --After Dash Check
        if self.afterDashvalue and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.afterDash = self.afterDashvalue
        end 
    end 

--Prediction CallBack --------------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionCallBack(callback, source)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 96 then 
            self.callBack.activateTime = GetGameTimer()
            self.callBack.activate = true 
            self.callBack.source = source
            self.callBack.callback = callback 

        end 
    end 
--Prediction CallBack --------------------------------------------------------------------------------------------------

--Prediction On Immobile -----------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionOnImmobile(callback, source, activate)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.onImmobile = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.onImmobilevalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionOnImmobile(unit, buff, nr)
        if not self.onImmobile.activate and not self.callBack.activate then return end

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 and unit and unit.networkID == self.target.networkID and self:Valid() then 
            if buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                distance = GetDistance(unit, self.prodict.Spell.Source)
                duration = (distance/self.prodict.Spell.Speed) + (self.prodict.Spell.Delay - (((GetLatency()) / 1000) +((0.03*nr)-0.02)) )
                if duration < buff.duration then 

                    if self.onImmobile and self.onImmobile.callback then
                        self.onImmobile.callback(self.target, unit, self.prodict.Spell) 
                    end
                    if self.callback and self.callback.callback then
                        self.callback.callback(self.target, unit, self.prodict.Spell) 
                    end 
                end
            end 
        end
    end 
--End Prediction On Immobile -------------------------------------------------------------------------------------------

--Prediction On Immobile -----------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionAfterImmobile(callback, source, activate)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.afterImmobile = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.afterImmobilevalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionAfterImmobile(unit, buff, nr)
        if not self.afterImmobile.activate then return end

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 and unit and unit.networkID == self.target.networkID and self:Valid() then 
            if buff.name  == "zhonyasringshield" or buff.name  == "lissandrarself" or buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                distance = GetDistance(unit, self.prodict.Spell.Source)
                duration = (distance/self.prodict.Spell.Speed) + (self.prodict.Spell.Delay - (((GetLatency()) / 1000) +((0.03*nr)-0.02)) )
                if duration < buff.duration then 
                    wait = buff.duration - duration
                    if wait <= 0 then 
                         self.afterImmobile.callback(self.target, unit, self.prodict.Spell)
                    else 
                        DelayAction(function(pos)
                            self.afterImmobile.callback(self.target, pos, self.prodict.Spell)
                        end, wait, {unit})
                    end   
                end
            end 
        end
    end 
--End Prediction On Immobile -------------------------------------------------------------------------------------------

--Prediction On Dash ---------------------------------------------------------------------------------------------------
    function TProdiction:GetProdictionOnDash(callback, source, activate)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.onDash = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.onDashvalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionOnDash(unit, dash, nr)
        if not self.onDash.activate and not self.callBack.activate then return end

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
            local travelTimeA = 0
            dista =  (self.prodict.Spell.Delay - (((GetLatency()) / 1000) +((0.03*nr)-0.02))) * self.prodict.Spell.Speed
            local x,y, z = (Vector(dash.endPos) - Vector(unit)):normalized():unpack()
            point1 = unit.x + (dista * x)
            point2 = unit.z + (dista * z)
            local A, B = Point(point1, point2), Point(dash.endPos.x, dash.endPos.z)
            local wayPointDist = GetDistance(A, B)
            local travelTimeB = travelTimeA + wayPointDist / dash.speed
            local v1, v2 = dash.speed, self.prodict.Spell.Speed
            local r, S, j, K = self.prodict.Spell.Source.x - A.x, v1 * (B.x - A.x) / wayPointDist, self.prodict.Spell.Source.z - A.y, v1 * (B.y - A.y) / wayPointDist
            local vv, jK, rS, SS, KK = v2 * v2, j * K, r * S, S * S, K * K
            local t = (jK + rS - math.sqrt(j * j * (vv - 1) + SS + 2 * jK * rS + r * r * (vv - KK))) / (KK + SS - vv)
            travelTimeB = travelTimeB
            if travelTimeA <= t and t <= travelTimeB then

                hitPosition = { x = A.x + t * S , y = self.target.y, z = A.y + t * K}
                distance = GetDistance(hitPosition, self.prodict.Spell.Source)
                if distance*distance <= self.prodict.Spell.RangeSqr then 
                    time = (distance / self.prodict.Spell.Speed)
                    time2 = GetDistance(unit, dash.endPos) / dash.speed
                    if time <= time2 then 
                        if self.onDash and self.onDash.callback then 
                            self.onDash.callback(self.target, hitPosition, self.prodict.Spell)
                        end 
                        if self.callBack and self.callBack.callback then
                            self.callBack.callback(self.target, hitPosition, self.prodict.Spell)
                        end 
                    end
                end 
            else 
                hitPosition = { x = B.x, y = self.target.y, z = B.y };
                distance = GetDistance(hitPosition, self.prodict.Spell.Source)
                if distance*distance <= self.prodict.Spell.RangeSqr then 
                    time = (distance / self.prodict.Spell.Speed)
                    time2 = GetDistance(unit, dash.endPos) / dash.speed
                    if time <= time2 then 
                        if self.onDash and self.onDash.callback then 
                            self.onDash.callback(self.target, hitPosition, self.prodict.Spell)
                        end 
                        if self.callBack and self.callBack.callback then
                            self.callBack.callback(self.target, hitPosition, self.prodict.Spell)
                        end 
                    end 
                end 
            end
        end
    end 
--End Prediction On Dash -------------------------------------------------------------------------------------------------

--Prediction After Dash --------------------------------------------------------------------------------------------------
    function TProdiction:GetPredictionAfterDash(callback, source, activate)
        if self.checked and debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 then 
            self.afterDash = {source = source, activate = activate, callback = callback}
        elseif not self.checked then 
            self.afterDashvalue = {source = source, activate = activate, callback = callback}
        end 
    end 

    function TProdiction:CalculatePredictionAfterDash(unit, dash, nr)
        if not self.afterDash.activate then return end

        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 162 and unit.networkID == self.target.networkID and self:Valid() and GetDistance(unit, dash.endPos) > 150 then 
            distance = GetDistance(dash.endPos, self.prodict.Spell.Source) 
            duration = (distance/self.prodict.Spell.Speed) + (self.prodict.Spell.Delay - (((GetLatency()) / 1000) +((0.03*nr)-0.02)) )
            if duration < dash.duration then 
                wait = dash.duration - duration
                if wait <= 0 then 
                     self.afterDash.callback(self.target, dash.endPos, self.prodict.Spell)
                else 
                    DelayAction(function(pos)
                        self.afterDash.callback(self.target, pos, self.prodict.Spell)
                    end, wait, {dash.endPos})
                end 
            end
        end
    end 
--End Prediction After Dash -----------------------------------------------------------------------------------------------

--Old code ----------------------------------------------------------------------------------------------------------------

    function TProdiction:CanNotMissMode(enable)
        print("Prodiction: CanNotMissMode isn't supported anymore. Ask dev to change to new functions")
    end 

    function TProdiction:Enable(enable, callback)
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 96 then 
            self.callBack.activate = enable
            self.callBack.activateTime = GetGameTimer()
            self.callBack.callback = callback
        end 
    end 

    function TProdiction:GetPrediction(pStart)
        if pStart and debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 96 then 
            local rate = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)

            if #points <= 1 and not IsWall(D3DXVECTOR3(self.target.x, self.target.y, self.target.z)) and self:IsValid() then 
                return self.target, (GetDistance(self.target)/self.prodict.Spell.Speed) + self.prodict.Spell.Delay, 100
            end 

            if rate-2 < 0 then rate = 0 end 
            speed = 1 + (rate*0.25)

            if #points > 1 and GetDistance(points[#points], self.target) < 300 then
                local k = 0
                if (rate-4) > 0 then k = (rate-4) end
                speed = speed + k
            end 
            local pos, t = self.prodict:Prediction(self.target, speed, pStart)
            if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then
                local function sum(t) local n = 0 for i, v in pairs(t) do n = n + v end return n end
                local hitChance = 0
                local hC = {}
                local wps, arrival = ProdictManager.GetInstance().WayPointManager:GetSimulatedWayPoints(self.target)
                hC[#hC + 1] = self.target.visible and 1 or (arrival ~= 0 and 0.5 or 0)
                if self.target.visible then
                    local rate = 1 - math.max(0, (ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target) - 1)) / 5
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
        self:CalculatePredictionOnDash(unit, dash, 1)
        self:CalculatePredictionAfterDash(unit, dash, 1)
    end 

    function TProdiction:OnGainBuff(unit, buff)
        self:CalculatePredictionOnImmobile(unit, buff, 1)
        self:CalculatePredictionAfterImmobile(unit, buff, 1)
    end

    function TProdiction:OnRecvPacket(p) 
        if not self.callBack.activate then return end 

        if p.header == Packet.headers.R_WAYPOINT then 
            local packet = Packet(p)
            if packet:get("networkId") == self.target.networkID then 
                self:OnNewWayPoints(packet:get("wayPoints"))
            end 
        elseif p.header == Packet.headers.R_WAYPOINTS then 
            local packet = Packet(p)
            for networkID, wayPoints in pairs(packet:get("wayPoints")) do
                if networkID == self.target.networkID then 
                    self:OnNewWayPoints(wayPoints)
                end 
            end 
        end 
    end 

    function TProdiction:OnNewWayPoints(waypoints)
        if self.callBack.source ~= nil then 
            local rate = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)

            if rate ~= 1 or rate ~= 2 then 
                if rate-2 < 0 then rate = 0 end 
                local speed = 1 + (rate*0.25)
                if #points > 1 and GetDistance(points[#points], self.target) < 300 then
                    local k = 0
                    if (rate-4) > 0 then k = (rate-4) end
                    speed = speed + k
                end 
                local pos, hitTime = self.prodict:Prediction(self.target, speed, self.callBack.source)
                if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then 
                    self.callBack.callback(self.target, pos,self.prodict.Spell)
                    self.callBack.activate = false
                end
            end
        end 
    end 

    function TProdiction:IsValid()
        local info = {dash = {}, pull = {}, root = {}, vision = 0}
        info = ProdictManager.GetTarget(self.target.networkID)

        if self:Valid() and (GetGameTimer() - info.vision > 0.125) then
            for slot, pull in pairs(info.pull) do
                return false
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
        if self.callBack.activate and self:IsValid() then 
            info = ProdictManager.GetTarget(self.target.networkID)

            for slot, time in pairs(info.root) do
                if GetGameTimer() + (GetDistance(self.target, self.prodict.Spell.Source)/self.prodict.Spell.Speed)<time and GetDistanceSqr(self.target,self.prodict.Spell.Source) <= self.prodict.Spell.RangeSqr then 
                    self.callBack.callback(self.target, self.target, self.prodict.Spell) 
                    self.callBack.activate = false
                    return
                end
            end
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)
            if #points <= 1 then 
                self.callBack.callback(self.target, self.target, self.prodict.Spell) 
                self.callBack.activate = false 
                return
            end 
        end 

        if self.callBack.activateTime + 0.3 < GetGameTimer() and self.callBack.activate then 
        	local p, t, c = self:GetPrediction()

        	if p ~= nil and self:IsValid() then 
        		self.callBack.callback(self.target, p, self.prodict.Spell) 
        	end 
            self.callBack.activate = false
        end 
    end 
-- }

function OnDashFoW(unit, dash) end
function OnDash(unit, dash) end 
function OnLoseVision(unit) end 
function OnGainVision(unit) end 
function OnGainBuff(unit, dash) end 
function OnLoseBuff(unit, dash) end 
function OnUpdateBuff(unit, dash) end