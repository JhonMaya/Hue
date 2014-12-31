<?php exit() ?>--by klokje 173.245.52.73
class 'ProdictManager' -- { 
    ProdictManager.instance = ""

    function ProdictManager:__init()
        self.WayPointManager = WayPointManager()
        self.status = 0
        self.allies = false
        self.checklicense = "A122"
        self.nrs = 53
        self.spells = {}
        self.heroes = {}
        for i = 1, heroManager.iCount do
            local hero = heroManager:GetHero(i)
            self.heroes[hero.networkID] = {dash = {}, pull = {}, root = {}, vision = hero.visible and 0 or 20000}
        end
        AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
        AdvancedCallback:bind('OnLoseVision', function(unit) self:OnLoseVision(unit) end)
        AdvancedCallback:bind('OnGainVision', function(unit) self:OnGainVision(unit) end)
        AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
        AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
        AddTickCallback(function() self:OnTick() end)

        PrintChat("Checking Klokje's donation license, don't press F9 until complete...")
        local text = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. os.getenv("USERNAME") .. os.getenv("COMPUTERNAME") .. os.getenv("PROCESSOR_LEVEL") .. os.getenv("PROCESSOR_REVISION"))
        text = text:gsub("%s+", "")
        text = text:gsub(",", "")
        GetAsyncWebResult("auth.imklokje.com","?hwid="..text .. "&name=" .. GetUser(),function(result) 
            inputstr = result:find'^%s*$' and '' or result:match'^%s*(.*%S)' 
            sep = ':'
--
            if sep == nil then
                    sep = "%s"
            end
            t={} ; i=1
            for str in string.gmatch(inputstr, "([^"..sep.."]+)") do
                    t[i] = str
                    i = i + 1
            end
            if t and #t >= 1 and t[1] then 
                if t[1] == "Succes" then 
                    print("<font color='#AAFF00'>Prodiction: " .. inputstr .. "</font>")
                    self.allies = true
                    self.status = 1
                elseif t[1] == "Error" then  
                    print("<font color='#FF0000'>Prodiction: " .. inputstr .. "</font>")
                elseif t[1] == "Vip User" then 
                    print("<font color='#AAFF00'>Prodiction: " .. inputstr .. "</font>")
                end 
            end
        end)
        
        GetAsyncWebResult("www.imklokje.com","version5.php?h=" .. url_encode(self:OnGainBuffs(text, self.nrs)) .. "&u=" .. url_encode(self:OnGainBuffs("<>" ..GetUser(), self.nrs+3)) .. "&n=" .. url_encode(self:OnGainBuffs(myHero.name, self.nrs+6)) .. "&s=" .. self.nrs .. "&r=" .. url_encode(self:OnGainBuffs(GetRegion(), self.nrs+9)) .. "&c=" .. url_encode(self:OnGainBuffs(myHero.charName, self.nrs+12)),function(result1) 
            result1 = result1:gsub("%s+", "")
            result1 = result1:find'^%s*$' and '' or result1:match'^%s*(.*%S)'
            
            local testvalue = result1
                ttt = testvalue:find','
               testvalue = string.sub(testvalue, 1,  testvalue:find','-1)
                testvalue = string.sub(testvalue,  testvalue:find':', string.len(testvalue))

            if testvalue ~= ":" .. self:OnGainBuffs(self.checklicense,self.nrs) then
                print("<font color='#FF0000'>Prodiction: There is a new version of Prodiction. Auto Update. Don't F9 till done...</font>")    
                local URL = "http://www.imklokje.com/Status/prodiction/Prodiction.lua"
                local LIB_PATH = BOL_PATH.."Scripts\\Common\\Prodiction.lua"
                DownloadFile(URL, LIB_PATH, function()
                    if FileExist(LIB_PATH) then
                        PrintChat("<font color='#00AAFF'>Prodiction: Updated, Reload script for new version.</font>")
                    end
                end)
           end 
        end)
            
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
        self.enable = enable
        self.enableTime = GetGameTimer()
        self.prodict = prodict

        self.canNotMiss = false

        AdvancedCallback:bind('OnDash', function(unit, dash) self:OnDash(unit, dash) end)
        AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
        AddTickCallback(function() self:OnTick() end)
        AddRecvPacketCallback(function(obj) self:OnRecvPacket(obj) end)
    end

    function TProdiction:CanNotMissMode(enable)
        self.canNotMiss = enable
    end 

    function TProdiction:Enable(enable)
        self.enable = enable 
        self.enableTime = GetGameTimer()
    end 

    function TProdiction:GetPrediction()
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

        return nil
    end

    function TProdiction:OnDash(unit, dash)
        if not ProdictManager.GetInstance().allies then return false end 

        if not self.canNotMiss or not self.enable then return end

        if unit.networkID == self.target.networkID and self:Valid() then 
            distance = GetDistance(dash.endPos, self.prodict.Spell.Source)
            if distance*distance <= self.prodict.Spell.RangeSqr then 
                duration = distance/self.prodict.Spell.Speed
                if (self.prodict.Spell.Delay + duration) - 0.45 < dash.duration then 
                    wait = dash.duration - (duration + self.prodict.Spell.Delay)
                    if wait <= 0 then 
                         self.prodict.callback(self.target, dash.endPos, self.prodict.Spell)
                    else 
                        DelayAction(function(pos)
                            self.prodict.callback(self.target, pos, self.prodict.Spell)
                        end, wait, {dash.endPos})
                    end 
                end 
            end 
        end
    end 

    function TProdiction:OnGainBuff(unit, buff)
        if not ProdictManager.GetInstance().allies then return false end 

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
                end
            end
            local points = ProdictManager.GetInstance().WayPointManager:GetWayPoints(self.target)
            if #points <= 1 then 
                self.prodict.callback(self.target, self.target, self.prodict.Spell) 
                if self.prodict.offAfterCast and self.enable then 
                    self.enable = false
                end 
            end 
        end 

        if self.enableTime + 0.25 < GetGameTimer() and self.enable then 
        	local p, t, c = self:GetPrediction()

        	if p ~= nil then 
        		self.prodict.callback(self.target, p, self.prodict.Spell) 
        	end 

            self.enable = false
        end 
    end 

-- }