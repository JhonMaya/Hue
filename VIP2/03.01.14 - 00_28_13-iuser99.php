<?php exit() ?>--by iuser99 98.119.108.50
--[[
	
	iLibrary
	@Author iuser99
	@Version 0.01 
	
	Contents
	-- 

--]]

require "AoE_Skillshot_Position"
require "Prodiction"
require "Collision"

-- class 'Misc' -- {
	
	_G.SpellStrings = {
	    [_Q] = "Q", 
	    [_W] = "W",
	    [_E] = "E",
	    [_R] = "R"
	}

	_G.SpellString = {
	    ["Q"] = _Q, 
	    ["W"] = _W,
	    ["E"] = _E, 
	    ["R"] = _R 
	}

	_G.JungleMonsters = {
		["Vilemaw"] = "TT_Spiderboss7.1.1",
		["Nashor"]  = "Worm12.1.1",
		["Dragon"]  = "Dragon6.1.1",
		["Blue_1"]  = "AncientGolem1.1.1",
		["Blue_2"]  = "AncientGolem7.1.1",
		["Red_1"]   = "LizardElder4.1.1",
		["Red_2"]   = "LizardElder10.1.1",
	}

	_G.round = function(num)
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

	_G.copyPacket = function(packet)
	    packet.pos = 1
	    p = CLoLPacket(packet.header)
	    for i=1,packet.size-1,1 do
	    	p:Encode1(packet:Decode1())
	    end
	    p.dwArg1 = packet.dwArg1
	    p.dwArg2 = packet.dwArg2
	    return p
	end

	local SupportTable = {
		AD_Carry = {
			"Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
			"Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "Jinx"
		},
		Bruiser = {
			"Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
			"Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
		},
		Tank = {
			"Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
			"Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar",
		},
		AP = {
			"Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
			"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
			"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",
		},
		Support = {
			"Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
		},	
	}
-- }

--[[
	Class: Menu

	Static 
	- Menu.Instance  
	- SPELL_MENUS 
	- init_spells 
	- Get 
	
	Instanced 
	- _Get
	- _init_spells 
--]]
class 'Menu' -- {

    Menu.instance = ""

    function Menu.Instance() 
        if Menu.instance == "" then Menu.instance = Menu() end return Menu.instance 
    end 

    _G.SPELL_MENUS = {
        [_Q] = function() return self.Config.Q end,
        [_W] = function() return self.Config.W end,
        [_E] = function() return self.Config.E end,
        [_R] = function() return self.Config.R end
    }
    
    function Menu:__init(name, param)
        self.Config = scriptConfig("" .. name, "" .. param)
        self.spell_init = false
    end 

    function Menu:_Get(sub)
        return sub and self.Config[sub] or self.Config 
    end 

    function Menu.Get(sub)
        return Menu.Instance():_Get(sub)
    end 

    function Menu:_init_spells()
    	if not self.spell_init then 
    		self.Config:addSubMenu("Cast Settings: Q", "Q")
			self.Config:addSubMenu("Cast Settings: W", "W")
			self.Config:addSubMenu("Cast Settings: E", "E")
			self.Config:addSubMenu("Cast Settings: R", "R")
			self.spell_init = true 
		end
    end 

    function Menu.init_spells()
    	return Menu.Instance():_init_spells()
    end 

-- }

class 'Caster' -- {

    _G.prodiction_manager = ProdictManager.GetInstance() 

    _G.SPELL_TARGETED = 1
    _G.SPELL_LINEAR = 2
    _G.SPELL_CIRCLE = 3
    _G.SPELL_CONE = 4
    _G.SPELL_LINEAR_COL = 5
    _G.SPELL_SELF = 6
    _G.SPELL_TARGETED_FRIENDLY = 7 

    function Caster:__init(spell, range, spellType, speed, delay, width, useFastCollision)
    	Menu.init_spells()
    	self.spell = spell
		self.range = range or 0
		self.spellType = spellType or SPELL_SELF
		self.speed = speed or math.huge
		self.delay = delay or 0
		self.width = width or 100
		self.spellData = myHero:GetSpellData(spell)
		self.damage_function = function(Target)
			return getDmg(SpellStrings[self.spell], Target, myHero)
		end 
		self.cast_condition = function(Target) 
			return true 
		end 

		if spellType == SPELL_LINEAR or spellType == SPELL_CIRCLE or spellType == SPELL_LINEAR_COL then
			self.prodiction = prodiction_manager:AddProdictionObject(self.spell, self.range, self.speed, self.delay, self.width, self.source)
			self.collision = Collision(self.range, self.speed, self.delay, self.width + 10) 
			if useFastCollision or Menu.Get(SpellStrings[self.spell]).fastcol then 
				self.collision = FastCol(self.prodiction)
			end 
			self.prodiction_function = function(unit, pos, castspell)
					if GetDistance(unit) < self.range then 
						if spellType == SPELL_LINEAR_COL then 
							if not self.collision:GetMinionCollision(pos, self.source) then
								CastSpell(castspell.Name, pos.x, pos.z) 
							end 
						else 
							CastSpell(castspell.Name, pos.x, pos.z)
						end 
					end 
				end
		end 


		Menu.Get(SpellStrings[self.spell]):addParam("custmdesc", "-- Casting Options --", SCRIPT_PARAM_INFO, "")
		Menu.Get(SpellStrings[self.spell]):addParam("packetcast", "Cast using packets", SCRIPT_PARAM_ONOFF, false)
		Menu.Get(SpellStrings[self.spell]):addParam("fastcol", "Use Fast Collision (Requires Reload)", SCRIPT_PARAM_ONOFF, false)
    end 

