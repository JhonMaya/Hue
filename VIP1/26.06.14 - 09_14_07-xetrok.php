<?php exit() ?>--by xetrok 27.122.126.1
--Project: SALib (In Progress name)
--Version: ??????
--User: BotHappy
--Time: ????????


if myHero.charName ~= "Nidalee" and myHero.charName ~= "Kayle" and myHero.charName ~= "Thresh" and myHero.charName ~= "Akali" and myHero.charName ~= "Nami" and myHero.charName ~= "Talon" and myHero.charName ~= "Sion" and myHero.charName ~= "Leona" and myHero.charName ~= "Mordekaiser" and myHero.charName ~= "LeeSin" and myHero.charName ~= "Galio" then return end

-------------------------------------------------------
--[[
	Class: Drawer
]]
-------------------------------------------------------
class 'Drawer' 

    Drawer.instance = ''

    function Drawer:__init(red, green, blue, alpha)
        self._R = red or 255
        self._G = green or 255
        self._B = blue or 255
        self._A = alpha or 255
    end

    function Drawer:__type()
        return "Drawer"
    end

    function Drawer.FromARGB(red, green, blue, alpha)
        return Drawer(red,green,blue, alpha)
    end

    function Drawer:A(number)
        self._A = number or 255
        return self
    end 

    function Drawer:R(number)
        self._R = number or 255
        return self
    end

    function Drawer:B(number)
        self._B = number or 255
        return self
    end

    function Drawer:G(number)
        self._G = number or 255
        return self
    end

    function Drawer:ToARGB()
        return ARGB(self._A, self._R, self._G, self._B)
    end

    _G.Drawer = {
        AliceBlue = Drawer(240, 248, 255, 255),
        AntiqueWhite = Drawer(250, 235, 215, 255),
        Aqua = Drawer(0, 255, 255, 255),
        AquaMarine = Drawer(127, 255, 212, 255),
        Azure = Drawer(240, 255, 255, 255),
        Beige = Drawer(245, 245, 196, 255),
        Bisque = Drawer(255, 228, 196, 255),
        Black = Drawer(0, 0, 0, 255),
        BlancheDalmond = Drawer(255, 235, 205, 255),
        Blue = Drawer(0, 0, 255, 255),
        BlueViolet = Drawer(138, 43, 226, 255),
        Brown = Drawer(165, 42, 42, 255),
        BurlyWood = Drawer(222, 184, 135, 255),
        CadetBlue = Drawer(92, 158, 160, 255),
        ChartReuse = Drawer(127, 255, 0, 255),
        Chocolate = Drawer(210, 105, 30, 255),
        Coral = Drawer(255, 127, 80, 255),
        CornFlowerBlue = Drawer(100, 149, 237, 255),
        CornSilk = Drawer(255, 248, 220, 255),
        Crimson = Drawer(220, 20, 60, 255),
        Cyan = Drawer(0, 255, 255, 255),
        DarkBlue = Drawer(0, 0, 139, 255),
        DarkCyan = Drawer(0, 139, 139, 255),
        DarkGoldenRod = Drawer(184, 134, 11, 255),
        DarkGray = Drawer(169, 169, 169, 255),
        DarkGreen = Drawer(0, 100, 0, 255),
        DarkKhaki = Drawer(189, 183, 107, 255),
        DarkMagenta = Drawer(139, 0, 139, 255),
        DarkOliveGreen = Drawer(85, 107, 47, 255),
        DarkOrange = Drawer(255, 140, 0, 255),
        DarkOrchid = Drawer(153, 50, 204, 255),
        DarkRed = Drawer(139, 0, 0, 255),
        DarkSalmon = Drawer(233, 150, 122, 255),
        DarkSeaGreen = Drawer(143, 188, 143, 255),
        DarkSlateBlue = Drawer(72, 61, 139, 255),
        DarkSlateGray = Drawer(47, 79, 79, 255),
        DarkTurquoise = Drawer(0, 206, 209, 255),
        DarkViolet = Drawer(148, 0, 211, 255),
        DeepPink = Drawer(255, 20, 147, 255),
        DeepSkyBlue = Drawer(0, 191, 255, 255),
        DimGray = Drawer(105, 105, 105, 255),
        DodgerBlue = Drawer(30, 144, 255, 255),
        FireBrick = Drawer(178, 34, 34, 255),
        FloralWhite = Drawer(255, 250, 240, 255),
        ForestGreen  = Drawer(34, 139, 34, 255),
        Fuchsia = Drawer(255, 0, 255, 255),
        GainsBoro = Drawer(220, 220, 220, 255),
        GhostWhite = Drawer(255, 250, 240, 255),
        Gold = Drawer(255, 215, 0, 255),
        GoldenRod = Drawer(218, 165, 32, 255),
        Gray = Drawer(128, 128, 128, 255),
        Green = Drawer(0, 255, 0, 255),
        GreenYellow = Drawer(173, 255, 47, 255),
        HoneyDew = Drawer(240, 255, 240, 255),
        HotPink = Drawer(255, 105, 180, 255),
        IndianRed = Drawer(205, 92, 92, 255),
        Indigo = Drawer(75, 0, 130, 255),
        Ivory  = Drawer(255, 255, 240, 255),
        Khaki = Drawer(240, 230, 140, 255),
        Lavender = Drawer(230, 230, 250, 255),
        LavenderBlush = Drawer(255, 240, 245),
        LawnGreen = Drawer(124, 252, 0, 255),
        LemonChiffon = Drawer(255, 250, 205, 255),
        LightBlue = Drawer(173, 216, 230, 255),
        LightCoral = Drawer(240, 128, 128, 255),
        LightCyan = Drawer(240, 128, 128, 255),
        LightGoldenRodYellow = Drawer(250, 250, 210, 255),
        LightGray = Drawer(211, 211, 211, 255),
        LightGreen = Drawer(144, 238, 144, 255),
        LightPink = Drawer(255, 182, 193, 255),
        LightSalmon = Drawer(255, 160, 122, 255),
        LightSeaGreen = Drawer(32, 178, 170, 255),
        LightSkyBlue = Drawer(135, 206, 250, 255),
        LightSlateGray = Drawer(119, 136, 153, 255),
        LightSteelBlue = Drawer(176, 196, 222, 255),
        LightYellow = Drawer(255, 255, 224, 255),
        Lime = Drawer(0, 255, 0, 255),
        LimeGreen = Drawer(50, 205, 50, 255),
        Linen = Drawer(250, 240, 230, 255),
        Magenta = Drawer(255, 0, 255, 255),
        Maroon = Drawer(128, 0, 0, 255),
        MediumAquaMarine  = Drawer(102, 205, 170, 255),
        MediumBlue = Drawer(0, 0, 205, 255),
        MediumOrchid = Drawer(186, 85, 211, 255),
        MediumPurple = Drawer(147, 112, 219, 255),
        MediumSeaGreen = Drawer(60, 179, 113, 255),
        MediumSlateBlue = Drawer(123, 104, 238, 255),
        MediumSpringGreen = Drawer( 0, 250, 154, 255),
        MediumTurquoise = Drawer(72, 209, 204, 255),
        MediumVioletred = Drawer(199, 21, 133, 255),
        Midnightblue = Drawer(25, 25, 112, 255),
        MintCream = Drawer(245, 255, 250, 255),
        MistyRose = Drawer(255, 228, 225, 255),
        Red = Drawer(255, 0, 0, 255),
        Yellow = Drawer(255, 255, 0, 255),
        Green = Drawer(0, 255, 0, 255),
        Fuchsia = Drawer(255, 0, 255, 255),
        White = Drawer(255, 255, 255, 255),
    }

	function Drawer:DrawCircleHero(hero, range, colorName, ready)
        if ready then
            if colorName == nil then
                DrawCircle(hero.x, hero.y, hero.z, range, self:ToARGB())
            else
                DrawCircle(hero.x, hero.y, hero.z, range, colorName:ToARGB())
            end
        end
	end

	function Drawer:DrawTextEnemy(hero, text, colorName)
		local barPos = WorldToScreen(D3DXVECTOR3(hero.x, hero.y, hero.z))
		local posX = barPos.x-- - 35
		local posY = barPos.y - 50
        if colorName == nil then
            DrawText(text, 13, posX, posY, ARGB(255, 255, 0, 0))
        else
            DrawText(text, 13, posX, posY, colorName:ToARGB())
        end
	end

    function Drawer:DrawOnHPBar(unit, text, colorName)
        local pos = self:_GetHPBarPos(unit)
        if colorName == nil then
            DrawCircle(unit.x, unit.y, unit.z, 100, ARGB(255, 255, 0, 0))  
            DrawText(text,13, math.floor(pos.x), (pos.y+38), ARGB(255, 255, 0, 0))
        else
            DrawCircle(unit.x, unit.y, unit.z, 100, colorName:ToARGB())  
            DrawText(text,13, math.floor(pos.x), (pos.y+38), colorName:ToARGB())
        end
    end

    function Drawer:_GetHPBarPos(enemy)
        enemy.barData = GetEnemyBarData()
        local barPos = GetUnitHPBarPos(enemy)
        local barPosOffset = GetUnitHPBarOffset(enemy)
        local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
        local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
        local BarPosOffsetX = 171
        local BarPosOffsetY = 46
        local CorrectionY =  -1 --Changed
        local StartHpPos = 31
        barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos
        barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY 
                            
        local StartPos = Vector(barPos.x , barPos.y, 0)
        local EndPos =  Vector(barPos.x + 108 , barPos.y , 0)

        return Vector(StartPos.x, StartPos.y, 0), Vector(EndPos.x, EndPos.y, 0)
    end


    function Drawer:DrawIndicator(unit, health, colorName)
        local SPos, EPos = self:_GetHPBarPos(unit)
        local barlenght = EPos.x - SPos.x
        local Position = SPos.x + (health / unit.maxHealth) * barlenght
        if Position < SPos.x then
            Position = SPos.x
        end
        if colorName == nil then
            DrawText("|", 13,  math.floor(Position),  math.floor(SPos.y+10), ARGB(255,0,255,0))
        else
            DrawText("|", 13,  math.floor(Position),  math.floor(SPos.y+10), colorName:ToARGB())
        end
    end

