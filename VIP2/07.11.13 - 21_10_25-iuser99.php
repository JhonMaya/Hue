<?php exit() ?>--by iuser99 108.162.246.215
local SpellStrings = {
    [_Q] = "Q", 
    [_W] = "W",
    [_E] = "E",
    [_R] = "R"
}

local SpellString = {
    ["Q"] = _Q, 
    ["W"] = _W,
    ["E"] = _E, 
    ["R"] = _R 
}

-- look up for `k' in list of tables `plist'
function search (k, plist)
    for i=1, #plist do
      local v = plist[i][k]     -- try `i'-th superclass
      if v then return v end
    end
end

function createClass (...)
    local c = {}        -- new class
    local arg = {...}

    -- class will search for each method in the list of its
    -- parents (`arg' is the list of parents)
    setmetatable(c, {__index = function (t, k)
      return search(k, arg)
    end})

    -- prepare `c' to be the metatable of its instances
    c.__index = c

    -- define a new constructor for this new class
    function c:new (o)
        o = o or {}
        setmetatable(o, c)
        return o
    end

    -- return new class
    return c
end

function round(num)
    under = math.floor(num)
    upper = math.floor(num) + 1
    underV = -(under - num)
    upperV = upper - num
    if (upperV > underV) then
        return under
    else
        return upper
    end
end 

class 'Menu' -- {

    Menu.instance = ""

    function Menu.Instance() 
        if Menu.instance == "" then Menu.instance = Menu() end return Menu.instance 
    end 

    local SPELL_MENUS = {
        [_Q] = function() return self.Config.Q end,
        [_W] = function() return self.Config.W end,
        [_E] = function() return self.Config.E end,
        [_R] = function() return self.Config.R end
    }
    
    function Menu:__init(name, param)
        self.Config = scriptConfig("" .. name, "" .. param)
        self.Config:addSubMenu("Drawing", "Drawing") 
    end 

    function Menu:_Get(sub)
        return sub and self.Config[sub] or self.Config 
    end 

    function Menu.Get(sub)
        return Menu.Instance():_Get(sub)
    end 

-- }

--[[
    Class: Caster
    Author: Apple
    Constructors:
        - spell, range, spellType, [speed, delay, width, useCollisionLib]
    
    Methods: 
        - Cast(Target, [minHitChance])
            - Standard cast method, casts to target with prediction if needed 
        - CastMouse(spellPos, [nearestTarget])
            - Casts spell to mouse position, very useful for dashes and occasional free pokes
        - CastMec(Target, [minTargets])
            - Casts spell from rMEC, prioritizing target location 
--]]
class 'Caster' -- {

    local pManager = ProdictManager.GetInstance() 

    SPELL_TARGETED = 1
    SPELL_LINEAR = 2
    SPELL_CIRCLE = 3
    SPELL_CONE = 4
    SPELL_LINEAR_COL = 5
    SPELL_SELF = 6
    SPELL_TARGETED_FRIENDLY = 7 


    function Caster:__init(spell, range, spellType, speed, delay, width, useCollisionLib)
        --assert(spell and (range or spellType == SPELL_SELF), "Error: Caster:__init(spell, range, spellType, [speed, delay, width, useCollisionLib]), invalid arguments.")
        self.spell = spell
        self.range = range or 0
        self.spellType = spellType or SPELL_SELF
        self.speed = speed or math.huge
        self.delay = delay or 0
        self.width = width or 100
        self.spellData = myHero:GetSpellData(spell)
        if range ~= math.huge and range > 0 then
            Drawing.AddSkill(self.spell, self.range)
        end 
        if spellType == SPELL_LINEAR or spellType == SPELL_CIRCLE or spellType == SPELL_LINEAR_COL then
            --if type(range) == "number" and (not speed or type(speed) == "number") and (not delay type(delay) == "number" and (type(width) == "number" or not width) then
                --assert(type(range) == "number" and type(speed) == "number" and type(delay) == "number" and (type(width) == "number" or not width), "Error: Caster:__init(spell, range, [spellType, speed, delay, width, useCollisionLib]), invalid arguments for skillshot-type.")
                self.pred = pManager:AddProdictionObject(self.spell, self.range, self.speed, self.delay, self.width, myHero, 
                    function(unit, pos, castspell)
                        if GetDistance(unit) < self.range then 
                            if spellType == SPELL_LINEAR_COL then 
                                local collition = Collision(self.range, self.speed, self.delay, self.width + 10) 
                                if not collition:GetMinionCollision(pos, myHero) then
                                    CastSpell(castspell.Name, pos.x, pos.z) 
                                end 
                            else 
                                CastSpell(castspell.Name, pos.x, pos.z)
                            end 
                        end 
                    end)

                if spellType == SPELL_LINEAR_COL then
                    self.coll = VIP_USER and useCollisionLib ~= false and Collision(range, (speed or math.huge), delay, width) or nil
                end
            --end
        end
        --self.str = SpellStrings[self.spell]
        --Menu.Get(self.str):addParam("spellds1","-- Prodiction Options --", SCRIPT_PARAM_INFO, "")
        --Menu.Get(self.str):addParam("musthit" .. myHero.charName, "Use Must-Hit Mode", SCRIPT_PARAM_ONOFF, false)
    end

    function Caster:__type()
        return "Caster"
    end

    function Caster:Cast(target, minHitChance)
        if myHero:CanUseSpell(self.spell) ~= READY then return false end
        if self.spellType == SPELL_SELF then
            CastSpell(self.spell)
            return true
        elseif self.spellType == SPELL_TARGETED then
            if ValidTarget(target, self.range) then
                CastSpell(self.spell, target)
                return true
            end
        elseif self.spellType == SPELL_TARGETED_FRIENDLY then
            if target ~= nil and not target.dead and GetDistance(target) < self.range and target.team == myHero.team then
                CastSpell(self.spell, target)
                return true
            end
        elseif self.spellType == SPELL_CONE then
            if ValidTarget(target, self.range) then
                CastSpell(self.spell, target.x, target.z)
                return true
            end
        elseif self.spellType == SPELL_LINEAR or self.spellType == SPELL_CIRCLE or self.spellType == SPELL_LINEAR_COL then
            if self.pred and ValidTarget(target) then
                self.pred:EnableTarget(target, true)
                return true
            elseif target.team == myHero.team then
                CastSpell(self.spell, target.x, target.z)
                return true
            end 
        end
        return false
    end

    function Caster:CastMouse(spellPos, nearestTarget)
        --assert(spellPos and spellPos.x and spellPos.z, "Error: iCaster:CastMouse(spellPos, nearestTarget), invalid spellPos.")
        --assert(self.spellType ~= SPELL_TARGETED or (nearestTarget == nil or type(nearestTarget) == "boolean"), "Error: iCaster:CastMouse(spellPos, nearestTarget), <boolean> or nil expected for nearestTarget.")
        if myHero:CanUseSpell(self.spell) ~= READY then return false end
        if self.spellType == SPELL_SELF then
            CastSpell(self.spell)
            return true
        elseif self.spellType == SPELL_TARGETED then
            if nearestTarget ~= false then
                local targetEnemy
                for _, enemy in ipairs(GetEnemyHeroes()) do
                    if ValidTarget(targetEnemy, self.range) and (targetEnemy == nil or GetDistanceFromMouse(enemy) < GetDistanceFromMouse(targetEnemy)) then
                        targetEnemy = enemy
                    end
                end
                if targetEnemy then
                    CastSpell(self.spell, targetEnemy)
                    return true
                end
            end
        elseif self.spellType == SPELL_LINEAR_COL or self.spellType == SPELL_LINEAR or self.spellType == SPELL_CIRCLE or self.spellType == SPELL_CONE then
            CastSpell(self.spell, spellPos.x, spellPos.z)
            return true
        end
    end

    function Caster:CastMec(Target, minTargets)
        if not self:Ready() then return false end
        local min = minTargets or 1
        local point = GetAoESpellPosition(self.width, Target) 
        if GetDistance(point) <= self.range and Monitor.CountEnemies(point, self.width) >= min then
            CastSpell(self.spell, point.x, point.z)
            return true 
        end 
        return false 
    end     

    function Caster:Ready()
        return myHero:CanUseSpell(self.spell) == READY
    end

    function Caster:GetPrediction(target)
        if self.pred and ValidTarget(target) then return self.pred:GetPrediction(target) end
    end

    function Caster:GetCollision(spellPos)
        if spellPos and spellPos.x and spellPos.z then
            if self.coll then
                return self.coll:GetMinionCollision(myHero, spellPos)
            end
        end
    end
-- }

class "Drawing" -- {

    Drawing.instance = ""

    function Drawing.Instance()
        if Drawing.instance == "" then Drawing.instance = Drawing() end return Drawing.instance
    end 

    function Drawing:__init()
        self.queue = {}
        Menu.Get("Drawing"):addParam("drawPlayers", "Draw Players", SCRIPT_PARAM_ONOFF, false)
        Menu.Get("Drawing"):addParam("drawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
        Menu.Get("Drawing"):addParam("drawLines", "Draw Lines to Players", SCRIPT_PARAM_ONOFF, false)
        Menu.Get("Drawing"):addParam("playerDistance", "Max distance to draw players",SCRIPT_PARAM_SLICE, 1600, 0, 3000, 0)
        AddDrawCallback(function(obj) self:OnDraw() end)
    end 

    function Drawing.AddSkill(spell, range)
        return Drawing.Instance():_AddSkill(spell, range)
    end  

    function Drawing:_AddSkill(sspell, rrange)
        local tempSpell = SpellStrings[sspell]
        if self.queue["Skill" .. tempSpell] ~= nil then return end 
        Menu.Get("Drawing"):addParam("draw" .. tempSpell, "Draw " .. tempSpell .. " range", SCRIPT_PARAM_ONOFF, false)
        Menu.Get("Drawing"):addParam("colorReady" .. tempSpell, "Ready " .. tempSpell .. " Color", SCRIPT_PARAM_COLOR, {40, 71,149,38})
        Menu.Get("Drawing"):addParam("colorNotReady" .. tempSpell, "Not Ready " .. tempSpell .. " Color", SCRIPT_PARAM_COLOR, {40, 177, 0, 0})
        self.queue["Skill" .. tempSpell] = {spell = sspell, range = rrange, spellString = tempSpell, aTimer = 0}
    end 

    function Drawing:OnDraw(target)
        for name,d in pairs(self.queue) do 
            if string.find(name, "Skill") and Menu.Get("Drawing")["draw" .. d.spellString] then
                local tempColor = _ColorARGB.FromTable(Menu.Get("Drawing")["colorNotReady" .. d.spellString])
                if myHero:CanUseSpell(d.spell) == READY then 
                    tempColor = _ColorARGB.FromTable(Menu.Get("Drawing")["colorReady" .. d.spellString])
                end
                DrawCircle(myHero.x, myHero.y, myHero.z, d.range, tempColor)
            end 
        end 

        if Menu.Get("Drawing").drawPlayers then 
            for i = 1, heroManager.iCount, 1 do 
                local target = heroManager:getHero(i)
                if ValidTarget(target) and target.dead ~= true and target ~= myHero and target.team == TEAM_ENEMY and GetDistance(target) <= Menu.Get("Drawing").playerDistance then 
                    self:DrawTarget(target)
                end 
            end 
        end 

        if Menu.Get("Drawing").drawTarget then 
            if ValidTarget(target) and target.dead ~= true and target ~= myHero and target.team == TEAM_ENEMY and GetDistance(target) <= Menu.Get("Drawing").playerDistance then 
                self:DrawTarget(target)
            end 
        end 
    end 

    function Drawing:DrawTarget(Target) 
        if myHero.dead or not myHero.valid then return false end 
        local totalDamage = DamageCalculation.CalculateBurstDamage(Target)
        local realDamage = DamageCalculation.CalculateRealDamage(Target) 
        local dps = myHero:CalcDamage(Target, myHero.damage) * myHero.attackSpeed
        local ttk = (Target.health - realDamage) / dps 
        local tempColor = _ColorARGB.Red 
        local tempText = "Not Ready"
        if Target.health <= realDamage then
            tempColor = _ColorARGB.Green
            tempText = "KILL HIM"
        elseif Target.health > realDamage and Target.health <= totalDamage then 
            tempColor = _ColorARGB.Yellow 
            tempText = "Wait for cooldowns"
        end  
        for w = 0, 15 do 
            DrawCircle(Target.x, Target.y, Target.z, 40 + w * 1.5, tempColor:ToARGB())
        end 
        PrintFloatText(Target, 0, tempText .. " DMG: " .. round(realDamage) .. " (" .. string.format("%4.1f", ttk) .. "s)")
        if GetDistance(Target) <= Menu.Get("Drawing").playerDistance and Menu.Get("Drawing").drawLines then 
            DrawArrows(myHero, Target, 30, 0x099B2299, 50)
        end 
    end 
-- }

class "DamageCalculation" -- {
    local items = { -- Item Aliases for spellDmg lib, including their corresponding itemID's.
        { name = "DFG", id = 3128},
        { name = "HXG", id = 3146},
        { name = "BWC", id = 3144},
        { name = "HYDRA", id = 3074},
        { name = "SHEEN", id = 3057},
        { name = "KITAES", id = 3186},
        { name = "TIAMAT", id = 3077},
        { name = "NTOOTH", id = 3115},
        { name = "SUNFIRE", id = 3068},
        { name = "WITSEND", id = 3091},
        { name = "TRINITY", id = 3078},
        { name = "STATIKK", id = 3087},
        { name = "ICEBORN", id = 3025},
        { name = "MURAMANA", id = 3042},
        { name = "LICHBANE", id = 3100},
        { name = "LIANDRYS", id = 3151},
        { name = "BLACKFIRE", id = 3188},
        { name = "HURRICANE", id = 3085},
        { name = "RUINEDKING", id= 3153},
        { name = "LIGHTBRINGER", id = 3185},
        { name = "SPIRITLIZARD", id = 3209},
        --["ENTROPY"] = 3184,
    }

    DamageCalculation.instance = ""

    function DamageCalculation.Instance()
        if DamageCalculation.instance == "" then DamageCalculation.instance = DamageCalculation() end return DamageCalculation.instance
    end  

    function DamageCalculation:__init()
        self.addItems = true
        self.spells = {}
        self.spellTable = {"Q", "W", "E", "R"}
        for _, spellName in pairs(self.spellTable) do
            self.spells[spellName] = {name = spellName, spell = SpellString[spellName]}
        end 

        self.ignite = nil
        if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then 
            self.ignite = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
            self.ignite = SUMMONER_2
        else 
            self.ignite = nil
        end
    end

    function DamageCalculation.GetDamage(spell, Target)
        return DamageCalculation.Instance():_GetDamage(spell, Target) 
    end 

    function DamageCalculation:_GetDamage(spell, Target, player)
        self.player = player or myHero 
        return getDmg(spell, Target, self.player)
    end 

    function DamageCalculation.CalculateItemDamage(Target)
        return DamageCalculation.Instance():_CalculateItemDamage(Target) 
    end 

    function DamageCalculation:_CalculateItemDamage(Target, player)
        self.player = player or myHero 
        self.itemTotalDamage = 0
        for _, item in pairs(items) do 
            -- On hit items
            self.itemTotalDamage = self.itemTotalDamage + (GetInventoryHaveItem(item.id, self.player) and getDmg(item.name, Target, self.player) or 0)
        end
    end

    function DamageCalculation.CalculateRealDamage(Target) 
        return DamageCalculation.CalculateRealDamage(Target, myHero)
    end 

    function DamageCalculation.CalculateRealDamage(Target, player) 
        return DamageCalculation.Instance():_CalculateRealDamage(Target, player) 
    end 

    function DamageCalculation:_CalculateRealDamage(Target, player)
        self.player = player or myHero 
        local total = 0
        for _, spell in pairs(self.spells) do 
            if self.player:CanUseSpell(spell.spell) == READY and self.player:GetSpellData(spell.spell).mana <= self.player.mana then
                total = total + self:_GetDamage(spell.name, Target, self.player)
            end 
        end
        if self.addItems then
            self:_CalculateItemDamage(Target, self.player) 
            total = total + self.itemTotalDamage 
        end
        if self.ignite ~= nil and myHero:CanUseSpell(self.ignite) == READY then 
            total = total + self:_GetDamage("IGNITE", Target)
        end 
        total = total + self:_GetDamage("AD", Target, self.player)
        total = total + self:_GetDamage("P", Target, self.player)
        return total 
    end

    function DamageCalculation.CalculateBurstDamage(Target)
        return DamageCalculation.Instance():_CalculateBurstDamage(Target) 
    end 

    function DamageCalculation:_CalculateBurstDamage(Target)
        local localSpells = self.spells
        local total = 0 
        for _, spell in pairs(localSpells) do
            if myHero:CanUseSpell(spell.spell) == READY then
                total = total + self:_GetDamage(spell.name, Target)
            end
        end 
        if self.addItems then
            self:_CalculateItemDamage(Target) 
            total = total + self.itemTotalDamage 
        end
        if self.ignite ~= nil then 
            total = total + self:_GetDamage("IGNITE", Target)
        end 
        total = total + self:_GetDamage("AD", Target)
        total = total + self:_GetDamage("P", Target, self.player)
        return total 
    end 
-- }

class 'Script' -- {

    function Script:CreateTS(name, dmg)
        self.ts = TargetSelector(TARGET_PRIORITY, 2000, dmg, true)
        self.ts.name = name 
    end 

    function Script:Register(name, dmg, func)
    	self:CreateTS(name, dmg) 
    	OrbWalking.Instance(name)
    	self.lh = LastHitting(func)
    end 

    function Script:GetLastHit()
    	return self.lh 
    end 

    function Script:GetTS()
        return self.ts
    end 

    function Script:GetTarget()
        return self.ts.target 
    end 

    function Script:GetMinions()
        return self.enemyMinions.objects
    end 
    
    function Script:OnTick()
        if self.ts then 
            self.ts:update() 
        end 
        self.enemyMinions:update()
    end 

    function Script:OnDraw()
    end 

    function Script:OnLoad()
        Drawing.Instance() 
        self.enemyMinions = minionManager(MINION_ENEMY, 2000, player, MINION_SORT_HEALTH_ASC)
    end 
-- }

class '_ColorARGB' -- {

    function _ColorARGB:__init(red, green, blue, alpha)
        self.R = red or 255
        self.G = green or 255
        self.B = blue or 255
        self.A = alpha or 255
    end

    function _ColorARGB.FromArgb(red, green, blue, alpha)
        return Color(red,green,blue, alpha)
    end

    function _ColorARGB.FromTable(table) 
        return ARGB(table[1], table[2], table[3], table[4])
    end 

    function _ColorARGB:ToARGB()
        return ARGB(self.A, self.R, self.G, self.B)
    end

    _ColorARGB.Red = _ColorARGB(255, 0, 0, 255)
    _ColorARGB.Yellow = _ColorARGB(255, 255, 0, 255)
    _ColorARGB.Green = _ColorARGB(0, 255, 0, 255)
    _ColorARGB.Aqua = _ColorARGB(0, 255, 255, 255)
    _ColorARGB.Blue = _ColorARGB(0, 0, 255, 255)
    _ColorARGB.Fuchsia = _ColorARGB(255, 0, 255, 255)
    _ColorARGB.Black = _ColorARGB(0, 0, 0, 255)
    _ColorARGB.White = _ColorARGB(255, 255, 255, 255)
-- }

class '_Message' -- {

    _Message.instance = ""

    function _Message:__init()
        self.notifys = {} 

        AddDrawCallback(function(obj) self:OnDraw() end)
    end

    function _Message.Instance()
        if _Message.instance == "" then _Message.instance = _Message() end return _Message.instance 
    end

    function _Message.AddMessage(text, color, target)
        return _Message.Instance():PAddMessage(text, color, target)
    end

    function _Message:PAddMessage(text, color, target)
        local x = 0
        local y = 200 
        local tempName = "Screen" 
        local tempcolor = color or _ColorARGB.Red

        if target then  
            tempName = target.networkID
        end

        self.notifys[tempName] = { text = text, color = tempcolor, duration = GetGameTimer() + 2, object = target}
    end

    function _Message:OnDraw()
        for i, notify in pairs(self.notifys) do
            if notify.duration < GetGameTimer() then notify = nil 
            else
                notify.color.A = math.floor((255/2)*(notify.duration - GetGameTimer()))

                if i == "Screen" then  
                    local x = 0
                    local y = 200
                    local gameSettings = GetGameSettings()
                    if gameSettings and gameSettings.General then 
                        if gameSettings.General.Width then x = gameSettings.General.Width/2 end 
                        if gameSettings.General.Height then y = gameSettings.General.Height/4 - 100 end
                    end  
                    --PrintChat(tostring(notify.color))
                    local p = GetTextArea(notify.text, 40).x 
                    self:DrawTextWithBorder(notify.text, 40, x - p/2, y, notify.color:ToARGB(), ARGB(notify.color.A, 0, 0, 0))
                else    
                    local pos = WorldToScreen(D3DXVECTOR3(notify.object.x, notify.object.y, notify.object.z))
                    local x = pos.x
                    local y = pos.y - 25
                    local p = GetTextArea(notify.text, 40).x 

                     self:DrawTextWithBorder(notify.text, 30, x- p/2, y, notify.color:ToARGB(), ARGB(notify.color.A, 0, 0, 0))
                end
            end
        end
    end 

    function _Message:DrawTextWithBorder(textToDraw, textSize, x, y, textColor, backgroundColor)
        DrawText(textToDraw, textSize, x + 1, y, backgroundColor)
        DrawText(textToDraw, textSize, x - 1, y, backgroundColor)
        DrawText(textToDraw, textSize, x, y - 1, backgroundColor)
        DrawText(textToDraw, textSize, x, y + 1, backgroundColor)
        DrawText(textToDraw, textSize, x , y, textColor)
    end
-- }

--[[
    Class: ComboLibrary
    Author: iuser99
    - A library designed for minimizing overkills often found in common STBW combos. It has 
      several casting options and is extremely flexiable featuring customized cast conditions
      and casting methods.

    Constructor: 
        - nil
    
    Methods:
        - AddCasters(table)
            - Adds the table of casters to the current combo instance (ONLY ADD ONCE)
        - UpdateCaster(spell, caster)
            - Updates caster instance (ie. From HumanQ -> SpiderQ (Elise))
        - AddCastCondition(spell, function)
            - Adds condition for cast in combo (no matter which combo) 
        - AddCast(spell, function)
            - Adds custom cast to the caster (useful for something such as Varus Q, or Ezreal E)
        - CastCombo(Target)
            - Casts combo in priority of damage 
        - CastSequenced(Target)
            - Casts combo in order of instanced (added) to table
        - CastWeaved(Target, attacked)
            - Weaves combo (AA > Q > AA); recommended to not call this initially, but instead
              call SetWeave
        - SetWeave(boolean)
            - Sets weave to en/disabled (true/false); registers OnAttacked with SAC
--]]
class 'ComboLibrary' -- {
    
    function ComboLibrary:__init()
        self.casters = {}
        self.lastCast = 0 
        self.registeredWeave = false
        Menu.Get():addSubMenu("ComboLibrary", "ComboLibrary")
    end 

    function ComboLibrary:AddCasters(table)
        for _, v in pairs(table) do 
            self:AddCaster(v)
        end 
    end 

    function ComboLibrary:AddCaster(caster)
        local tempSpell = SpellStrings[caster.spell]
        Menu.Get("ComboLibrary"):addSubMenu(tempSpell, tempSpell)
        --Menu.Get("ComboLibrary")[tempSpell]:addParam("cblb1","-- ComboLibrary Options --", SCRIPT_PARAM_INFO, "")
        Menu.Get("ComboLibrary")[tempSpell]:addParam("use" .. myHero.charName, "Use in smart combo", SCRIPT_PARAM_ONOFF, true)
        Menu.Get("ComboLibrary")[tempSpell]:addParam("distance" .. myHero.charName, "Required distance",SCRIPT_PARAM_SLICE, caster.range, 0, caster.range, 0)
        table.insert(self.casters, {spellVar = caster.spell, casterInstance = caster, damage = 0, customCastCondition = nil, mana = 0, customCast = nil})
    end 

    function ComboLibrary:UpdateCaster(spellVar, caster) 
        for k, v in pairs(self.casters) do 
            if v.spellVar == sepllVar then
                self.casters[k].casterInstance = caster 
                break 
            end 
        end 
    end 

    function ComboLibrary:AddCastCondition(spellVar, funct) 
        for k, v in pairs(self.casters) do 
            if v.spellVar == spellVar then
                self.casters[k].customCastCondition = funct
                break 
            end 
        end 
    end 

    function ComboLibrary:AddCustomCast(spellVar, funct)
        --Deprecate("ComboLibrary:AddCustomCast", "ComboLibrary:AddCastCondition")
        self:AddCastCondition(spellVar, funct)
    end 

    function ComboLibrary:AddCast(spellVar, funct) 
        for k, v in pairs(self.casters) do 
            if v.spellVar == spellVar then
                self.casters[k].customCast = funct
                break 
            end 
        end 
    end 

    function ComboLibrary:CheckMana(currentCombo) 
        local totalCost = 0
        for v, caster in pairs(currentCombo) do 
            totalCost = totalCost + myHero:GetSpellData(caster.spellVar).mana 
        end 
        return totalCost <= myHero.mana 
    end 

    function ComboLibrary:UpdateDamages(target) 
        for k, caster in pairs(self.casters) do 
            self.casters[k].damage = getDmg(SpellStrings[caster.spellVar], target, myHero)
            self.casters[k].mana = myHero:GetSpellData(caster.spellVar).mana 
        end 
    end

    function ComboLibrary:Sort() 
        table.sort(self.casters, function(a,b) 
            if a.damage == b.damage then 
                return a.mana < b.mana 
            end 
            return a.damage > b.damage
         end)
    end 

    function ComboLibrary:GetCombo(target, asCaster) 
        local damage = 0
        local currentCombo = {}
        self:UpdateDamages(target)
        self:Sort()
        damage = damage + getDmg("AD", target, myHero)
        for k, v in ipairs(self.casters) do 
            if damage >= target.health then
                break 
            end 
            if v.casterInstance:Ready() then
                damage = damage + v.damage 
                table.insert(currentCombo, v)
            end 
        end 
        if self:CheckMana(currentCombo) and asCaster then
            return self:ToCasters(currentCombo)
        end
        return currentCombo
    end 

    function ComboLibrary:ToCasters(combo) 
        local localCasters = {}
        for k, v in pairs(combo) do 
            table.insert(localCasters, v.casterInstance)
        end 
        return localCasters
    end 

    function ComboLibrary:CastCombo(target) 
        if target == nil or target.dead then return false end 
        local combo = self:GetCombo(target, false) 
        for k, caster in ipairs(combo) do 
            if not target or target.dead then return true end 
            if Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["use" .. myHero.charName] then
                if caster.casterInstance:Ready() and ValidTarget(target, Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["distance" .. myHero.charName]) and (caster.customCastCondition == nil or caster.customCastCondition(target)) then
                    if caster.customCast ~= nil then
                        caster.customCast(target) 
                    else 
                        caster.casterInstance:Cast(target)
                    end 
                end 
            end 
        end 
    end 

    function ComboLibrary:CastSequenced(target)
        self:CastSequenced(Target, false) 
    end 

    function ComboLibrary:CastSequenced(target, override) 
        if target == nil or target.dead then return false end 
        for k, caster in ipairs(self.casters) do 
            if Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["use" .. myHero.charName] or override then
                if caster.casterInstance:Ready() and ValidTarget(target, Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["distance" .. myHero.charName]) and (caster.customCastCondition == nil or caster.customCastCondition(target)) then
                    if caster.customCast ~= nil then
                        caster.customCast(target) 
                    else 
                        caster.casterInstance:Cast(target)
                    end 
                end 
            end 
        end 
    end 

    function ComboLibrary:CastSpecific(target, spells) 
        if target == nil or target.dead then return false end 
        for k, caster in ipairs(self.casters) do 
            for spell, v in pairs(spells) do 
                if caster.spellVar == v then 
                    if Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["use" .. myHero.charName] then
                        if caster.casterInstance:Ready() and ValidTarget(target, Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["distance" .. myHero.charName]) and (caster.customCastCondition == nil or caster.customCastCondition(target)) then
                            if caster.customCast ~= nil then
                                caster.customCast(target) 
                            else 
                                caster.casterInstance:Cast(target)
                            end 
                        end 
                    end 
                end 
            end 
        end 
    end 

    function ComboLibrary:CastWeaved(target, attacked) 
        if target == nil or target.dead or not self.weave then return false end 
        if attacked or GetTickCount() - self.lastCast > 3000 then
            if AutoCarry.Keys.AutoCarry then  
                for k, caster in ipairs(self.casters) do 
                    if Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["use" .. myHero.charName] then
                        if caster.casterInstance:Ready() and ValidTarget(target, Menu.Get("ComboLibrary")[SpellStrings[caster.spellVar]]["distance" .. myHero.charName]) and (caster.customCastCondition == nil or caster.customCastCondition(target)) then
                            if caster.customCast ~= nil then
                                self.lastCast = GetTickCount()
                                return caster.customCast(target) 
                            else 
                                self.lastCast = GetTickCount()
                                return caster.casterInstance:Cast(target)
                            end 
                        end 
                    end 
                end 
            end 
        end 
    end 

    function ComboLibrary:OnAttacked()
        local target = AutoCarry.Crosshair:GetTarget()
        if target then 
            self:CastWeaved(target, true)
        end 
    end 

    function ComboLibrary:RegisterWeave() 
        AutoCarry.Plugins:RegisterOnAttacked(function() self:OnAttacked() end)
        self.registeredWeave = true
    end 

    function ComboLibrary:SetWeave(bool) 
        self.weave = bool 
        if bool then 
            if not self.registeredWeave then 
                self:RegisterWeave()
            end 
        end 
    end 

    function ComboLibrary.KillableCast(Target, spellName) 
        return getDmg(spellName, Target, myHero) > Target.health
    end 
-- }

class 'Calculation' -- {
   
   function Calculation.CountSurrounding(source, radius, list)
        local c = 0 
        for _, o in pairs(list) do 
            if o and o.valid and GetDistance(o, source) < radius then 
                c = c + 1 
            end 
        end 
        return c 
   end 

   function Calculation.GetNearest(source, radius, list)
        local b = nil
        local d = 20000
        for _, o in pairs(list) do 
            if o and o.valid and GetDistance(source, o) < radius then 
                if GetDistance(o) < d then
                    d = GetDistance(o)
                    b = o 
                end 
            end 
        end 
        return b 
   end 

   function Calculation.InRange(source, teamFlag, destination) 
        if not destination then 
            destination = {}
            for i=1, heroManager.iCount do 
                local p = heroManager:GetHero(i)
                if p and not p.dead and ((teamFlag and p.team == teamFlag) or true) then 
                    if GetDistance(source, p) <= p.range then 
                        table.insert(destination, p)
                    end 
                end 
            end 
            if #destination > 1 then 
                return true, destination 
            end 
        else 
            if GetDistance(source, destination) <= destination then 
                return true, destination 
            end 
        end 
    end 

-- }

class 'AutoUpdate' -- {

	function NewIniReader()
		local reader = {}
		function reader:Read(fName)
			self.root = {}
			self.reading_section = ""
			for line in io.lines(fName) do
				if startsWith(line, "[") then
					local section = string.sub(line,2,-2)
					self.root[section] = {}
					self.reading_section = section;
				elseif not startsWith(line, ";") then
					if self.reading_section then
						local var,val = line:usplit("=")
						self.root[self.reading_section] = self.root[self.reading_section] or {}
						self.root[self.reading_section][var] = val
					end
				end
			end
		end
		function reader:GetValue(Section, Key)
			return self.root[Section][Key]
		end
		function reader:GetKeys(Section)
			return self.root[Section]
		end
		return reader;
	end

	function startsWith(text,prefix)
		return string.sub(text, 1, string.len(prefix)) == prefix
	end

	function string:usplit(sep)
		return self:match("([^" .. sep .. "]+)[" .. sep .. "]+(.+)")
	end
   
   function AutoUpdate:__init(VersionURL, className)
   		self.VERSION_PATH = os.getenv("APPDATA").."\\" .. myHero.charName.."Version.ini"
   		self.url = VersionURL 
   		self.PLUGIN_PATH = BOL_PATH.."Scripts\\" .. className .. ".lua"
   		DownloadFile(self.url, self.VERSION_PATH, function() end) 
   end 

   function AutoUpdate:Update(version)
   		reader = NewIniReader()
   		if FileExists(self.VERSION_PATH) then 
   			reader:Read(self.VERSION_PATH)
   			newDownloadURL = reader:GetValue("Version", "Download")
			newVersion = reader:GetValue("Version", "Version")
			newMessage = reader:GetValue("Version", "Message")

			os.remove(self.VERSION_PATH)

			if tonumber(newVersion) > tonumber(version) then 
				Downloadfile(newDownloadURL, self.PLUGIN_PATH, function()
						if FileExists(self.PLUGIN_PATH) then 
							 PrintAlert("Updated: " .. newMessage .. " (".. newVersion .. ")", 30, 0, 255, 255)
						end 
					end)
			else
				-- U2pdate 
			end 
   		else
   			-- Failed 
   		end 
   end 
 
-- }

class 'BuffManager' -- {
	
	BuffManager.instance = ""

	function BuffManager.Instance() 
		if BuffManager.instance == "" then BuffManager.instance = BuffManager() end return BuffManager.instance 
	end 

	function BuffManager:__init()
		self.enemies = {}
		AdvancedCallback:bind('OnGainBuff', function(unit, buff) self:OnGainBuff(unit, buff) end)
		AdvancedCallback:bind('OnLoseBuff', function(unit, buff) self:OnLoseBuff(unit, buff) end)
		AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) self:OnUpdateBuff(unit, buff) end)
		for i=0, heroManager.iCount, 1 do
	        local player = heroManager:GetHero(i)
	        if player and player.team ~= myHero.team then
	        	self.enemies[player.networkID] = {}
	        end 
		end
	end 

	function BuffManager.TargetHaveBuff(target, buffName, stacks)
		return BuffManager.Instance():_TargetHaveBuff(target, buffName, stacks) 
	end 

	function BuffManager:_TargetHaveBuff(target, buffName, stacks)
		self.stacks = stacks or 0
		if self.enemies[target.networkID] ~= nil then
			for _, buff in pairs(self.enemies[target.networkID]) do 
				if buff.name == buffName and buff.stack >= self.stacks then
					return true
				end  
			end 
		end 
	end 

	function BuffManager:OnGainBuff(unit, buff) 
		if unit == nil or buff == nil then return end 
		if unit.team ~= myHero.team then 
			if self.enemies[unit.networkID] ~= nil then
				table.insert(self.enemies[unit.networkID], buff)
			else 
				self.enemies[unit.networkID] = {}
				table.insert(self.enemies[unit.networkID], buff)
			end 
		end 
	end 

	function BuffManager:OnLoseBuff(unit, buff) 
		if unit == nil or buff == nil then return end 
		if unit.team ~= myHero.team then 
			if self.enemies[unit.networkID] ~= nil then 
				for i=1, #self.enemies[unit.networkID], 1 do 
					if self.enemies[unit.networkID][i].name == buff.name then
						table.remove(self.enemies[unit.networkID], i)
						break 
					end 
				end 
			end 
		end 
	end 

	function BuffManager:OnUpdateBuff(unit, buff) 
		if unit == nil or buff == nil then return end 
		if unit.team ~= myHero.team then 
			if self.enemies[unit.networkID] ~= nil then 
				for i=1, #self.enemies[unit.networkID], 1 do 
					if self.enemies[unit.networkID][i] == buff then
						table.remove(self.enemies[unit.networkID], i)
						break 
					end 
				end 
				table.insert(self.enemies[unit.networkID], buff)
			end 
		end 
	end 
-- }

class 'MovementPrediction' -- {

	DIRECTION_AWAY = 0 
	DIRECTION_TOWARDS = 1
	DIRECTION_UNKNOWN = 2
	
	MovementPrediction.instance = ""

	function MovementPrediction.Instance() 
		if MovementPrediction.instance == "" then MovementPrediction.instance = MovementPrediction() end return MovementPrediction.instance 
	end 

	function MovementPrediction:__init() 
		self.wp = WayPointManager()
	end 

	function MovementPrediction.GetDirection(Target)
		return MovementPrediction.Instance():_GetDirection(Target)
	end 

	function MovementPrediction:_GetDirection(Target)
		if Target and not Target.dead then 
			local points = self.wp:GetWayPoints(Target)
			if points[1] == nil or points[2] == nil then return DIRECTION_UNKNOWN end 
			local d1 = GetDistanceSqr(points[1]) 
			local d2 = GetDistanceSqr(points[2]) 
			if d1 < d2 then 
				return DIRECTION_AWAY
			elseif d1 > d2 then 
				return DIRECTION_TOWARDS
			end 
		end 
	end 

	function MovementPrediction.Place(Skill, Target) 
		MovementPrediction.Instance():_Place(Skill, Target)
	end 

	function MovementPrediction:_Place(Skill, Target)
		local direction = self:_GetDirection(Target)
		if direction == DIRECTION_TOWARDS then 
			--PrintChat("TOWARDS")
			self.PlaceInfront(Skill, Target)
		elseif direction == DIRECTION_AWAY then 
			--PrintChat("AWAY")
			self.PlaceBehind(Skill, Target)
		end 
	end 

	function MovementPrediction.PlaceBehind(Skill, enemy) 
		if Skill:Ready() and GetDistance(enemy) <= Skill.range then
			local TargetPosition = Vector(enemy.x, enemy.y, enemy.z)
			local MyPosition = Vector(myHero.x, myHero.y, myHero.z)		
			local WallPosition = TargetPosition + (TargetPosition - MyPosition)*((150/GetDistance(enemy)))
			CastSpell(Skill.spell, WallPosition.x, WallPosition.z)
		end
	end

	function MovementPrediction.PlaceInfront(Skill, enemy) 
		if Skill:Ready() and GetDistance(enemy) <= Skill.range then 
			local TargetPosition = Vector(enemy.x, enemy.y, enemy.z)
      		local MyPosition = Vector(myHero.x, myHero.y, myHero.z)
        	local SpellPosition = TargetPosition + (TargetPosition - MyPosition) * (-250 / GetDistance(enemy))
        	CastSpell(Skill.spell, SpellPosition.x, SpellPosition.z) 
		end 
	end 
-- }