    function Caster:__type()
    	return "Caster"
    end 

    function Caster:Cast(target)
		if myHero:CanUseSpell(self.spell) ~= READY then return false end
		if self.spellType == SPELL_SELF then
			CastSpell(self.spell)
			return true
		elseif self.spellType == SPELL_TARGETED then
			if ValidTarget(target, self.range) then
				if Menu.Get(SpellStrings[self.spell]).packetcast then 
					Caster.PacketCast(self.spell, target.networkID)
				else 
					CastSpell(self.spell, target)
				end 
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
			if self.prodiction and ValidTarget(target) then
				self.prodiction:GetPredictionCallBack(target, function(unit, pos, spell)
					self.prodiction_function(unit, pos, spell) 
				end)
				return true
			elseif target.team == myHero.team then
				CastSpell(self.spell, target.x, target.z)
				return true
			end 
		end
		return false
	end

	function Caster.PacketCast(spell, id)
		pE = CLoLPacket(0x9A)
		pE:EncodeF(myHero.networkID)
		pE:Encode1(spell)
		pE:EncodeF(mousePos.x)
		pE:EncodeF(mousePos.z)
		pE:EncodeF(0)
		pE:EncodeF(0)
		if id then
			pE:EncodeF(id)
		end
		pE.dwArg1 = 1
		pE.dwArg2 = 0
		SendPacket(pE)
	end 

	function Caster:UpdateDamage(func)
		self.damage_function = func 
	end 

	function Caster:GetDamage(Target)
		return self.damage_function(Target)  
	end 

	function Caster:UpdateCondition(func) 
		self.cast_condition = func 
	end 

	function Caster:Condition(Target) 
		return self.cast_condition(Target)
	end 

    function Caster:Ready()
        return myHero:CanUseSpell(self.spell) == READY
    end

-- }

class 'CustomizableCombo' -- {

	_G.COMBO_MODE_ORDER = 0 
	_G.COMBO_MODE_BURST = 1
	_G.COMBO_MODE_RANGE = 2

	function CustomizableCombo:__init(...) 	
		local args = {...}
		self.casters = args
		self.priority_table = {}
		self.last_order = {}
		self.combo_mode = COMBO_MODE_ORDER

		Menu.init_spells()
		Menu.Get():addSubMenu("Customize Combo", "CustomizableCombo")
		Menu.Get("CustomizableCombo"):addParam("Active", "Activate Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.Get("CustomizableCombo"):addParam("combo", "Combo Mode", SCRIPT_PARAM_SLICE, 0, 0, 2, 0)
		Menu.Get("CustomizableCombo"):addParam("desc", "", SCRIPT_PARAM_INFO, "nil")

		local i = 0
		local boolean_order = {}
		for k, v in pairs(args) do 
			i = i + 1
			boolean_order = {}
			for n=1, 4 do 
				if n == i then 
					table.insert(boolean_order, true)
				else
					table.insert(boolean_order, false)
				end 
			end 
			table.insert(self.priority_table, v) 
			self.last_order[v.spell] = boolean_order
			Menu.Get(SpellStrings[v.spell]):addParam("custmdesc", "-- Customize Combo --", SCRIPT_PARAM_INFO, "")
			Menu.Get(SpellStrings[v.spell]):addParam("cast1", "Cast First", SCRIPT_PARAM_ONOFF, boolean_order[1])
			Menu.Get(SpellStrings[v.spell]):addParam("cast2", "Cast Second", SCRIPT_PARAM_ONOFF, boolean_order[2])
			Menu.Get(SpellStrings[v.spell]):addParam("cast3", "Cast Third", SCRIPT_PARAM_ONOFF, boolean_order[3])
			Menu.Get(SpellStrings[v.spell]):addParam("cast4", "Cast Fourth", SCRIPT_PARAM_ONOFF, boolean_order[4])
			Menu.Get(SpellStrings[v.spell]):addParam("allin", "Only cast for killable", SCRIPT_PARAM_ONOFF, false)
			self.priority_table[v.spell] = {order = i}

			AddTickCallback(function() self:Update() end)
		end 

	end 

	function CustomizableCombo:GetCombo(Target)
		local result = {}
		if self.combo_mode == COMBO_MODE_ORDER then 
			for k, v in pairs(self.casters) do 
				for i=1, 4 do 
					if Menu.Get(SpellStrings[v.spell])["cast"..i] then 
						if Menu.Get(SpellStrings[v.spell]).allin and DamageCalculation.CalculateRealDamage(Target) > Target.health then 
							table.insert(result, v)
						elseif not Menu.Get(SpellStrings[v.spell]).allin then 
							table.insert(result, v)
						end
						break 
					end 
				end 
			end 
		elseif self.combo_mode == COMBO_MODE_BURST then 
			result = {}
			for k,v in pairs(self.casters) do 
				if Menu.Get(SpellStrings[v.spell]).allin and DamageCalculation.CalculateRealDamage(Target) > Target.health then 
					table.insert(result, v)
				elseif not Menu.Get(SpellStrings[v.spell]).allin then 
					table.insert(result, v)
				end
			end 
			table.sort(result, function(a,b) 
				return getDmg(SpellStrings[a.spell], Target, myHero) > getDmg(SpellStrings[b.spell], Target, myHero)
			end)
		elseif self.combo_mode == COMBO_MODE_RANGE then 
			result = {}
			for k,v in pairs(self.casters) do 
				if Menu.Get(SpellStrings[v.spell]).allin and DamageCalculation.CalculateRealDamage(Target) > Target.health then 
					table.insert(result, v)
				elseif not Menu.Get(SpellStrings[v.spell]).allin then 
					table.insert(result, v)
				end
			end 
			table.sort(result, function(a,b)
				return a.range > b.range 
			end)
		end 
		return result 
	end 

	function CustomizableCombo:Update() 
		self:Update_Priority()
		self:Update_Combo()
	end 

	function CustomizableCombo:Update_Combo() 
		local i = Menu.Get("CustomizableCombo").combo
		local text = "nil"
		if i == COMBO_MODE_ORDER then 
			self.combo_mode = COMBO_MODE_ORDER
			text = "Order"
		elseif i == COMBO_MODE_BURST then 
			self.combo_mode = COMBO_MODE_BURST
			text = "Burst"
		elseif i == COMBO_MODE_RANGE then 
			self.combo_mode = COMBO_MODE_RANGE
			text = "Range"
		end 
		Menu.Get("CustomizableCombo").desc = text
	end 

	function CustomizableCombo:Update_Priority()
		for k, v in pairs(self.casters) do 
			for i=1, 4 do 
				if Menu.Get(SpellStrings[v.spell])["cast"..i] then 
					if self.last_order[v.spell][i] ~= Menu.Get(SpellStrings[v.spell])["cast"..i] then  
						self.last_order[v.spell] = self:Disable(i, v.spell)
					end 
				end 
			end 
		end 
	end

	function CustomizableCombo:Disable(index, except) 
		local result = {}
		for i=1, 4 do 
			if i ~= index then 
				Menu.Get(SpellStrings[except])["cast"..i] = false
			end 
			table.insert(result, Menu.Get(SpellStrings[except])["cast"..i])
		end 
		for k,v in pairs(self.casters) do 
			if v.spell ~= except then 
				Menu.Get(SpellStrings[v.spell])["cast"..index] = false
			end 
		end 
		return result
	end 

-- }

class 'ObjectManager' -- {
	