-------------------------------------------------------
--[[
	Class: Hero
]]
-------------------------------------------------------

class 'Hero'
	function Hero:__init()
		self.myTrueRange = myHero.range + GetDistance(myHero.minBBox)
	end

    function Hero:__type()
        return "Hero"
    end

	function Hero:TrueRange(unit)
		return unit.range + GetDistance(unit.minBBox)
	end

	function Hero:CountInRange(range, hero)
        if hero == nil then hero = myHero end
        local allyInRange, enemyInRange = 0, 0
        for i = 1, heroManager.iCount, 1 do
            local character = heroManager:getHero(i)
            if character ~=nil and GetDistanceSqr(character, hero) <range*range and character.networkID ~= hero.networkID then
                if character.team == hero.team then
                    allyInRange = allyInRange + 1
                else
                    enemyInRange = enemyInRange +1
                end
            end
        end
        return allyInRange, enemyInRange
    end

-------------------------------------------------------
--[[
	Class: Helper
]]
-------------------------------------------------------

class 'Helper'

	function Helper:__init()
        self.actualTick = 0
        self.latency = 100
		self.initialTick = GetTickCount()
		AddTickCallback(function() self:_OnTick() end)
	end

    function Helper:__type()
        return "Helper"
    end

	function Helper:_OnTick()
		self.actualTick = GetTickCount()
		self.latency = GetLatency()
	end

	function Helper:Evading()
		return _G.evade or _G.evading
	end

    function Helper:__validTarget(unit, distance)
        return ((ValidTarget(unit, distance) and unit.bTargetable and unit.type:lower() == "obj_ai_hero") and true or false)
    end

    function Helper:__correctDistance(unit, distance)
        return GetDistanceSqr(unit) < distance*distance
    end

    function Helper:CountEnemies(point, range)
        local ChampCount = 0
        for j = 1, heroManager.iCount, 1 do
            local enemyhero = heroManager:getHero(j)
            if myHero.team ~= enemyhero.team and ValidTarget(enemyhero, range) then
                if GetDistanceSqr(enemyhero, point) <= range*range then
                    ChampCount = ChampCount + 1
                end
            end
        end            
        return ChampCount
    end

