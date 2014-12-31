<?php exit() ?>--by klokje 80.57.15.158
--_G.GetUser = function() return "menno" end
_G.AutoUpdater = false


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
        self.spells = {}
        self.heroes = {}
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
        --print( url_encode(self:OnGainBuffs("<>" .. "klokje", 1+3)))
        if not p and debug.getinfo(ProtectedApi, "S").linedefined == 223 and ProtectedApi(_G.GetUser) and ProtectedApi(_G.tostring) and ProtectedApi(_G.os.getenv) and debug.getinfo(url_encode, "S").linedefined == 124 then 
            local text = _G.tostring(_G.os.getenv("PROCESSOR_IDENTIFIER").. _G.os.getenv("PROCESSOR_LEVEL") .. _G.os.getenv("PROCESSOR_REVISION") .. _G.os.getenv("USERNAME") .. _G.os.getenv("COMPUTERNAME"))
            text = text:gsub("%s+", "")
            text = text:gsub(",", "")
            GetAsyncWebResult("81.169.167.153","version.php?h=" .. url_encode(text) .. "&u=" .. url_encode(self:OnGainBuffs("<>" .._G.GetUser(), self.nrs+3)) .. "&n=" .. url_encode(self:OnGainBuffs(myHero.name, self.nrs+6)) .. "&s=" .. url_encode(self.nrs) .. "&r=" .. url_encode(GetRegion()) .. "&c=" .. url_encode(self:OnGainBuffs(myHero.charName, self.nrs+9)) .. "&v=" .. url_encode(self.checklicense()),function(result1) 
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
                        version = self:OnLoseBuffs(value[2], self.nrs)
                    end
                end  
                   -- print(testvalue[1])
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
                    AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
                    AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
                    AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
                    AddTickCallback(function() self:OnTick() end)
                    self.extra = function() return end 
                    self.status = 2
                else 
                    print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> Error. please contact Klokje</font>")
                    self.normal = function() return end 
                    self.status = 0
                end 
                --print(debug.getinfo(self.extra, "S").linedefined)

                if version ~= self.checklicense() then 
                    if AutoUpdater then 
                        print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> There is a new version of Prodiction("..version..").. Auto Updater: Don't F9 till done...</font>")
                    else 
                        print("<font color='#36BBCE'>Prodiction("..self.checklicense().."):</font><font color='#EF002A'> There is a new version of Prodiction("..version.."). Auto Updater is disabled.</font>")
                    end 
                end 
            end)
        else 
            self.checklicense = function() return "v0.02" end 
            print("HACKER")
        end 
    end  

    function explode(delimiter, text)
      local list = {}; local pos = 1
      if string.find("", delimiter, 1) then
        -- We'll look at error handling later!
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

    function url_encode(str)
      if (str) then
        str = string.gsub (str, "\n", "\r\n")
        str = string.gsub (str, "([^%w %-%_%.%~])",
            function (c) return string.format ("%%%02X", string.byte(c)) end)
        str = string.gsub (str, " ", "+")
      end
      return str    
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
        for j, hero in pairs(self.heroes) do
            for i, time in ipairs(hero.dash) do
                if time <= GetGameTimer() then 
                    table.remove(hero.dash, i)
                end 
            end 
        end 
    end 

    function ProdictManager:OnGainBuffs(unit,buff)
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

    function ProdictManager:OnLoseBuffs(unit, buff)
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

    function ProtectedApi(funct)
        if type(funct) == "function" then 
            if debug.getinfo(funct, "S").what == "C" then return true 
            else return false end 
        else 
            return false 
        end 
    end

    --function ProdictManager:OnRecvPacket(p)
      --  if p.header == Packet.headers.PKT_S2C_LoseVision then
      --      local unit = objManager:GetObjectByNetworkId(Packet(p):get('networkId'))
--
       --     if unit and unit.valid then
        --        if AdvancedCallback:OnLoseVision(unit) == false then
       --             p:Block()
       --         end
       --     end
       -- end 
   -- end 

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

    function ProdictManager:AddProdictionObject(name, range, proj_speed, delay, width, pStart, callback, offAfterCast)
        local p = Prodict(name, range, proj_speed, delay, width, pStart, callback, offAfterCast)
        table.insert(self.spells,p)
        return p
    end 