	_G.OBJECT_TYPE_TURRET = 'obj_AI_Turrets'
	_G.OBJECT_TYPE_MINION = 'obj_AI_Minion'
	_G.OBJECT_TYPE_JUNGLE = 2 
	_G.OBJECT_TYPE_TROY   = 3 
	_G.OBJECT_TYPE_HERO   = 'obj_AI_Hero'

	_G.JungleMonsters = {
		["Vilemaw"] = "TT_Spiderboss7.1.1",
		["Nashor"] = "Worm12.1.1",
		["Dragon"] = "Dragon6.1.1",
		["Blue_1"] = "AncientGolem1.1.1",
		["Blue_2"] = "AncientGolem7.1.1",
		["Red_1"]  = "LizardElder4.1.1",
		["Red_2"]  = "LizardElder10.1.1",
	}

	ObjectManager.instance = ""

	function ObjectManager.Instance()
		if ObjectManager.instance == "" then ObjectManager.instance = ObjectManager() end return ObjectManager.instance
	end 

	function ObjectManager:__init()
		self.table = {}
		AddCreateObjCallback(function(object) self:OnCreateObj(object) end)
		AddDeleteObjCallback(function(object) self:OnDeleteObj(object) end)
	end 

	function ObjectManager:OnCreateObj(object)
		if object and object.valid then 
			for i, j in pairs(JungleMonsters) do 
				if j == object.name then 
					self.table[object.team][OBJECT_TYPE_JUNGLE] = {}
					table.insert(self.table[object.team][OBJECT_TYPE_JUNGLE], object)
					return 
				end 
			end 
			if self.table[object.team][object.type] == nil or type(self.table[object.team][object.type]) ~= "table" then 
				self.table[object.team][object.type] = {} 
			end 
			table.insert(self.table[object.team][object.type], object)
			PrintChat(object.name)
		end 
	end 

	function ObjectManager:OnDeleteObj(object)
		if object and object.valid then 
			--table.remove(self.table[object.team][object.type], Calculation.index(self.table[object.team][object.type], object))
		end 
	end 

	function ObjectManager:_Get(param)
		local filter = {}
		filter.team = param.team or Game.enemy_team()
		filter.type = param.type 

		return self.table[filter.team][filter.type]
	end 

	function ObjectManager.Get(param)
		return ObjectManager.Instance():_Get(param)
	end 
-- }


class 'AutoFunctions' -- {
	
	function AutoFunctions:__init(...)
		self.casters = {...}
		self.lasthit_last_selected = nil 
		self.lasthit_last_caster = nil
		self.autoshield_last_selected = nil 
		self.autoshield_last_caster = nil
		Menu.init_spells()

		Menu.Get():addSubMenu("Auto-Functions", "AutoFunctions")
		Menu.Get("AutoFunctions"):addParam("LastHit", "Activate Last Hit", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("R"))
		Menu.Get("AutoFunctions"):addParam("LaneClear", "Activate Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("E"))
		Menu.Get("AutoFunctions"):addParam("BuffSteal", "Activate Buff Stealer", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))

		self.enemyMinions = minionManager(MINION_ENEMY, 2000, player, MINION_SORT_HEALTH_ASC)
		self.jungleMinions = {}
		self.heal_conditions = {}
		self.heal_percentages = {}
		self.auto_shield = AutoShield() 