-------------------------------------------------------
--[[
	Class: Map
	Version: 0.02
	TO-DO:
		*Win or lose idk
	Changelog:
		0.01: First build
        0.02: Improved performance a little
]]
-------------------------------------------------------

class 'Map'

    function Map:__init()
        self.name = GetGame().map.shortName
    end

    function Map:__type()
        return "Map"
    end

    function Map:TwistedTreeline()
        if self.name == "twistedTreeline" then
            return true
        else
        	return false
        end
    end

    function Map:ARAM()
        if self.name == "howlingAbyss" then
            return true
        else
            return false
        end
    end

    function Map:Dominion()
        if self.name == "crystalScar" then
            return true
        else
            return false
        end
    end

    function Map:SummonersRift()
        if self.name == "SummonersRift" then
            return true
        else
            return false
        end
    end

-------------------------------------------------------
--[[
	Class: Priorities
	Version: 0.03
	TO-DO:
		*Dinamic Table
        *Improve for 1,2,4 and 6
        *Maybe dominion focus
	Changelog:
		0.01: First build
        0.02: Fixes
        0.03: Improved, should work.
]]
-------------------------------------------------------

class 'Priorities'

    local _priorityTable = {
        AP = {
            "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
            "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
            "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Velkoz", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
                },
        Support = {
            "Alistar", "Blitzcrank", "Braum", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
                    },
        Tank = {
            "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Nautilus", "Shen", "Singed", "Skarner", "Volibear",
            "Warwick", "Yorick", "Zac",
                },
        AD_Carry = {
            "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MasterYi", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
            "Talon","Tryndamere", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Yasuo","Zed", 
                    },
        Bruiser = {
            "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nocturne", "Olaf", "Poppy",
            "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao",
                },
        }

    function Priorities:__init()

    end

    function Priorities:__type()
        return "Priorities"
    end

	function Priorities:Load()
		if Map():TwistedTreeline() and (#GetEnemyHeroes() >2) then
			self:_ArrangeTTPrioritys()
		elseif ((#GetEnemyHeroes())> 4) then
			self:_ArrangePrioritys()
		end
	end

	function Priorities:_ArrangePrioritys()
	    for i, enemy in pairs(GetEnemyHeroes()) do
	        self:_SetPriority(_priorityTable.AD_Carry, enemy, 1)
	        self:_SetPriority(_priorityTable.AP, enemy, 2)
	        self:_SetPriority(_priorityTable.Support, enemy, 3)
	        self:_SetPriority(_priorityTable.Bruiser, enemy, 4)
	        self:_SetPriority(_priorityTable.Tank, enemy, 5)
	    end
	end

	function Priorities:_ArrangeTTPrioritys()
		for i, enemy in pairs(GetEnemyHeroes()) do
			self:_SetPriority(_priorityTable.AD_Carry, enemy, 1)
	        self:_SetPriority(_priorityTable.AP, enemy, 1)
	        self:_SetPriority(_priorityTable.Support, enemy, 2)
	        self:_SetPriority(_priorityTable.Bruiser, enemy, 3)
	        self:_SetPriority(_priorityTable.Tank, enemy, 3)
		end
	end

	function Priorities:_SetPriority(table, hero, priority)
	    for i=1, #table, 1 do
	        if hero.charName:find(table[i]) ~= nil then
	            TS_SetHeroPriority(priority, hero.charName)
	        end
	    end
	end

-------------------------------------------------------
--[[
	Class: Summoners
	Version: 0.02
	Usage: Summoners cast and Helper
	TO-DO:
		*Add AutoSummoners with Logic
	Changelog:
		0.01: First build
        0.02: Improved Code
]]
-------------------------------------------------------

class 'Summoners'

	Summoners.List= {
		SummonerFlash =	        {name="Flash",           IGN="SummonerFlash",        range=400},
		SummonerHaste =	        {name="Ghost",           IGN="SummonerHaste",        range=nil},
		SummonerDot = 	        {name="Ignite",          IGN="SummonerDot",          range=600},
		SummonerBarrier =	    {name="Barrier",         IGN="SummonerBarrier",      range=nil},
		SummonerSmite =	        {name="Smite",           IGN="SummonerSmite",        range=625},
		SummonerExhaust =	    {name="Exhaust",         IGN="SummonerExhaust",      range=650},
		SummonerHeal =	        {name="Heal",            IGN="SummonerHeal",         range=700},
		SummonerTeleport =	    {name="Teleport",        IGN="SummonerTeleport",     range=nil},
		SummonerBoost =	        {name="Cleanse",         IGN="SummonerBoost",        range=nil},
		SummonerMana =	        {name="Clarity",         IGN="SummonerMana",         range=600},
		SummonerClairvoyance =	{name="Clairvoyance",    IGN="SummonerClairvoyance", range=nil},
		SummonerRevive =	    {name="Revive",          IGN="SummonerRevive",       range=nil},
		SummonerOdinGarrison =	{name="Garrison",        IGN="SummonerOdinGarrison", range=1000}
		}

    function Summoners:__init()
        
    end

    function Summoners:__type()
        return "Summoners"
    end

    function Summoners:SmiteDmg(level)
        return math.max(20*level+370,30*level+330,40*level+240,50*level+100) 
    end


    function _G.CheckSummoner(IGNName)
        if myHero:GetSpellData(SUMMONER_1).name:find(IGNName) then 
            return SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find(IGNName) then 
            return SUMMONER_2
        end
        return nil
    end

-------------------------------------------------------
--[[
    Class: Collision with Prodiction
    Version: 0.01
]]
-------------------------------------------------------

uniqueId = 0

class 'Collision' -- {
    HERO_ALL = 1
    HERO_ENEMY = 2
    HERO_ALLY = 3


    function Collision:__init(sRange, projSpeed, sDelay, sWidth)
        uniqueId = uniqueId + 1
        self.uniqueId = uniqueId

        self.sRange = sRange
        self.projSpeed = projSpeed
        self.sDelay = sDelay
        self.sWidth = sWidth/2

        self.enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
        self.minionupdate = 0
    end

    function Collision:GetMinionCollision(pStart, pEnd)
        self.enemyMinions:update()

        local distance =  GetDistance(pStart, pEnd)
        local prediction = TargetPredictionVIP(self.sRange, self.projSpeed, self.sDelay, self.sWidth)
        local mCollision = {}

        if distance > self.sRange then
            distance = self.sRange
        end

        local V = Vector(pEnd) - Vector(pStart)
        local k = V:normalized()
        local P = V:perpendicular2():normalized()

        local t,i,u = k:unpack()
        local x,y,z = P:unpack()

        local startLeftX = pStart.x + (x *self.sWidth)
        local startLeftY = pStart.y + (y *self.sWidth)
        local startLeftZ = pStart.z + (z *self.sWidth)
        local endLeftX = pStart.x + (x * self.sWidth) + (t * distance)
        local endLeftY = pStart.y + (y * self.sWidth) + (i * distance)
        local endLeftZ = pStart.z + (z * self.sWidth) + (u * distance)
        
        local startRightX = pStart.x - (x * self.sWidth)
        local startRightY = pStart.y - (y * self.sWidth)
        local startRightZ = pStart.z - (z * self.sWidth)
        local endRightX = pStart.x - (x * self.sWidth) + (t * distance)
        local endRightY = pStart.y - (y * self.sWidth) + (i * distance)
        local endRightZ = pStart.z - (z * self.sWidth)+ (u * distance)

        local startLeft = WorldToScreen(D3DXVECTOR3(startLeftX, startLeftY, startLeftZ))
        local endLeft = WorldToScreen(D3DXVECTOR3(endLeftX, endLeftY, endLeftZ))
        local startRight = WorldToScreen(D3DXVECTOR3(startRightX, startRightY, startRightZ))
        local endRight = WorldToScreen(D3DXVECTOR3(endRightX, endRightY, endRightZ))
       
        local poly = Polygon(Point(startLeft.x, startLeft.y),  Point(endLeft.x, endLeft.y), Point(startRight.x, startRight.y),   Point(endRight.x, endRight.y))

         for index, minion in pairs(self.enemyMinions.objects) do
            if minion ~= nil and minion.valid and not minion.dead then
                if GetDistance(pStart, minion) < distance then
                    local pos, t, vec = prediction:GetPrediction(minion) 
                    local lineSegmentLeft = LineSegment(Point(startLeftX,startLeftZ), Point(endLeftX, endLeftZ))
                    local lineSegmentRight = LineSegment(Point(startRightX,startRightZ), Point(endRightX, endRightZ))
                    local toScreen, toPoint
                    if pos ~= nil then
                        toScreen = WorldToScreen(D3DXVECTOR3(minion.x, minion.y, minion.z))
                        toPoint = Point(toScreen.x, toScreen.y)
                    else 
                        toScreen = WorldToScreen(D3DXVECTOR3(minion.x, minion.y, minion.z))
                        toPoint = Point(toScreen.x, toScreen.y)
                    end


                    if poly:contains(toPoint) then
                        table.insert(mCollision, minion)
                    else
                        if pos ~= nil then
                            distance1 = Point(pos.x, pos.z):distance(lineSegmentLeft)
                            distance2 = Point(pos.x, pos.z):distance(lineSegmentRight)
                        else 
                            distance1 = Point(minion.x, minion.z):distance(lineSegmentLeft)
                            distance2 = Point(minion.x, minion.z):distance(lineSegmentRight)
                        end
                        if (distance1 < (getHitBoxRadius(minion)*2+10) or distance2 < (getHitBoxRadius(minion) *2+10)) then
                            table.insert(mCollision, minion)
                        end
                    end
                end
            end
        end
        if #mCollision > 0 then return true, mCollision else return false, mCollision end
    end

    function Collision:GetHeroCollision(pStart, pEnd, mode)
        if mode == nil then mode = HERO_ENEMY end
        local heros = {}

        for i = 1, heroManager.iCount do
            local hero = heroManager:GetHero(i)
            if (mode == HERO_ENEMY or mode == HERO_ALL) and hero.team ~= myHero.team then
                table.insert(heros, hero)
            elseif (mode == HERO_ALLY or mode == HERO_ALL) and hero.team == myHero.team and not hero.isMe then
                table.insert(heros, hero)
            end
        end

        local distance =  GetDistance(pStart, pEnd)
        local prediction = TargetPredictionVIP(self.sRange, self.projSpeed, self.sDelay, self.sWidth)
        local hCollision = {}

        if distance > self.sRange then
            distance = self.sRange
        end

        local V = Vector(pEnd) - Vector(pStart)
        local k = V:normalized()
        local P = V:perpendicular2():normalized()

        local t,i,u = k:unpack()
        local x,y,z = P:unpack()

        local startLeftX = pStart.x + (x *self.sWidth)
        local startLeftY = pStart.y + (y *self.sWidth)
        local startLeftZ = pStart.z + (z *self.sWidth)
        local endLeftX = pStart.x + (x * self.sWidth) + (t * distance)
        local endLeftY = pStart.y + (y * self.sWidth) + (i * distance)
        local endLeftZ = pStart.z + (z * self.sWidth) + (u * distance)
        
        local startRightX = pStart.x - (x * self.sWidth)
        local startRightY = pStart.y - (y * self.sWidth)
        local startRightZ = pStart.z - (z * self.sWidth)
        local endRightX = pStart.x - (x * self.sWidth) + (t * distance)
        local endRightY = pStart.y - (y * self.sWidth) + (i * distance)
        local endRightZ = pStart.z - (z * self.sWidth)+ (u * distance)

        local startLeft = WorldToScreen(D3DXVECTOR3(startLeftX, startLeftY, startLeftZ))
        local endLeft = WorldToScreen(D3DXVECTOR3(endLeftX, endLeftY, endLeftZ))
        local startRight = WorldToScreen(D3DXVECTOR3(startRightX, startRightY, startRightZ))
        local endRight = WorldToScreen(D3DXVECTOR3(endRightX, endRightY, endRightZ))
       
        local poly = Polygon(Point(startLeft.x, startLeft.y),  Point(endLeft.x, endLeft.y), Point(startRight.x, startRight.y),   Point(endRight.x, endRight.y))

        for index, hero in pairs(heros) do
            if hero ~= nil and hero.valid and not hero.dead then
                if GetDistance(pStart, hero) < distance then
                    local pos, t, vec = prediction:GetPrediction(hero) 
                    local lineSegmentLeft = LineSegment(Point(startLeftX,startLeftZ), Point(endLeftX, endLeftZ))
                    local lineSegmentRight = LineSegment(Point(startRightX,startRightZ), Point(endRightX, endRightZ))
                    local toScreen, toPoint
                    if pos ~= nil then
                        toScreen = WorldToScreen(D3DXVECTOR3(pos.x, hero.y, pos.z))
                        toPoint = Point(toScreen.x, toScreen.y)
                    else 
                        toScreen = WorldToScreen(D3DXVECTOR3(hero.x, hero.y, hero.z))
                        toPoint = Point(toScreen.x, toScreen.y)
                    end


                    if poly:contains(toPoint) then
                        table.insert(hCollision, hero)
                    else
                        if pos ~= nil then
                            distance1 = Point(pos.x, pos.z):distance(lineSegmentLeft)
                            distance2 = Point(pos.x, pos.z):distance(lineSegmentRight)
                        else 
                            distance1 = Point(hero.x, hero.z):distance(lineSegmentLeft)
                            distance2 = Point(hero.x, hero.z):distance(lineSegmentRight)
                        end
                        if (distance1 < (getHitBoxRadius(hero)*2+10) or distance2 < (getHitBoxRadius(hero) *2+10)) then
                            table.insert(hCollision, hero)
                        end
                    end
                end
            end
        end
        if #hCollision > 0 then return true, hCollision else return false, hCollision end
    end

    function Collision:GetCollision(pStart, pEnd)
        local b , minions = self:GetMinionCollision(pStart, pEnd)
        local t , heros = self:GetHeroCollision(pStart, pEnd, HERO_ENEMY)

        if not b then return t, heros end 
        if not t then return b, minions end 

        local all = {}

        for index, hero in pairs(heros) do
            table.insert(all, hero)
        end

        for index, minion in pairs(minions) do
            table.insert(all, minion)
        end 

        return true, all
    end

    function Collision:DrawCollision(pStart, pEnd)
        
        local distance =  GetDistance(pStart, pEnd)

        if distance > self.sRange then
            distance = self.sRange
        end

        local color = 4294967295

        local V = Vector(pEnd) - Vector(pStart)
        local k = V:normalized()
        local P = V:perpendicular2():normalized()

        local t,i,u = k:unpack()
        local x,y,z = P:unpack()

        local startLeftX = pStart.x + (x *self.sWidth)
        local startLeftY = pStart.y + (y *self.sWidth)
        local startLeftZ = pStart.z + (z *self.sWidth)
        local endLeftX = pStart.x + (x * self.sWidth) + (t * distance)
        local endLeftY = pStart.y + (y * self.sWidth) + (i * distance)
        local endLeftZ = pStart.z + (z * self.sWidth) + (u * distance)
        
        local startRightX = pStart.x - (x * self.sWidth)
        local startRightY = pStart.y - (y * self.sWidth)
        local startRightZ = pStart.z - (z * self.sWidth)
        local endRightX = pStart.x - (x * self.sWidth) + (t * distance)
        local endRightY = pStart.y - (y * self.sWidth) + (i * distance)
        local endRightZ = pStart.z - (z * self.sWidth)+ (u * distance)

        local startLeft = WorldToScreen(D3DXVECTOR3(startLeftX, startLeftY, startLeftZ))
        local endLeft = WorldToScreen(D3DXVECTOR3(endLeftX, endLeftY, endLeftZ))
        local startRight = WorldToScreen(D3DXVECTOR3(startRightX, startRightY, startRightZ))
        local endRight = WorldToScreen(D3DXVECTOR3(endRightX, endRightY, endRightZ))

        local colliton, objects = self:GetCollision(pStart, pEnd)
        
        if colliton then
            color = 4294901760
        end

        for i, object in pairs(objects) do
            DrawCircle(object.x,object.y,object.z,getHitBoxRadius(object)*2+20,4294901760)
        end 

        DrawLine(startLeft.x, startLeft.y, endLeft.x, endLeft.y, 1, color)
        DrawLine(startRight.x, startRight.y, endRight.x, endRight.y, 1, color) 

    end

    function getHitBoxRadius(target)
        return GetDistance(target, target.minBBox)/2
    end


class 'CombatHandler'

    function CombatHandler:__init(TargetSelector)
        self.ts = TargetSelector
        self._RangeExpection = {
            ["Kayle"] = true,
            ["LeeSin"] = true,
            ["Nidalee"] = true
        }

        if self.ts.range < 700 and self._RangeExpection[myHero.charName] then
            AddTickCallback(function()
                self.ts.range = myHero.range + 65-- + 50
            end)
        end
    end

    function CombatHandler:__type()
        return "CombatHandler"
    end

    function CombatHandler:GetTrueRange(Unit)
        if ValidTarget(Unit) then
            return GetDistance(myHero.minBBox) + myHero.range + GetDistance(Unit.minBBox, Unit.maxBBox)/2
        else
            return nil
        end
    end

    function CombatHandler:GetTarget()
        if _G.MMA_Target ~= nil and _G.MMA_Target.type:lower() == "obj_ai_hero" then
            return _G.MMA_Target
        end

        if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Crosshair.Attack_Crosshair.target ~= nil and _G.AutoCarry.Crosshair.Attack_Crosshair.target.type:lower() == "obj_ai_hero" then
            return _G.AutoCarry.Crosshair.Attack_Crosshair.target
        end

        --if _G.SOWLoaded and 

        self.ts:update()

        return self.ts.target
    end

class 'Orbwalker'

    Orbwalker.instance = 'Default'

    function Orbwalker:__init(Name)
        self.instance = Name
        self.enabled = true
        self.nextAttack = 0
        self.windUp = 0
        self.Destination = 0
        self.AutoAttackAlias = {
            ["frostarrow"] = true,
            ["CaitlynHeadshotMissile"] = true,
            ["QuinnWEnhanced"] = true,
            ["TrundleQ"] = true,
            ["XenZhaoThrust"] = true,
            ["XenZhaoThrust2"] = true,
            ["XenZhaoThrust3"] = true,
            ["GarenSlash2"] = true,
            ["RenektonExecute"] = true,
            ["RenektonSuperExecute"] = true,
            ["KennenMegaProc"] = true
        }
        self.NonAttack = {
             ["shyvanadoubleattackdragon"] = true,
             ["ShyvanaDoubleAttack"] = true,
             ["MonkeyKingDoubleAttack"] = true
        }
        self.ResetSpell = {
            ["Powerfist"] = true,
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
            ["HecarimRamp"] = true,
            ["XenZhaoComboTarget"] = true,
            ["LeonaShieldOfDaybreak"] = true,
            ["ShyvanaDoubleAttack"] = true,
            ["shyvanadoubleattackdragon"] = true,
            ["TalonNoxianDiplomacy"] = true,
            ["TrundleTrollSmash"] = true,
            ["VolibearQ"] = true,
            ["PoppyDevastatingBlow"] = true,
            ["SivirW"] = true
        }
        self.BaseAttackSpeed = { 
            ["Aatrox"] = 0.651,
            ["Ahri"] = 0.668,
            ["Akali"] = 0.694,
            ["Alistar"] = 0.625,
            ["Amumu"] = 0.638,
            ["Anivia"] = 0.625,
            ["Annie"] = 0.579,
            ["Ashe"] = 0.658,
            ["Blitzcrank"] = 0.625,
            ["Brand"] = 0.625,
            ["Caitlyn"] = 0.625,
            ["Cassiopeia"] = 0.647,
            ["Chogath"] = 0.625,
            ["Corki"] = 0.625,
            ["Darius"] = 0.6679,
            ["Diana"] = 0.625,
            ["DrMundo"] = 0.625,
            ["Draven"] = 0.679,
            ["Elise"] = 0.625,
            ["Evelynn"] = 0.625,
            ["Ezreal"] = 0.625,
            ["Fiddlestick"] = 0.625,
            ["Fiora"] = 0.672,
            ["Fizz"] = 0.658,
            ["Galio"] = 0.638,
            ["Gangplank"] = 0.651,
            ["Garen"] = 0.625,
            ["Gragas"] = 0.651,
            ["Graves"] = 0.625,
            ["Hecarim"] = 0.67,
            ["Heimerdinger"] = 0.625,
            ["Irelia"] = 0.665,
            ["Janna"] = 0.625,
            ["JarvanIV"] = 0.658,
            ["Jax"] = 0.638,
            ["Jayce"] = 0.658,
            ["Jinx"] = 0.625,
            ["Karma"] = 0.625,
            ["Karthus"] = 0.625,
            ["Kassadin"] = 0.64,
            ["Katarina"] = 0.658,
            ["Kayle"] = 0.638,
            ["Kennen"] = 0.69,
            ["Khazix"] = 0.668,
            ["Kogmaw"] = 0.665,
            ["LeBlanc"] = 0.625,
            ["LeeSin"] = 0.651,
            ["Leona"] = 0.625,
            ["Lissandra"] = 0.625,
            ["Lucian"] = 0.638,
            ["Lulu"] = 0.625,
            ["Lux"] = 0.625,
            ["Malphite"] = 0.638,
            ["Malzahar"] = 0.625,
            ["Maokai"] = 0.694,
            ["MasterYi"] = 0.679,
            ["MissFortune"] = 0.656,
            ["Mordekaiser"] = 0.694,
            ["Morgana"] = 0.625,
            ["Nami"] = 0.644,
            ["Nasus"] = 0.638,
            ["Nautilus"] = 0.613,
            ["Nidalee"] = 0.67,
            ["Nocturne"] = 0.668,
            ["Nunu"] = 0.625,
            ["Olaf"] = 0.694,
            ["Orianna"] = 0.658,
            ["Pantheon"] = 0.679,
            ["Poppy"] = 0.638,
            ["Quinn"] = 0.668,
            ["Rammus"] = 0.625,
            ["Renekton"] = 0.665,
            ["Rengar"] = 0.679,
            ["Riven"] = 0.625,
            ["Rumble"] = 0.644,
            ["Ryze"] = 0.625,
            ["Sejuani"] = 0.67,
            ["Shaco"] = 0.694,
            ["Shen"] = 0.651,
            ["Shyvana"] = 0.658,
            ["Singed"] = 0.613,
            ["Sion"] = 0.625,
            ["Sivir"] = 0.658,
            ["Skarner"] = 0.625,
            ["Sona"] = 0.644,
            ["Soraka"] = 0.625,
            ["Swain"] = 0.625,
            ["Syndra"] = 0.625,
            ["Talon"] = 0.668,
            ["Taric"] = 0.625,
            ["Teemo"] = 0.69,
            ["Thresh"] = 0.625,
            ["Tristana"] = 0.656,
            ["Trundle"] = 0.67,
            ["Tryndamere"] = 0.67,
            ["TwistedFate"] = 0.651,
            ["Twitch"] = 0.679,
            ["Udyr"] = 0.658,
            ["Urgot"] = 0.644,
            ["Varus"] = 0.658,
            ["Vayne"] = 0.658,
            ["Veigar"] = 0.625,
            ["Vi"] = 0.644,
            ["Viktor"] = 0.625,
            ["Vladimir"] = 0.658,
            ["Volibear"] = 0.658,
            ["Warwick"] = 0.679,
            ["MonkeyKing"] = 0.658,
            ["Xerath"] = 0.625,
            ["XinZhao"] = 0.672,
            ["Yasuo"] = 0.658,
            ["Yorick"] = 0.625,
            ["Zac"] = 0.638,
            ["Zed"] = 0.638,
            ["Ziggs"] = 0.656,
            ["Zilean"] = 0.625,
            ["Zyra"] = 0.625
        }
        self.ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, myHero.range+50, DAMAGE_PHYSICAL)
        self.combatInstance = CombatHandler(self.ts)

        self.config = scriptConfig("[SALib]: Orbwalking", "saorbwalking")   
        self.config:addParam("orbwalk", "Orbwalk", SCRIPT_PARAM_ONKEYDOWN, false, 32)
        self.config:addParam("enabled", "Enable orbwalking", SCRIPT_PARAM_ONOFF, true)
        self.config:addParam("followMouse", "Follow the mouse", SCRIPT_PARAM_ONOFF, true)
        self.config:addParam("drawRange", "Draw auto attack range", SCRIPT_PARAM_ONOFF, true)
        self.config:addParam("drawLagfree", "Use lagfree circles", SCRIPT_PARAM_ONOFF, true)

        AddTickCallback(function() self:OnTick() end)
        AddProcessSpellCallback(function(unit, spell) self:OnProcessSpell(unit, spell) end)
        AddDrawCallback(function() self:OnDraw() end)
        AddSendPacketCallback(function(obj) self:OnSendPacket(packet) end)
    end

    function Orbwalker:__type()
        return 'Orbwalker'
    end

    function Orbwalker.Instance(Name)
        if Orbwalker.instance == '' then
            Orbwalker.instance = DefaultLoad(Name)
        end

        return Orbwalker.instance
    end

    function Orbwalker:OnTick()
        if not self.enabled or not self.config.enabled or not self.config.orbwalk then return end

        if self:CheckThirdParty() then
            PrintChat("<font color='#CCCCCC'>MMA, SOW or Reborn were found as active scripts. The build-in orbwalker will be disabled now.</font>")
            self.enabled = false
        end
        local target = self.combatInstance:GetTarget()
        local destination = myHero + (Vector(mousePos) - myHero):normalized()*300
        local distance = GetDistanceSqr(myHero, target)
        local trueRange = self.combatInstance:GetTrueRange(target)
        if ValidTarget(target) and GetGameTimer() > self.windUp then
            if distance <= 400*400 and GetGameTimer() < self.nextAttack then
                if GetInventoryItemIsCastable(3077) then
                    CastItem(3077)
                    self.nextAttack = 0
                elseif GetInventoryItemIsCastable(3074) then
                    CastItem(3074)
                    self.nextAttack = 0
                end
            end
            if distance*distance <= trueRange and GetGameTimer() > self.nextAttack then
                myHero:Attack(target)
            elseif self.config.followMouse then
                Packet('S_MOVE', {x = destination.x, y = destination.z}):send(true)
            end
        elseif self.config.followMouse and (not ValidTarget(target) or distance*distance > trueRange) then
            Packet('S_MOVE', {x = destination.x, y = destination.z}):send(true)
        end
    end

    function Orbwalker:OnDraw()
        if self.config.drawRange and not myHero.dead then
            if self.config.drawLagfree then
                _G.DrawCircle2(myHero.x, myHero.y, myHero.z, myHero.range, ARGB(255, 191, 247, 84))
            else
                DrawCircle(myHero.x, myHero.y, myHero.z, myHero.range, ARGB(255, 191, 247, 84))
            end

        end
    end

    function Orbwalker:OnProcessSpell(unit, spell)
        if unit.isMe then
            if (spell.name:lower():find("attack") or self.AutoAttackAlias[spell.name] and not self.NonAttack[spell.name]) then
                self.target = spell.target
                self.windUp = GetGameTimer() + spell.windUpTime
                self.windUpTime = spell.windUpTime
                self.nextAttack =  GetGameTimer() + (spell.animationTime * ((myHero.attackSpeed * self.BaseAttackSpeed[myHero.charName])) / myHero.attackSpeed)
            elseif self.ResetSpell[spell.name] then
                self.nextAttack = 0
            elseif spell.name == "SummonerExhaust" and spell.target.networkID == myHero.networkID then
                self.nextAttack = self.nextAttack * 1.5
            elseif spell.name == "Wither" and spell.target.networkID == myHero.networkID then
                self.nextAttack = self.nextAttack * 1.35
            end
        end
    end

    function Orbwalker:OnSendPacket(packet)
        -- We could block attacks here
    end

    function Orbwalker:CheckThirdParty()
        return _G.MMA_Loaded or _G.AutoCarry or _G.SOWLoaded
    end

class 'SAUpdater'

    SAUpdater.instance = ''

    function SAUpdater:__init(Name)
        self.LocalVersion = 0
        self.SCRIPT_NAME = ""
        self.SCRIPT_URL = ""
        self.PATH = ""
        self.HOST = ""
        self.URL_PATH = ""
        self.NeedUpdate = false
        self.NeedRun = true
        self.instance = Name
    end

    function SAUpdater:__type()
        return 'Updater'
    end

    function SAUpdater.Instance(Name)
        if self.instance == '' then
            self.instance = SAUpdater(Name)
        end

        return self.instance
    end

    function SAUpdater:Run()
        if self.LocalVersion ~= 0 and self.SCRIPT_NAME ~= "" and self.SCRIPT_URL ~= "" and self.PATH ~= "" and self.HOST ~= "" and self.URL_PATH ~= "" then
            AddTickCallback(function() self:OnTick() end)
        else
            PrintChat("You missed variables. Won't start the update class.")
        end
    end

    function SAUpdater:OnTick()
        if self.NeedRun then
            self.NeedRun = false
            GetAsyncWebResult(self.HOST, self.URL_PATH, function(Data)
                local OnlineVersion = tonumber(Data)

                if type(OnlineVersion) ~= "number" then return end
                if OnlineVersion and self.LocalVersion and OnlineVersion > self.LocalVersion then
                    PrintChat("<font color='#CCCCCC'>[SA] Updater: There is a new version of "..self.SCRIPT_NAME..". Don't F9 till done...</font>") 
                    self.NeedUpdate = true
                end
            end)
        end

        if self.NeedUpdate then
            self.NeedUpdate = false
            DownloadFile(self.SCRIPT_URL, self.PATH, function()
                if FileExist(self.PATH) then
                    PrintChat("<font color='#CCCCCC'>[SA] Updater: "..self.SCRIPT_NAME.." updated! Double F9 to use new version!</font>")
                end
            end)
        end
    end

--Other functions 

function _G.DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
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

function _G.DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75)    
    end
end

function CastOnObject(Spell, Object)
    Packet("S_CAST", {spellId = Spell, targetNetworkId = Object.networkID}):send()
end

function _G.RoundUp(number)
    return math.floor(number)+1
end

---------------------------- END OF LIB-----------------------------------------
local LibUpdate = SAUpdater("LibUpdate") 
local version = 1.09

LibUpdate.LocalVersion = version
LibUpdate.SCRIPT_NAME = "SALib" 
LibUpdate.SCRIPT_URL = "https://raw.githubusercontent.com/BotHappy/bh-scripts/master/Common/SALib.lua" 
LibUpdate.PATH = BOL_PATH.."Scripts\\Common\\".."SALib.lua" 
LibUpdate.HOST = "raw.githubusercontent.com" 
LibUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/REV/LibRev.lua" 
LibUpdate:Run()