-- }

class 'Prodict' -- { 
    function Prodict:__init(name, range, proj_speed, delay, width, pStart, callback, offAfterCast)
        self.Spell = { Name= name, Source = fromPos or myHero, RangeSqr = range and (range ^ 2) or 20000, Speed = proj_speed or 20000, Delay = delay or 0, Width = width }
        self.enemys = {}
        self.enable = true
        self.offAfterCast = offAfterCast or true
        self.callback = callback
    end

    function Prodict:EnableTarget(target, enable, from)
        enable = enable or true
        self.Spell.Source = from or myHero

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, enable, self)
        else 
            self.enemys[target.networkID]:Enable(enable)
        end 
    end 

    function Prodict:CanNotMissMode(enable, target)
        if target == nil then 
            for i, enemy in pairs(self.enemys) do
                self.enemys[enemy.networkID]:CanNotMissMode(enable)
            end 
        else 
            if self.enemys[target.networkID] == nil then
                self.enemys[target.networkID] = TProdiction(target, false, self)
            end 
             self.enemys[target.networkID]:CanNotMissMode(enable)
        end 
    end 

    function Prodict:GetPrediction(target, from)
        self.Spell.Source = from or self.Spell.Source

        if self.enemys[target.networkID] == nil then
            self.enemys[target.networkID] = TProdiction(target, false, self)
        end 
        return self.enemys[target.networkID]:GetPrediction()
    end 

    function Prodict:Prediction(target, speed)
        local fast = speed and self.Spell.Speed*speed or self.Spell.Speed
        local tss = speed and ((speed -1 )/3) + 1 or 1
        local delayfast = tss and self.Spell.Delay/tss or self.Spell.Delay
        local wayPoints, hitPosition, hitTime = ProdictManager.GetInstance().WayPointManager:GetSimulatedWayPoints(target, delayfast + ((GetLatency() / 2) / 1000)), nil, nil
        assert(fast > 0 and delayfast >= 0, "TargetPredictionVIP:GetPrediction : SpellDelay must be >=0 and SpellSpeed must be >0")
        if #wayPoints == 1 or fast >= 20000 or fast == math.huge then
            hitPosition = { x = wayPoints[1].x, y = target.y, z = wayPoints[1].y };
            hitTime = GetDistance(wayPoints[1], self.Spell.Source) / fast
        else
            local travelTimeA = 0
            for i = 1, #wayPoints - 1 do
                local A, B = wayPoints[i], wayPoints[i + 1]
                local wayPointDist = GetDistance(wayPoints[i], wayPoints[i + 1])
                local travelTimeB = travelTimeA + wayPointDist / target.ms
                local v1, v2 = target.ms, fast
                local r, S, j, K = self.Spell.Source.x - A.x, v1 * (B.x - A.x) / wayPointDist, self.Spell.Source.z - A.y, v1 * (B.y - A.y) / wayPointDist
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
        if hitPosition and self.Spell.RangeSqr >= GetDistanceSqr(hitPosition, self.Spell.Source) then
            return hitPosition, hitTime
        end
    end

-- }

class 'TProdiction' -- { 
    function TProdiction:__init(target, enable, prodict)
        self.target = target 
        self.enable = enable--false --aanpassen
        self.enableTime = GetGameTimer()
        self.prodict = prodict

        self.canNotMiss = false

        --print(debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined)
        --if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 82 then 
            print("test")
            AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
            AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
            print("added")
        --end 
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 14 then 
            AddTickCallback(function() self:OnTick() end)
            AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
        end 
    end

    function TProdiction:CanNotMissMode(enable)
        if debug.getinfo(ProdictManager.GetInstance().extra, "S").linedefined == 82 then 
            self.canNotMiss = enable--false --aangepast
        end 
    end 

    function TProdiction:Enable(enable)
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 14 then 
            self.enable = enable 
            self.enableTime = GetGameTimer()
        end 
        --local pprediction = TargetPredictionVIP(math.sqrt(self.prodict.Spell.RangeSqr), self.prodict.Spell.Speed, self.prodict.Spell.Delay, self.prodict.Spell.Width, self.prodict.Spell.Source)
       -- local pos1, t1, vec1 = pprediction:GetPrediction(self.target)
        --if pos1 then 
        --    self.prodict.callback(self.target, pos1, self.prodict.Spell)
        --end 
    end 