		self.auto_shield.caster = self.casters[1]

		for k, v in pairs(self.casters) do 
			Menu.Get(SpellStrings[v.spell]):addParam("autofunc1", "-- Auto-Functions: Options --", SCRIPT_PARAM_INFO, "")
			Menu.Get(SpellStrings[v.spell]):addParam("killsteal", "Use for killsteal", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("lasthit", "Use for last hitting", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("heal", "Use for healing", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("healamount", "Heal Percent", SCRIPT_PARAM_SLICE, 20, 0, 100, 0)
			Menu.Get(SpellStrings[v.spell]):addParam("autofunc1", "-- Auto-Functions: LaneClear --", SCRIPT_PARAM_INFO, "")
			Menu.Get(SpellStrings[v.spell]):addParam("laneclear", "Use for Lane Clear", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("mec", "Use MEC for skill (lane clear)", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("autofunc1", "-- Auto-Functions: Buff Steal --", SCRIPT_PARAM_INFO, "")
			Menu.Get(SpellStrings[v.spell]):addParam("usebuffstealer", "Use for stealing buffs", SCRIPT_PARAM_ONOFF, false)
			Menu.Get(SpellStrings[v.spell]):addParam("autofunc1", "-- Auto-Functions: AutoShield --", SCRIPT_PARAM_INFO, "")
			Menu.Get(SpellStrings[v.spell]):addParam("autoshield", "Use for auto shield", SCRIPT_PARAM_ONOFF, false)
			
			self.heal_percentages[v.spell] = function()
					return myHero.health / myHero.maxHealth
				end
			self.heal_conditions[v.spell] = function() 
					return v:Ready() and Menu.Get(SpellStrings[v.spell]).heal
				end 
		end 
		AddTickCallback(function() self:OnTick() end)
		AddCreateObjCallback(function(object) self:OnCreateObj(object) end)
		AddDeleteObjCallback(function(object) self:OnDeleteObj(object) end)

		for i = 1, objManager.maxObjects do
			local object = objManager:getObject(i) 
			if object and object.valid then 
				for i, j in pairs(JungleMonsters) do 
					if j == object.name then 
						PrintChat(object.name)
						self.jungleMinions[i] = object
					end 
				end
			end 
		end 
	end 

	function AutoFunctions:OnCreateObj(object)
		if object then 
			for i, j in pairs(JungleMonsters) do 
				if j == object.name then 
					PrintChat(object.name)
					self.jungleMinions[i] = object
				end 
			end 
		end 
	end 

	function AutoFunctions:OnDeleteObj(object)
		if object then 
			for i, j in pairs(JungleMonsters) do 
				if j == object.name then 
					PrintChat(object.name)
					self.jungleMinions[i] = nil
				end 
			end 
		end 
	end 

	function AutoFunctions:OnTick() 
		self.enemyMinions:update() 
		self:OnTick_Killsteal()
		self:OnTick_LastHit() 
		self:OnTick_LaneClear() 
		self:OnTick_BuffSteal() 
		self:OnTick_Heal() 
		self:OnTick_AutoShield() 
	end 

	function AutoFunctions:OnTick_Killsteal() 
		local caster, enemy = self:GetTarget_Killsteal() 
		if enemy and ValidTarget(enemy, caster.range) and caster:Ready() then 
			caster:Cast(enemy)
		end 
	end 

	function AutoFunctions:GetTarget_Killsteal() 
		for k, caster in pairs(self.casters) do 
			if caster:Ready() and Menu.Get(SpellStrings[caster.spell]).killsteal then 
				for _, enemy in pairs(GetEnemyHeroes()) do 
					if ValidTarget(enemy, caster.range) then 
						if getDmg(SpellStrings[caster.spell], enemy, myHero) > enemy.health then 
							return caster, enemy 
						end 
					end 
				end 
			end 
		end 
	end 

	function AutoFunctions:OnTick_LastHit() 
		local caster, minion = self:GetTarget_LastHit() 
		if minion and caster:Ready() and Menu.Get("AutoFunctions").LastHit then 
			caster:Cast(minion)
		end 
	end 

	function AutoFunctions:GetCaster_LastHit() 
		for _, caster in pairs(self.casters) do 
			if Menu.Get(SpellStrings[caster.spell]).lasthit then 
				if self.lasthit_last_selected ~= caster.spell then 
					self.lasthit_last_selected = caster.spell 
					self.lasthit_last_caster = caster
					self:DisableMenu_LastHit(self.lasthit_last_selected) 
					return caster 
				end 
			end 
		end 
		return self.lasthit_last_caster
	end 

	function AutoFunctions:DisableMenu_LastHit(except)
		for _, caster in pairs(self.casters) do 
			if Menu.Get(SpellStrings[caster.spell]).lasthit and caster.spell ~= except then 
				Menu.Get(SpellStrings[caster.spell]).lasthit = false
			end 
		end 
	end 

	function AutoFunctions:GetTarget_LastHit() 
		local caster = self:GetCaster_LastHit()
		for _, minion in pairs(self.enemyMinions.objects) do 
			if caster:Ready() and GetDistance(minion) < caster.range and getDmg(SpellStrings[caster.spell], minion, myHero) > minion.health then 
				return caster, minion
			end 
		end 
	end 

	function AutoFunctions:OnTick_LaneClear() 
		if not Menu.Get("AutoFunctions").LaneClear then return end
		for _, caster in pairs(self.casters) do 
			if caster:Ready() and Menu.Get(SpellStrings[caster.spell]).laneclear then
				local m = nil 
				if Menu.Get(SpellStrings[caster.spell]).mec then 
					m = Calculation.MinionMEC(self.enemyMinions.objects, caster.range, caster.width)
				else 
					m = Calculation.GetNearest(myHero, caster.range, self.enemyMinions.objects)
				end 

				if m then 
					CastSpell(caster.spell, m.x, m.z) 
				end 
			end 
		end 
	end 

	function AutoFunctions:OnTick_BuffSteal() 
		if not Menu.Get("AutoFunctions").BuffSteal then return end
		local buff = self:GetTarget_BuffSteal() 
		if buff then 
			local caster = self:GetCaster_BuffSteal(buff) 
			if caster then 
				return caster:Cast(buff) 
			end 
		end 
	end 

	function AutoFunctions:GetCaster_BuffSteal(Target) 
		local temp = self.casters 
		if #temp > 1 then 
			table.sort(temp, function(a,b)
				return a:GetDamage(Target) > b:GetDamage(Target)
			end)
		end 
		for _, c in ipairs(temp) do 
			if c:Ready() and GetDistance(Target) < c.range and c:GetDamage(Target) > Target.health then 
				return c 
			end 
		end 
		return nil 
	end 

	function AutoFunctions:GetTarget_BuffSteal() 
		local buffs = self.jungleMinions
		for _, b in pairs(buffs) do 
			if b and b.valid then 
				return b 
			end 
		end 
	end 

	function AutoFunctions:OnTick_Heal() 
		local caster, ally_use = self:GetCaster_Heal() 
		if caster then 
			if ally_use then 
				local target = iHealing.Instance():GetTarget(caster.range) 
				if target then 
					caster:Cast(target)
				end 
			else
				caster:Cast(myHero) 
			end  
		end 
	end 

	function AutoFunctions:GetCaster_Heal() 
		local result = self.casters
		table.sort(result, function(a, b) 
				return Menu.Get(SpellStrings[a.spell]).healamount > Menu.Get(SpellStrings[b.spell]).healamount
			end)
		for k,v in ipairs(result) do 
			local percentage = self.heal_percentages[v.spell]
			local condition = self.heal_conditions[v.spell]
			if condition() and (Menu.Get(SpellStrings[v.spell]).healamount / 100) > percentage() then 
				return v, (not (v.spellType == SPELL_SELF or v.spellType == SPELL_TARGETED_FRIENDLY))
			end 
		end 
	end 

	function AutoFunctions:OnTick_AutoShield() 
		local caster = self:GetCaster_AutoShield() 
		if caster then 
			self.auto_shield:SetCaster(caster) 
		end 
	end 

	function AutoFunctions:GetCaster_AutoShield() 
		for _, caster in pairs(self.casters) do 
			if Menu.Get(SpellStrings[caster.spell]).autoshield then 
				if self.autoshield_last_selected ~= caster.spell then 
					self.autoshield_last_selected = caster.spell 
					self.autoshield_last_caster = caster
					self:DisableMenu_AutoShield(self.autoshield_last_selected) 
					return caster 
				end 
			end 
		end 
		return self.autoshield_last_caster
	end 

	function AutoFunctions:DisableMenu_AutoShield(except)
		for _, caster in pairs(self.casters) do 
			if Menu.Get(SpellStrings[caster.spell]).autoshield and caster.spell ~= except then 
				Menu.Get(SpellStrings[caster.spell]).autoshield = false
			end 
		end 
	end 
-- }

class 'Calculation' -- {
   
   function Calculation.CountSurrounding(source, radius, list)
        local c = 0 
        for _, o in pairs(list) do 
            if o and o.valid and GetDistance(source, o) < radius then 
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

    function Calculation.FindNearestNonWall( x0, y0, z0, maxRadius, precision )
		if not IsWall(D3DXVECTOR3(x0, y0, z0)) then return D3DXVECTOR3(x0, y0, z0) end
	    local radius, gP = 1, precision or 50
	    x0, y0, z0, maxRadius = math.round(x0/gP)*gP, math.round(y0/gP)*gP, math.round(z0/gP)*gP, maxRadius and math.floor(maxRadius/gP) or math.huge
	    local function toGamePos(x, y) return x0+x*gP, y0, z0+y*gP end
		while radius<=maxRadius do
	        for i = 1, 4 do
	           local p = D3DXVECTOR3(toGamePos((i==2 and radius) or (i==4 and -radius) or 0,(i==1 and radius) or (i==3 and -radius) or 0))
	           if not IsWall(p) then return p end
	        end
	        local f, x, y = 1-radius, 0, radius
	        while x<y-1 do
	            x = x + 1
	            if f < 0 then f = f+1+x+x
	            else y, f = y-1, f+1+x+x-y-y end
	            for i=1, 8 do
	                local w = math.ceil(i/2)%2==0
	                local p = D3DXVECTOR3(toGamePos(((i+1)%2==0 and 1 or -1)*(w and x or y),(i<=4 and 1 or -1)*(w and y or x)))
	                if not IsWall(p) then return p end
	            end
	        end
	        radius = radius + 1
	    end
	end

	function Calculation.MinionMEC(m, range, radius)
		local minions = {}
		for _, minion in pairs(m) do
			if ValidTarget(minion, range) then
				table.insert(minions, minion)
			end
		end

		local minionClusters = {}

		local closeMinion = radius
		for _, minion in pairs(minions) do
			local foundCluster = false
			for i, mc in ipairs(minionClusters) do
				if GetDistance(mc, minion) < closeMinion then
					mc.x = ((mc.x * mc.count) + minion.x) / (mc.count + 1)
					mc.z = ((mc.z * mc.count) + minion.z) / (mc.count + 1)
					mc.count = mc.count + 1
					foundCluster = true
					break
				end
			end
	 
			if not foundCluster then
				local mc = {x=0, z=0, count=0}
				mc.x = minion.x
				mc.z = minion.z
				mc.count = 1
				table.insert(minionClusters, mc)
			end
		end

		if #minionClusters < 1 then return end

		local largestCluster = 0
		local largestClusterSize = 0
		for i, mc in ipairs(minionClusters) do
			if mc.count > largestClusterSize then
				largestCluster = i
				largestClusterSize = mc.count
			end
		end

		minionCluster = minionClusters[largestCluster]
		if minionCluster then
			minions = nil
			minionClusters = nil
			return minionCluster 
		end
	end 

	function Calculation.index(haystack, needle)
		for i=1, #haystack do 
			if haystack[i] == needle then
				return i
			end
		end 
	end 

-- }

class 'Game' -- {
	
	function Game.enemy_team()
		if myHero.team == TEAM_RED then
			return TEAM_BLUE
		end
		return TEAM_RED
	end 

-- }

class "Drawing" -- {

	Drawing.instance = ""

	function Drawing.Instance()
		if Drawing.instance == "" then Drawing.instance = Drawing() end return Drawing.instance
	end 

	function Drawing:__init()
		self.queue = {}
		Menu.Get():addSubMenu("Drawing", "Drawing")
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

	function Drawing:OnDraw()
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
			local target = AutoCarry.Crosshair:GetTarget()
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

class 'iHealing' -- {
	
	iHealing.instance = ""

	function iHealing.Instance()
		if iHealing.instance == "" then iHealing.instance = iHealing() end return iHealing.instance
	end 

	function iHealing:__init()
		self.allies = {}
		for i=1, heroManager.iCount, 1 do 
			local player = heroManager:GetHero(i)
			if player and player.team == myHero.team then 
				table.insert(self.allies, player)
			end 
		end 
	end 

	function iHealing:Weigh(hero)
		local weight = 0 

		weight = weight + ((hero.maxHealth - hero.health) * 5) 
		weight = weight + (hero.totalDamage * 4)
		weight = weight - (GetDistance(hero) * 2) 

		for i=1, #SupportTable, 1 do 
			local sub = SupportTable[i]
			for _, h in pairs(sub) do 
				if hero.charName == h then 
					weight = weight * (10 - i)
					break
				end 
			end 
		end 

		return weight 
	end 

	function iHealing:Sorting(allies)
		table.sort(allies, function(a, b) 
				return self:Weigh(a) > self:Weigh(b)
			end)
		return allies
	end 

	function iHealing:GetTarget(range)
		local allies = {}
		for i, ally in pairs(self.allies) do 
			if ally and not ally.dead and GetDistance(ally) < range then 
				table.insert(allies, ally)
			end 
		end 
		self:Sorting(allies)
		for i, ally in pairs(allies) do 
			if ally and not ally.dead and GetDistance(ally) < range then 
				return ally 
			end 
		end 
	end 

-- }

class "DamageCalculation" -- {
    _G.items = { -- Item Aliases for spellDmg lib, including their corresponding itemID's.
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

class 'AutoShield' -- {

	AvoidList = {
	-- AOE
		["UFSlash"] = 300,
		["GragasExplosiveCask"] = 400,
		["CurseoftheSadMummy"] = 550,
		["LeonaSolarFlare"] = 400,
		["InfernalGuardian"] = 250,
		["DianaVortex"] = 300,
		["RivenMartyr"] = 200,
		["OrianaDetonateCommand"] = 400,
		["DariusAxeGrabCone"] = 200,
		["LeonaZenithBladeMissile"] = 200,
		["ReapTheWhirlwind"] = 600,
		["ShenShadowDash"] = 350,
		["GalioIdolOfDurand"] = 600,
		["XenZhaoParry"] = 200,
		["EvelynnR"] = 400,
		["Pulverize"] = 250,
		["VladimirHemoplague"] = 200,
	-- Target
		["Headbutt"] = 0,
		["Dazzle"] = 0,
		["CrypticGaze"] = 0,
		--["Pantheon_LeapBash"] = 0,
		["RenektonPreExecute"] = 0,
		["IreliaEquilibriumStrike"] = 0,
		["MaokaiUnstableGrowth"] = 0,
		["BusterShot"] = 0,
		["BlindMonkRKick"] = 0,
		["VayneCondemn"] = 0,
		["SkarnerImpale"] = 0,
		["ViR"] = 0,
		["Terrify"] = 0,
		["IceBlast"] = 0,
		["NullLance"] = 0,
		["PuncturingTaunt"] = 0,
		["BlindingDart"] = 0,
		["VeigarPrimordialBurst"] = 0,
		["DeathfireGrasp"] = 0,
		["GarenJustice"] = 0,
		["DariusExecute"] = 0,
		["ZedUlt"] = 0,
		--["PickaCard_yellow_mis.troy"] = 0,
		["RunePrison"] = 0,
		["PoppyHeroicCharge"] = 0,
		["AlZaharNetherGrasp"] = 0,
		["InfiniteDuress"] = 0,
		["UrgotSwap2"] = 0,
		["TalonCutthroat"] = 0,
		["LeonaShieldOfDaybreakAttack"] = 0,
	}

	local PlayerSkills = {
		"Q", "W", "E", "R", "P", "QM", "WM", "EM"
	}

	local OnHitItems = {
		["KITAES"] = 3186,
		["MALADY"] = 3114,
		["WITSEND"] = 3091,
		["SHEEN"] = 3057,
		["TRINITY"] = 3078,
		["LICHBANE"] = 3100,
		["ICEBORN"] = 3025,
		["STATIKK"] = 3087,
		["RUINEDKING"] = 3153,
		["SPIRITLIZARD"] = 3209
	}

	local SkillOnHitItems = {
		["LIANDRYS"] = 3151,
		["BLACKFIRE"] = 3188,
		["SPIRITLIZARD"] = 3209
	}
	
	function AutoShield:__init() 
		Menu.init_spells() 
		Menu.Get():addSubMenu("Auto Shield", "AutoShield")
		Menu.Get("AutoShield"):addParam("userselection", "Block only selected spells", SCRIPT_PARAM_ONOFF, false)
		Menu.Get("AutoShield"):addSubMenu("Targeted Spells", "Targeted")
		Menu.Get("AutoShield"):addSubMenu("Skillshots", "Skillshots")

		for k, v in pairs(AvoidList) do 
			if v == 0 then 
				Menu.Get("AutoShield").Targeted:addParam(""..k, k, SCRIPT_PARAM_ONOFF, false)
			else 
				Menu.Get("AutoShield").Skillshots:addParam(""..k, k, SCRIPT_PARAM_ONOFF, false)
			end 
		end

		Menu.Get("AutoShield"):addSubMenu("Ally Settings", "Ally")	

		for i=1, heroManager.iCount do 
			local p = heroManager:GetHero(i)
			if p and p.team == myHero.team then
			     Menu.Get("AutoShield").Ally:addParam(p.charName .. "desc", "", SCRIPT_PARAM_INFO, "")
				 Menu.Get("AutoShield").Ally:addParam(p.charName .. "shield", "Shield " .. p.charName, SCRIPT_PARAM_ONOFF, false)
				 Menu.Get("AutoShield").Ally:addParam(p.charName .. "percent", "Damage to shield", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
			end 
		end 

		Menu.Get("AutoShield"):addSubMenu("Self Settings", "Self")
		Menu.Get("AutoShield").Self:addParam("shield", "Shield myself", SCRIPT_PARAM_ONOFF, false)
		Menu.Get("AutoShield").Self:addParam("onlyaa", "Only Shield AA", SCRIPT_PARAM_ONOFF, false)
		Menu.Get("AutoShield").Self:addParam("percent", "Damage to shield", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)


		self.caster = nil 
		self.callbacks = {}

		AddProcessSpellCallback(function(obj, spell) self:OnProcessSpell(obj, spell) end)	
	end 

	function AutoShield:GetCaster() 
		return self.caster 
	end 

	function AutoShield:SetCaster(caster)
		self.caster = caster
	end 

	function AutoShield:RegisterCallback(func) 
		table.insert(self.callbacks, {callback = func})
	end 

	function AutoShield:FireCallbacks(unit, spell, target) 
		for k, v in pairs(self.callbacks) do
			v:callback(unit, spell, target)
		end
	end 

	function AutoShield:OnProcessSpell(unit, spell) 
		if (unit == nil or spell == nil or self.caster == nil) then return end 
		if unit.team ~= nil and unit.team ~= myHero.team then
			self.spellType, self.castType = getSpellType(unit, spell.name)
			for i=1, heroManager.iCount do 
				local p = heroManager:GetHero(i) 
				if p and p.team == myHero.team then 
					if self.SpellWillHit(unit, spell, p) then 
						if p.isMe and AvoidList[spell.name] == nil then 
							self:AddSpell(spell)
						end
						if ((self:IsEnabled(p, spell) and Menu.Get("AutoShield").userselection) or not Menu.Get("AutoShield").userselection) and self:IsApplicable(unit, spell, p) then 
							self.caster:Cast(p) 
							self:FireCallbacks(unit, spell, p)
						end 
					end 
				end 
			end 
		end 
	end 

	function AutoShield:AddSpell(spell) 
		if spell.target ~= nil then 
			Menu.Get("AutoShield").Targeted:addParam(spell.name, spell.name, SCRIPT_PARAM_ONOFF, false)
		else 
			Menu.Get("AutoShield").Skillshots:addParam(spell.name, spell.name, SCRIPT_PARAM_ONOFF, false)
		end 
		--PrintChat("AutoShield: Unknown spell detected, added to options.")
	end 

	function AutoShield:IsApplicable(source, spell, target) 
		local damage_percent = AutoShield.GetSpellDamage(source, spell, target) * 100 / target.health
		if target.isMe then 
			if (self.spellType == "BAttack" or self.spellType == "CAttack") and Menu.Get("AutoShield").Self.onlyaa then 
				return damage_percent > Menu.Get("AutoShield").Self.percent and Menu.Get("AutoShield").Self.shield
			end 
			return damage_percent > Menu.Get("AutoShield").Self.percent and Menu.Get("AutoShield").Self.shield and not Menu.Get("AutoShield").Self.onlyaa
		end 
		return damage_percent > Menu.Get("AutoShield").Ally[target.charName .. "percent"] and Menu.Get("AutoShield").Ally[target.charName .. "shield"]
	end 

	function AutoShield:IsEnabled(object, spell) 
		if spell.target ~= nil and spell.target == object then 
			return Menu.Get("AutoShield").Targeted[spell.name] 
		end 
		return Menu.Get("AutoShield").Skillshots[spell.name]
	end

	function AutoShield.GetSpellDamage(object, spell, target) 
		if object == nil or spell == nil or target == nil then return 0 end 
		local attackDamage = object:CalcDamage(target, object.totalDamage)
		local spellType, castType = getSpellType(object, spell.name)
		if object.type ~= "obj_AI_Hero" then
			if spell.name:find("BasicAttack") then
				return attackDamage 
			elseif spell.name:find("CritAttack") then 
				return attackDamage * 2 
			end 
		else
			local itemDamage = 0
			for name, id in pairs(OnHitItems) do 
				if GetInventoryHaveItem(id, object) then
					itemDamage = itemDamage + getDmg(name, target, object)
				end 
			end 
			if spellType == "BAttack" then
				return (attackDamage + itemDamage) * 1.07
			elseif spellType == "CAttack" then 
				local ie = 0
				if GetInventoryHaveItem(3031,object) then ie = .5 end 
				return (attackDamage * (2.1 + ie) + itemDamage) * 1.07
			elseif PlayerSkills[spellType] ~= nil then
				local skillDamage, skillType = 0, 0
				local skillItemDamage = 0
				for name, id in pairs(SkillOnHitItems) do 
					if GetInventoryHaveItem(id, object) then 
						skillItemDamage = skillItemDamage + getDmg(name, target, object)
					end 
				end 

				skillDamage, skillType = getDmg(spellType, target, object, castType, spell.level)

				if skillType ~= 2 then
					return (skillDamage + skillItemDamage) * 1.07
				end 
				return (skillDamage + attackDamage + itemDamage + skillItemDamage) * 1.07
			end 						
		end
		return attackDamage 
	end 

	function AutoShield.SpellWillHit(object, spell, target) 
		if object == nil then return false end 
		local hitchampion = false
		local spellType, castType = getSpellType(object, spell.name)
		if object.type == "obj_AI_Hero" and (spellType == "BAttack" or spellType == "CAttack") then
			hitchampion = GetDistance(target, spell.endPos) < 80 
		elseif spellType == "Q" or spellType == "W" or spellType == "E" or spellType =="R" then
			local shottype = skillData[object.charName][spellType]["type"]
			local radius = skillData[object.charName][spellType]["radius"]
			local maxdistance = skillData[object.charName][spellType]["maxdistance"]
			local P2 = spell.endPos 
			if shottype == 0 then hitchampion = checkhitaoe(object, P2, 80, target, 0)
			elseif shottype == 1 then hitchampion = checkhitlinepass(object, P2, radius, maxdistance, target, 50)
			elseif shottype == 2 then hitchampion = checkhitlinepoint(object, P2, radius, maxdistance, target, 50)
			elseif shottype == 3 then hitchampion = checkhitaoe(object, P2, radius, maxdistance, target, 50)
			elseif shottype == 4 then hitchampion = checkhitcone(object, P2, radius, maxdistance, target, 50)
			elseif shottype == 5 then hitchampion = checkhitwall(object, P2, radius, maxdistance, target, 50)
			elseif shottype == 6 then hitchampion = checkhitlinepass(object, P2, radius, maxdistance, target, 50) or checkhitlinepass(object, Vector(object)*2-P2, radius, maxdistance, target, 50)
			elseif shottype == 7 then hitchampion = checkhitcone(P2, object, radius, maxdistance, target, 50)
			end
		end  
		return hitchampion
	end 
-- }


class 'iScript' -- {
	
	function iScript:__init() 
		AddTickCallback(function() self:Update() end)
	end 

	function iScript:CreateTS(name, damage_type)
		self.ts = TargetSelector(TARGET_PRIORITY, 2000, damage_type, true)
		self.ts.name = name 
	end 

	function iScript:Create_CustomizableCombo(...)
		self.custom_combo = CustomizableCombo(table.unpack({...}))
	end 

	function iScript:Create_AutoFunctions(...)
		self.auto_functions = AutoFunctions(table.unpack({...}))
	end 

	function iScript:Register(name, damage_type, t)
		self:CreateTS(name, damage_type)
		self:Create_CustomizableCombo(table.unpack(t)) 
		self:Create_AutoFunctions(table.unpack(t)) 
		PrintChat("Loaded >> iScript: " .. name)
		PrintChat("Remember, these are BETAs. Please report all bugs to iuser99")
	end 

	function iScript:CastCombo(Target) 
		if Target then 
			if Menu.Get("CustomizableCombo").Active then 
				local combo = self.custom_combo:GetCombo(Target)
				if combo then 
					for _, c in ipairs(combo) do 
						if Target.dead or not Target.valid then break end 
						if not Menu.Get("CustomizableCombo").Active then break end
						if c:Ready() and ValidTarget(Target, c.range) and c:Condition(Target) then 
							c:Cast(Target)
						end 
					end 
				end 
			end 
		end 
	end 

	function iScript:Update() 
		self:Update_Selector() 
	end 

	function iScript:Update_Selector() 
		if self.ts then 
			self.ts:update() 
		end 
	end 

	function iScript:GetTarget() 
		return self.ts.target
	end 

-- }