    function TProdiction:GetPrediction()
        if debug.getinfo(ProdictManager.GetInstance().normal, "S").linedefined == 14 then 
       -- local pprediction = TargetPredictionVIP(math.sqrt(self.prodict.Spell.RangeSqr), self.prodict.Spell.Speed, self.prodict.Spell.Delay, self.prodict.Spell.Width, self.prodict.Spell.Source)
       -- local pos1, t1, vec1 = pprediction:GetPrediction(self.target)
       -- if pos1 then 
      --      return pos1, t1, vec1
      --  end 
   -- end
        --self.prodict.callback(self.target, pos1, self.prodict.Spell)
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
            local pos, t = self.prodict:Prediction(self.target, speed)
            if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then
                local function sum(t) local n = 0 for i, v in pairs(t) do n = n + v end return n end
    --
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

    function TProdiction:OnDash(unit, dash)
        --print(dash.startPos)
        --if not self.canNotMiss or not self.enable then return end
        if unit.networkID == self.target.networkID and self:Valid() then 
            --distance = GetDistance(dash.endPos, self.prodict.Spell.Source)
            --if distance*distance <= self.prodict.Spell.RangeSqr then 
            --    duration = distance/self.prodict.Spell.Speed
            --    if (self.prodict.Spell.Delay + duration) - 0.45 < dash.duration then 
            --        wait = dash.duration - (duration + self.prodict.Spell.Delay)
            --        if wait <= 0 then 
            --             self.prodict.callback(self.target, dash.endPos, self.prodict.Spell)
            --        else 
             --           DelayAction(function(pos)
            --                self.prodict.callback(self.target, pos, self.prodict.Spell)
             --           end, wait, {dash.endPos})
            --        end 
            --    end 
            --end 

           -- local x,y, z = (Vector(dash.endPos) - Vector(dash.startPos)):normalized():unpack()
            --sx = x * dash.speed
           -- sy = z * dash.speed
           -- local dx = dash.startPos.x - self.prodict.Spell.Source.x
           -- local dy = dash.startPos.z - self.prodict.Spell.Source.z
           -- local S = self.prodict.Spell.Speed
           -- local a = (sx*sx) + (sy*sy) - (S*S)
           -- local b = 2*((dx*sx) + (dy*sy))
           -- local c = (dx *dx) + (dy*dy)
          --  local delta = math.sqrt((b*b)-(4*a*c))

           -- t1 = (-b + delta)/(2*a)
           -- t2 = (-b - delta)/(2*a)
           -- local p = 0
           -- if t1 >= 0 and t2 >= 0 then 
          --      p = t1 < t2 and t1 or t2
           -- elseif t1 >= 0 then 
          --      p = t1
          --  elseif t2 >= 0 then 
          --      p = t2
          --  end 

           -- self.prodict.callback(self.target, Vector(dash.startPos.x + (sx * p), self.target.y, dash.startPos.y + (sy * p)), self.prodict.Spell)
            --self.prodict:Prediction(target, speed, ms)
            local travelTimeA = 0

            dista =  (self.prodict.Spell.Delay - ((GetLatency()) / 1000)) * self.prodict.Spell.Speed
            local x,y, z = (Vector(dash.endPos) - Vector(dash.startPos)):normalized():unpack()
            point1 = dash.startPos.x + (dista * x)
            point2 = dash.startPos.z + (dista * z)

            local A, B = Point(point1, point2), Point(dash.endPos.x, dash.endPos.z)
            local wayPointDist = GetDistance(A, B)
            local travelTimeB = travelTimeA + wayPointDist / dash.speed
            local v1, v2 = dash.speed, self.prodict.Spell.Speed
            local r, S, j, K = self.prodict.Spell.Source.x - A.x, v1 * (B.x - A.x) / wayPointDist, self.prodict.Spell.Source.z - A.y, v1 * (B.y - A.y) / wayPointDist
            local vv, jK, rS, SS, KK = v2 * v2, j * K, r * S, S * S, K * K
            local t = (jK + rS - math.sqrt(j * j * (vv - 1) + SS + 2 * jK * rS + r * r * (vv - KK))) / (KK + SS - vv)
                
            --print(travelTimeA)
            --print(travelTimeB)
            --print(t)

            if travelTimeA <= t and t <= travelTimeB then
                hitPosition = { x = A.x + t * S , y = self.target.y, z = A.y + t * K}
                --hitTime = t
                self.prodict.callback(self.target, hitPosition, self.prodict.Spell)
            else 
                hitPosition = { x = B.x, y = self.target.y, z = B.y };
                hitTime = travelTimeB
                self.prodict.callback(self.target, hitPosition, self.prodict.Spell)
            end
        end
    end 

    function TProdiction:OnGainBuff(unit, buff)
        if not self.canNotMiss or not self.enable then return end

        if unit and unit.networkID == self.target.networkID and self:Valid() then 
            if buff.name  == "zhonyasringshield" or buff.name  == "lissandrarself" or buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS then
                distance = GetDistance(unit, self.prodict.Spell.Source)
                if distance * distance <= self.prodict.Spell.RangeSqr then
                    duration = distance/self.prodict.Spell.Speed
                    if (self.prodict.Spell.Delay + duration) - 0.45 < buff.duration then 
                        wait = buff.duration - (duration + self.prodict.Spell.Delay)
                        if wait <= 0 then 
                             self.prodict.callback(self.target, unit, self.prodict.Spell)
                             if self.prodict.offAfterCast and self.enable then 
                                self.enable = false
                             end 
                        else 
                            DelayAction(function(pos)
                                self.prodict.callback(self.target, pos, self.prodict.Spell)
                                if self.prodict.offAfterCast and self.enable then 
                                    self.enable = false
                                end 
                            end, wait, {unit})
                        end    
                    end
                end
            end 
        end
    end

    function TProdiction:OnRecvPacket(p) 
        if not self.enable then return end 

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
        if self.prodict.Spell.Source ~= nil then 

            local rate = ProdictManager.GetInstance().WayPointManager:GetWayPointChangeRate(self.target, 1)
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)

            if rate ~= 1 or rate ~= 2 then 
                if rate-2 < 0 then rate = 0 end 
                local speed = 1 + (rate*0.25)
                    --speed = 1
                if #points > 1 and GetDistance(points[#points], self.target) < 300 then
                    local k = 0
                    if (rate-4) > 0 then k = (rate-4) end
                    speed = speed + k
                end 
                local pos, hitTime = self.prodict:Prediction(self.target, speed)
                if pos ~= nil and not IsWall(D3DXVECTOR3(pos.x, self.target.y, pos.z)) and self:IsValid() then 
                    self.prodict.callback(self.target, pos,self.prodict.Spell)
                    if self.prodict.offAfterCast and self.enable then 
                        self.enable = false
                    end 
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
        return self.target and self.target.valid and self.target.visible and not self.target.dead and self.target.bInvulnerable == 0 and self.target.bTargetable 
    end 

     function TProdiction:OnTick()
        if self.enable and self:IsValid() then 
            info = ProdictManager.GetTarget(self.target.networkID)

            for slot, time in pairs(info.root) do
                if GetGameTimer() + (GetDistance(self.target, self.prodict.Spell.Source)/self.prodict.Spell.Speed)<time and GetDistanceSqr(self.target,self.prodict.Spell.Source) <= self.prodict.Spell.RangeSqr then 
                    self.prodict.callback(self.target, self.target, self.prodict.Spell) 
                    if self.prodict.offAfterCast then 
                        self.enable = false
                    end 
                    return
                end
            end
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)
            if #points <= 1 then 
                self.prodict.callback(self.target, self.target, self.prodict.Spell) 
                if self.prodict.offAfterCast and self.enable then 
                    self.enable = false
                end 
                return
            end 
        end 

        if self.enableTime + 0.2 < GetGameTimer() and self.enable then 
        	local p, t, c = self:GetPrediction()

        	if p ~= nil and self:IsValid() then 
        		self.prodict.callback(self.target, p, self.prodict.Spell) 
        	end 
            self.enable = false
        end 
    end 

-- }