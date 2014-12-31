<?php exit() ?>--by iuser99 98.119.108.50
local function abs(k) if k < 0 then return -k else return k end end
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then return end
if tostring == nil or tostring(220) ~= "220" then return end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then return end
last1 = tonumber(string.sub(tostring(GetUser), 11), 16)
last2 = tonumber(string.sub(tostring(GetAsyncWebResult), 11), 16)
last3 = tonumber(string.sub(tostring(CastSpell), 11), 16)
local function rawset3(table, value, id) end
local function protect(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
--overload check (addresses should be almost equal)
if _G.GetAsyncWebResult == nil or _G.GetUser == nil or _G.CastSpell == nil then print("Error processing.") return end
local a1 = tonumber(string.sub(tostring(_G.GetAsyncWebResult), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
if abs(a2-a1) > 500000 and abs(a3-a2) > 500000 then print("Error processing.") return end
if abs(a2-a1) < 500 and abs(a3-a2) < 500 then print("Error processing.") return end

Menu.instance = Menu(myHero.charName, myHero.charName) 

class 'ObjectWatcher' -- {

	ObjectWatcher_Created = 0 
	ObjectWatcher_Deleted = 1
	
	function ObjectWatcher:__init() 
		self.list = {}
		AddCreateObjCallback(function(object) self:OnCreateObj(object) end)
		AddDeleteObjCallback(function(object) self:OnDeleteObj(object) end)
	end 

	function ObjectWatcher:Add(object_name, callback_function)
		local func = callback_function or function() end 
		self.list[object_name] = {object = nil, callback = func}
	end 

	function ObjectWatcher:OnCreateObj(object)
		if object and object.valid then 
			if self.list[object.name] then 
				self.list[object.name].object = object 
				self.list[object.name].callback(ObjectWatcher_Created, object) 
			end 
		end 
	end 

	function ObjectWatcher:OnDeleteObj(object)
		if self.list[object.name] then 
			self.list[object.name].object = nil 
			self.list[object.name].callback(ObjectWatcher_Deleted, object) 
		end 
	end 

	function ObjectWatcher:GetObject(object_name)
		return self.list[object_name]
	end 
-- }

class 'Champion' -- {

	function Champion:__init(name, damage_type, casters, custom_load)
		self.name = name 
		self.casters = casters 
		self.damage_type = damage_type
		self.custom_load = custom_load or function() end 
	end 
	
-- }

class 'Champions' -- {
	
	function Champions:__init() 
		self.list = {}
	end 

	function Champions:Add(champion) 
		self.list[champion.name] = {instance = champion, damage_type = champion.damage_type, casters = champion.casters, name = champion.name, custom_load = champion.custom_load} 
	end 

	function Champions:GetChampion() 
		return self.list[myHero.charName]
	end 

	function Champions:GetCasters() 
		return self.list[myHero.charName].casters
	end 

-- }

local champion_list = Champions()
local object_watcher = ObjectWatcher() 

-- { Champion Skills 

	-- [[ Aatrox ]] -- 
	if myHero.charName == "Aatrox" then 
		local AatroxQ = Caster(_Q, 800, SPELL_CIRCLE, 1800, 0.270, 80)
		local AatroxW = Caster(_W, 300, SPELL_SELF)
		local AatroxE = Caster(_E, 1000, SPELL_CONE, 1600, 0.270, 30)
		local AatroxR = Caster(_R, 300, SPELL_SELF)

		champion_list:Add(Champion("Aatrox", DAMAGE_PHYSICAL, { AatroxQ, AatroxW, AatroxE, AatroxR }, function() 
				AatroxW:UpdateCondition(function()
					local spellName = myHero:GetSpellData(_W).name
					return (myHero.health < myHero.maxHealth * 0.60 and spellName == "aatroxw2") or (myHero.health > myHero.maxHealth * 0.75 and spellName == "AatroxW")
				end) 
			end))
	end  

	-- [[ Ahri ]] -- 
	if myHero.charName == "Ahri" then 
		local AhriQ = Caster(_Q, 880, SPELL_LINEAR, 1600, 0.25, 50)
		local AhriW = Caster(_W, 800, SPELL_SELF)
		local AhriE = Caster(_E, 975, SPELL_LINEAR_COL, 1250, 0.25, 50)
		local AhriR = Caster(_R, 1000, SPELL_LINEAR, 2000, 0.25, 100)

		champion_list:Add(Champion("Ahri", DAMAGE_MAGIC, { AhriQ, AhriW, AhriE, AhriR })) 
	end 

	-- [[ Anivia ]] -- TODO: Fix Ultimate casting 
	if myHero.charName == "Anivia" then 
		local AniviaQ = Caster(_Q, 1100, SPELL_LINEAR, 860.05, 0.250, 110, true)
		local AniviaW = Caster(_W, 1000, SPELL_CIRCLE, math.huge, 0, 200, true) 
		local AniviaE = Caster(_E, 700, SPELL_TARGETED)
		local AniviaR = Caster(_R, 615, SPELL_CIRCLE, math.huge, 0, 200, true)

		champion_list:Add(Champion("Anivia", DAMAGE_MAGIC, { AniviaQ, AniviaW, AniviaE, AniviaR }, function() 
				object_watcher:Add("cryo_FlashFrost_mis.troy")
				object_watcher:Add("cryo_storm") 

				AniviaQ:UpdateCondition(function(Target)
						local missile = object_watcher:GetObject("cryo_FlashFrost_mis.troy")
						return missile.object == nil 
					end)

				local function SecondQ_Condition() 
					local missile = object_watcher:GetObject("cryo_FlashFrost_mis.troy")
					for k, v in pairs(GetEnemyHeroes()) do 
						if v and v.valid and not v.dead and missile.object then
							if GetDistance(v, missile.object) < 50 then 
								return true 
							end  
						end 
					end 
					return false 
				end 

				local function SecondQ_Cast() 
					CastSpell(_Q)
				end 

				AniviaQ:RegisterSecondaryCast(SecondQ_Cast, SecondQ_Condition)
			end))
	end 

	-- [[ Darius ]] --
	if myHero.charName == "Darius" then 
		local enemy_table = GetEnemyHeroes() 

		for i, enemy in pairs(enemy_table) do 
			enemy_table[i].hemo = { tick = 0, count = 0}
		end 

		local hemoTable = {
	        [1] = "darius_hemo_counter_01.troy",
	        [2] = "darius_hemo_counter_02.troy",
	        [3] = "darius_hemo_counter_03.troy",
	        [4] = "darius_hemo_counter_04.troy",
	        [5] = "darius_hemo_counter_05.troy",
		}

		for i, v in ipairs(hemoTable) do
			object_watcher:Add(v, function(type, instance) 
					if type == ObjectWatcher_Created then 
						for d, enemy in pairs(enemy_table) do 
							if GetDistance(enemy, instance) <= 50 then 
								enemy.hemo.count = i 
								enemy.hemo.tick = GetTickCount() 
								PrintChat(enemy.charName .. " " .. enemy.hemo.count)
								break 
							end 
						end 
					end 
				end) 
		end

		local DariusQ = Caster(_Q, 425, SPELL_SELF)
		local DariusW = Caster(_W, 145, SPELL_SELF)
		local DariusE = Caster(_E, 540, SPELL_LINEAR_COL, math.huge, 0, 100, true)
		local DariusR = Caster(_R, 460, SPELL_TARGETED)
		
		DariusR:UpdateDamage(function(Target) 
				return getDmg("R", Target, myHero) * (enemy_table.hemo.count * 0.2)  
			end)

		champion_list:Add(Champion("Darius", DAMAGE_PHYSICAL, { DariusQ, DariusW, DariusE, DariusR }), 
			function()
				AddTickCallback(function() 
					for i, enemy in pairs(enemy_table) do 
						if enemy and (enemy.dead or (GetTickCount() - enemy.hemo.tick > 5000)) then 
							enemy.hemo.count = 0 
						end 
					end 
				end)
			end) 
	end 

	-- [[ Elise ]] --
	if myHero.charName == "Elise" then 

		function IsSpider() 
			return myHero:GetSpellData(_R).name == "EliseRSpider" 
		end 

		local EliseQ = Caster(_Q, 625, SPELL_TARGETED)
		local EliseW = Caster(_W, 950, SPELL_LINEAR_COL, math.huge, 0, 100, true)
		local EliseE = Caster(_E, 1075, SPELL_LINEAR_COL, 1450, 0.250, 50, true)
		local EliseR = Caster(_R, math.huge, SPELL_SELF)

		EliseR:UpdateCondition(function() 
				return not EliseQ:Ready() and not EliseW:Ready() and not EliseE:Ready() 
			end)

		champion_list:Add(Champion("Elise", DAMAGE_MAGIC, { EliseQ, EliseW, EliseE, EliseR }, 
			function() 

				local function SetSpells(form) 
					if form == true then 
						EliseQ.range = 475 
						EliseW:UpdateCast(function(Target) if ValidTarget(Target, 300) then CastSpell(_W) end end)
						EliseE:UpdateCast(function(Target) if ValidTarget(Target, 1000) then CastSpell(_E, Target) end end)
						return true 
					end 
					EliseQ.range = 625 
					EliseW.cast_function = nil 
					EliseE.cast_function = nil 
					return true 
				end 

				AddTickCallback(function() 
					SetSpells(isSpider())
				end)
			end)) 
	end 

	-- [[ Evelynn ]] --
	if myHero.charName == "Evelynn" then 
		local EvelynnQ = Caster(_Q, 500, SPELL_SELF)
		local EvelynnE = Caster(_E, 225, SPELL_TARGETED)
		local EvelynnR = Caster(_R, 650, SPELL_CIRCLE, math.huge, 0, 0)

		EvelynnR:UpdateCast(function(Target)
				local p = GetAoESpellPosition(350, Target)
				if p and GetDistance(p) <= EvelynnR.range then
					CastSpell(_R, p.x, p.z)
				end
			end)

		champion_list:Add(Champion("Evelynn", DAMAGE_MAGIC, { EvelynnQ, EvelynnE, EvelynnR })) 
	end 

	-- [[ Ezreal ]] -- 
	if myHero.charName == "Ezreal" then 
		local EzrealQ = Caster(_Q, 1100, SPELL_LINEAR_COL, 2000, 0.251, 80)
		local EzrealW = Caster(_W, 1050, SPELL_LINEAR, 1600, 0.250, 100)
		local EzrealE = Caster(_E, 475, SPELL_TARGET)
		local EzrealR = Caster(_R, 2000, SPELL_LINEAR, 1700, 1.0, 100)

		EzrealE:UpdateCast(function(Target) 
				CastSpell(_E, mousePos.x, mousePos.z)
			end) 

		champion_list:Add(Champion("Ezreal", DAMAGE_PHYSICAL, { EzrealQ, EzrealW, EzrealE, EzrealR })) 
	end 

	-- [[ Fiddlesticks ]] -- TODO: Fix orb walking
	if myHero.charName == "FiddleSticks" then 
		local FiddleSticksQ = Caster(_Q, 575, SPELL_TARGETED)
		local FiddleSticksW = Caster(_W, 475, SPELL_TARGETED)
		local FiddleSticksE = Caster(_E, 750, SPELL_TARGETED)
		local FiddleSticksR = Caster(_R, 800, SPELL_SELF)

		champion_list:Add(Champion("FiddleSticks", DAMAGE_MAGIC, { FiddleSticksQ, FiddleSticksW, FiddleSticksE, FiddleSticksR }))		
	end 

	-- [[ Fiora ]] -- 
	if myHero.charName == "Fiora" then 
		local FioraQ = Caster(_Q, 600, SPELL_TARGETED)
		local FioraW = Caster(_W, math.huge, SPELL_SELF)
		local FioraE = Caster(_E, math.huge, SPELL_SELF)
		local FioraR = Caster(_R, 400, SPELL_TARGETED)

		champion_list:Add(Champion("Fiora", DAMAGE_PHYSICAL, { FioraQ, FioraW, FioraE, FioraR }))
	end 	

	-- [[ Fizz ]] -- 
	if myHero.charName == "Fizz" then 
		local FizzQ = Caster(_Q, 550, SPELL_TARGETED)
		local FizzW = Caster(_W, 225, SPELL_SELF)
		local FizzE = Caster(_E, 400, SPELL_CIRCLE)
		local FizzR = Caster(_R, 1275, SPELL_LINEAR_COL, 1380, 1.38, 50, true) 

		champion_list:Add(Champion("Fizz", DAMAGE_MAGIC, { FizzQ, FizzW, FizzE, FizzR }))
	end 

	-- [[ Gangplank ]] -- 
	if myHero.charName == "Gangplank" then 
		local GangplankQ = Caster(_Q, 625, SPELL_TARGETED)
		local GangplankW = Caster(_W, 0, SPELL_SELF)
		local GangplankE = Caster(_E, 600, SPELL_SELF)
		local GangplankR = Caster(_R, math.huge, SPELL_CIRCLE, math.huge, 0, 300) 

		champion_list:Add(Champion("Gangplank", DAMAGE_PHYSICAL, { GangplankQ, GangplankW, GangplankE, GangplankR }))
	end 

	-- [[ Garen ]] -- 	
	if myHero.charName == "Garen" then 
		local GarenQ = Caster(_Q, math.huge, SPELL_SELF)
		local GarenW = Caster(_W, math.huge, SPELL_SELF)
		local GarenE = Caster(_E, 200, SPELL_SELF)
		local GarenR = Caster(_R, 400, SPELL_TARGETED)

		champion_list:Add(Champion("Garen", DAMAGE_PHYSICAL, { GarenQ, GarenW, GarenE, GarenR }, 
			function() 
				object_watcher:Add("Garen_Base_E_Spin.troy")

				GarenE:UpdateCondition(function(Target)
						local spin = object_watcher:GetObject("Garen_Base_E_Spin.troy")
						return spin.object == nil 
					end)
			end))
	end 

	-- [[ Graves ]] -- 
	if myHero.charName == "Graves" then 
		local GravesQ = Caster(_Q, 950, SPELL_LINEAR, 2150, 0.218, 200)
		local GravesW = Caster(_W, 950, SPELL_CIRCLE)
		local GravesE = Caster(_E, 425, SPELL_LINEAR)
		local GravesR = Caster(_R, 1000, SPELL_LINEAR, 2250, 0.234, 150)

		champion_list:Add(Champion("Graves", DAMAGE_PHYSICAL, { GravesQ, GravesW, GravesE, GravesR }))
	end 

	-- [[ Hecarim ]] --
	if myHero.charName == "Hecarim" then 
		local HecarimQ = Caster(_Q, 325, SPELL_SELF)
		local HecarimW = Caster(_W, 525, SPELL_SELF)
		local HecarimE = Caster(_E, 500, SPELL_SELF)
		local HecarimR = Caster(_R, 1000, SPELL_CIRCLE, math.huge, 0, 150, true)

		champion_list:Add(Champion("Hecarim", DAMAGE_PHYSICAL, { HecarimQ, HecarimW, HecarimE, HecarimR }))
	end 

	-- [[ Irelia ]] -- 
	if myHero.charName == "Irelia" then 
		local IreliaQ = Caster(_Q, 650, SPELL_TARGETED)
		local IreliaW = Caster(_W, 700, SPELL_SELF)
		local IreliaE = Caster(_E, 650, SPELL_TARGETED)
		local IreliaR = Caster(_R, 1000, SPELL_LINEAR)

		champion_list:Add(Champion("Irelia", DAMAGE_PHYSICAL, { IreliaQ, IreliaW, IreliaE, IreliaR }))
	end 

	-- [[ JarvanIV ]] --
	if myHero.charName == "JarvanIV" then 
		local JarvanIVQ = Caster(_Q, 770, SPELL_LINEAR, math.huge, 0.200, 100, true)
		local JarvanIVW = Caster(_W, math.huge, SPELL_SELF)
		local JarvanIVE = Caster(_E, 830, SPELL_CIRCLE, math.huge, 0.200, 100, true)
		local JarvanIVR = Caster(_R, 650, SPELL_TARGETED)

		champion_list:Add(Champion("JarvanIV", DAMAGE_PHYSICAL, { JarvanIVQ, JarvanIVW, JarvanIVE, JarvanIVR }))
	end 

	-- [[ Karma ]] -- 
	if myHero.charName == "Karma" then 
		local KarmaQ = Caster(_Q, 1050, SPELL_LINEAR_COL, 1800, 0.250, 100, true) 
		local KarmaW = Caster(_W, 650, SPELL_TARGETED)
		local KarmaE = Caster(_E, 800, SPELL_TARGETED) 
		local KarmaR = Caster(_R, math.huge, SPELL_SELF) 

		KarmaR:UpdateCondition(function(Target)
				if (myHero.health / myHero.maxHealth) < (0.60) and KarmaW:Ready() then 
					return true 
				elseif KarmaQ:Ready() then 
					return true 
				end 
			end)

		champion_list:Add(Champion("Karma", DAMAGE_MAGIC, { KarmaQ, KarmaW, KarmaE, KarmaR }))
	end 

	-- [[ Karthus ]] -- 
	if myHero.charName == "Karthus" then 
		local KarthusQ = Caster(_Q, 875, SPELL_CIRCLE, 1750, 0.25)
		local KarthusW = Caster(_W, 1000, SPELL_CONE)
		local KarthusE = Caster(_E, 425, SPELL_SELF)
		local KarthusR = Caster(_R, math.huge, SPELL_SELF)

		champion_list:Add(Champion("Karthus", DAMAGE_MAGIC, { KarthusQ, KarthusW, KarthusE, KarthusR },
			function() 
				object_watcher:Add("Defile_glow.troy")

				KarthusE:UpdateCondition(function(Target)
						local circle = object_watcher:GetObject("Defile_glow.troy")
						return circle.object == nil 
					end)
			end))
	end 

	-- [[ Kayle ]] -- 
	if myHero.charName == "Kayle" then 
		local KayleQ = Caster(_Q, 650, SPELL_TARGETED)
		local KayleW = Caster(_W, 900, SPELL_TARGETED_FRIENDLY)
		local KayleE = Caster(_E, 525, SPELL_SELF)
		local KayleR = Caster(_R, 900, SPELL_TARGETED_FRIENDLY)

		KayleQ:UpdateCondition(function(Target)
				return GetDistance(Target, myHero) < KayleE.range 
			end)

		champion_list:Add(Champion("Kayle", DAMAGE_MAGIC, { KayleQ, KayleW, KayleE, KayleR }))
	end 

	-- [[ Khazix ]] -- 
	if myHero.charName == "Khazix" then 
		local KhazixQ = Caster(_Q, 325, SPELL_TARGETED)
		local KhazixW = Caster(_W, 1030, SPELL_LINEAR_COL, 1835, 0.225, 110, true)
		local KhazixE = Caster(_E, 900, SPELL_CIRCLE, math.huge, 0, 100, true)
		local KhazixR = Caster(_R, math.huge, SPELL_SELF)

		champion_list:Add(Champion("Khazix", DAMAGE_MAGIC, { KhazixQ, KhazixW, KhazixE, KhazixR }, 
			function() 

				local function CheckEvolution()
					if myHero:GetSpellData(_E).name == "khazixelong" then
					  	KhazixE.range = 900
				 	end 
				 	if myHero:GetSpellData(_Q).name == "khazixqlong" then
				  		KhazixQ.range = 375
				 	end 
				end 

				AddTickCallback(function() 
						CheckEvolution() 
					end)
			end))
	end 

	-- [[ Lux ]] -- 
	if myHero.charName == "Lux" then 
		local LuxQ = Caster(_Q, 1150, SPELL_LINEAR_COL, 1175, 0.250, 80)
		local LuxW = Caster(_W, 1075, SPELL_LINEAR, 1400, 0.250, 50)
		local LuxE = Caster(_E, 1100, SPELL_CIRCLE, 1300, 0.150, 275)
		local LuxR = Caster(_R, 3000, SPELL_LINEAR, math.huge, 0.700, 200)

		champion_list:Add(Champion("Lux", DAMAGE_MAGIC, { LuxQ, LuxW, LuxE, LuxR },
			function() 
				object_watcher:Add("LuxLightstrike_tar.troy")

				LuxE:UpdateCondition(function(Target)
						local circle = object_watcher:GetObject("LuxLightstrike_tar.troy")
						return circle.object == nil 
					end)

				local function CanCast_E() 
					local circle = object_watcher:GetObject("LuxLightstrike_tar.troy")
					if circle.object then 
						for i, enemy in pairs(GetEnemyHeroes()) do 
							if enemy and enemy.dead then 
								if GetDistance(enemy, circle.object) <= 275 then 
									CastSpell(_E) 
								end 
							end 
						end 
					end 
				end 

				AddTickCallback(function() 
						CanCast_E() 
					end)
			end))
	end 

	-- [[ Malzahar ]] -- 
	if myHero.charName == "Malzahar" then 
		local MalzaharQ = Caster(_Q, 900, SPELL_CIRCLE, 1400, 0.25)
		local MalzaharW = Caster(_W, 800, SPELL_CIRCLE, 2000, 0.25)
		local MalzaharE = Caster(_E, 650, SPELL_TARGETED) 
		local MalzaharR = Caster(_R, 700, SPELL_TARGETED) 

		MalzaharQ:UpdateCondition(function(Target)
				return not TargetHaveBuff("alzaharnethergraspsound", myHero)
			end)
		MalzaharW:UpdateCondition(function(Target)
				return not TargetHaveBuff("alzaharnethergraspsound", myHero)
			end)
		MalzaharE:UpdateCondition(function(Target)
				return not TargetHaveBuff("alzaharnethergraspsound", myHero)
			end)
		MalzaharR:UpdateCondition(function(Target)
				return not TargetHaveBuff("alzaharnethergraspsound", myHero)
			end)

		champion_list:Add(Champion("Malzahar", DAMAGE_MAGIC, { MalzaharQ, MalzaharW, MalzaharE, MalzaharR }))
	end 

	-- [[ Maokai ]] -- 
	if myHero.charName == "Maokai" then 
		local MaokaiQ = Caster(_Q, 600, SPELL_LINEAR) 
		local MaokaiW = Caster(_W, 650, SPELL_TARGETED)
		local MaokaiE = Caster(_E, 1100, SPELL_CIRCLE)
		local MaokaiR = Caster(_R, 625, SPELL_CIRCLE, math.huge, 0, 1150)

		champion_list:Add(Champion("Maokai", DAMAGE_MAGIC, { MaokaiQ, MaokaiW, MaokaiE, MaokaiR }))
	end 

	-- [[ MonkeyKing ]] -- 
	if myHero.charName == "MonkeyKing" then 
		local MonkeyKingQ = Caster(_Q, 200, SPELL_SELF)
		local MonkeyKingW = Caster(_W, math.huge, SPELL_SELF)
		local MonkeyKingE = Caster(_E, 625, SPELL_TARGETED)
		local MonkeyKingR = Caster(_R, 162, SPELL_SELF)

		champion_list:Add(Champion("MonkeyKing", DAMAGE_PHYSICAL, { MonkeyKingQ, MonkeyKingW, MonkeyKingE, MonkeyKingR }))
	end 

	-- [[ Mordekaiser ]] --
	if myHero.charName == "Mordekaiser" then
		local MordekaiserQ = Caster(_Q, 200, SPELL_SELF)
		local MordekaiserW = Caster(_W, 750, SPELL_TARGETED_FRIENDLY)
		local MordekaiserE = Caster(_E, 700, SPELL_CONE)
		local MordekaiserR = Caster(_R, 850, SPELL_TARGETED)

		local Mordekaiser_R_Delay = 0 

		MordekaiserR:UpdateCast(function(Target)
				local ghost = myHero:GetSpellData(_R).name == "mordekaisercotgguide"
				if ghost and GetTickCount() >= Mordekaiser_R_Delay then 
					Mordekaiser_R_Delay = GetTickCount() + 1000 
				end 
				CastSpell(_R, Target) 
			end)

		champion_list:Add(Champion("Mordekaiser", DAMAGE_MAGIC, { MordekaiserQ, MordekaiserW, MordekaiserE, MordekaiserR }))
	end 

	-- [[ Nasus ]] -- 
	if myHero.charName == "Nasus" then 
		local NasusQ = Caster(_Q, 225, SPELL_SELF)
		local NasusW = Caster(_W, 700, SPELL_TARGETED)
		local NasusE = Caster(_E, 650, SPELL_CIRCLE)
		local NasusR = Caster(_R, 300, SPELL_SELF)

		local NasusQ_Stacks = 0

		champion_list:Add(Champion("Nasus", DAMAGE_PHYSICAL, { NasusQ, NasusW, NasusE, NasusR }, 
			function() 
				AddRecvPacketCallback(function(p)
						if p.header == 0xFE and p.size == 0xC then
				                p.pos = 1
				                pNetworkID = p:DecodeF()
				                unk01 = p:Decode2()
				                unk02 = p:Decode1()
				                stack = p:Decode4()
				 
				                if pNetworkID == myHero.networkID then
				                        NasusQ_Stacks = stack
				                end
				        end
					end)
				AddTickCallback(function() 
						NasusQ:UpdateDamage(function(Target)
								return getDmg("Q", Target, myHero) + myHero:CalcDamage(Target, NasusQ_Stacks)
							end)
					end)
			end))
	end 

	-- [[ Nautilus ]] -- 
	if myHero.charName == "Nautilus" then 
		local NautilusQ = Caster(_Q, 1030, SPELL_LINEAR_COL, 2000, 0.250, 100, true)
		local NautilusW = Caster(_W, 700, SPELL_SELF)
		local NautilusE = Caster(_E, 600, SPELL_SELF)
		local NautilusR = Caster(_R, 850, SPELL_TARGET)

		champion_list:Add(Champion("Nautilus", DAMAGE_MAGIC, { NautilusQ, NautilusW, NautilusE, NautilusR }))
	end 

	-- [[ Nidalee ]] -- 
	if myHero.charName == "Nidalee" then 
		local NidaleeQ = Caster(_Q, 1500, SPELL_LINEAR_COL, 1300, 0.100, 60, true)
		local NidaleeW = Caster(_W, 900, SPELL_CIRCLE, math.huge, 0.900, 80, true)
		local NidaleeE = Caster(_E, 600, SPELL_TARGETED_FRIENDLY)
		local NidaleeR = Caster(_R, 400, SPELL_SELF) 

		local Nidalee_wayPointManager = WayPointManager()

		local Niadlee_waypoint_current, Nidalee_waypoint_distance, Nidalee_waypoint_last = nil, nil, nil

		function isCougar() 
			return myHero:GetSpellData(_Q).name == "Takedown" 
		end 

		champion_list:Add(Champion("Nidalee", DAMAGE_PHYSICAL, { NidaleeQ, NidaleeW, NidaleeE, NidaleeR }, 
			function()

				function SetSpells(form) 
					if form == true then 
						NidaleeQ:UpdateCast(function(Target)
								if GetDistance(Target, myHero) < 375 then 
									CastSpell(_Q)
								end 
							end)
						NidaleeW:UpdateCast(function(Target)
								if GetDistance(Target, myHero) < 225 then 
									CastSpell(_Q)
								end 
							end)
						NidaleeE:UpdateCast(function(Target)
								if GetDistance(Target, myHero) < 300 then 
									CastSpell(_Q)
								end 
							end)
						return true
					end 
					NidaleeQ.cast_function = nil 
					NidaleeW.cast_function = nil 
					NidaleeE.cast_function = nil
					return true 
				end 

				function Travel()
					Nidalee_waypoint_distance = GetDistance(Nidalee_wayPointManager:GetWayPoints(player)[#Nidalee_wayPointManager:GetWayPoints(player)])
					Nidalee_waypoint_current = Nidalee_wayPointManager:GetWayPoints(player)[#Nidalee_wayPointManager:GetWayPoints(player)]
					if Nidalee_waypoint_distance > 5000 then 
						Nidalee_waypoint_last = Nidalee_waypoint_current
					elseif Nidalee_waypoint_distance < 5000 and Nidalee_waypoint_last ~= Nidalee_waypoint_current then 
						Nidalee_waypoint_last = nil 
					end 

					if Nidalee_waypoint_last == Nidalee_waypoint_current then 
						if not isCougar() then 
							CastSpell(_R)
						else 
							CastSpell(_W)
						end 
					end 
				end 

				NidaleeR:UpdateCondition(function(Target)
					if isCougar() then 
						return GetDistance(Target, myHero) > 500 
					end 
					return GetDistance(Target, myHero) < 500 
				end)

				AddTickCallback(
					function() 
						SetSpells(isCougar()) 
						Travel() 
					end) 
			end))
	end 

	-- [[ Nocturne ]] -- 	
	if myHero.charName == "Nocturne" then 
		local NocturneQ = Caster(_Q, 1200, SPELL_LINEAR, 1398, 0.249, 50, true) 
		local NocturneW = Caster(_W, math.huge, SPELL_SELF)
		local NocturneE = Caster(_E, 425, SPELL_TARGETED)	

		champion_list:Add(Champion("Nocturne", DAMAGE_MAGIC, { NocturneQ, NocturneW, NocturneE }))
	end 

	-- [[ Olaf ]] -- 
	if myHero.charName == "Olaf" then 
		local OlafQ = Caster(_Q, 1000, SPELL_LINEAR, 1600, 0.3, 75, true)
		local OlafW = Caster(_W, 225, SPELL_SELF)
		local OlafE = Caster(_E, 250, SPELL_TARGETED)
		local OlafR = Caster(_R, 300, SPELL_SELF)

		champion_list:Add(Champion("Olaf", DAMAGE_PHYSICAL, { OlafQ, OlafW, OlafE, OlafR }))
	end 

	-- [[ Pantheon ]] -- 
	if myHero.charName == "Pantheon" then 
		local PantheonQ = Caster(_Q, 600, SPELL_TARGETED)
		local PantheonW = Caster(_W, 600, SPELL_TARGETED)
		local PantheonE = Caster(_E, 225, SPELL_CONE, math.huge, 0, 100, true)

		champion_list:Add(Champion("Pantheon", DAMAGE_PHYSICAL, { PantheonQ, PantheonW, PantheonE }))
	end 

	-- [[ Renekton ]] -- 
	if myHero.charName == "Renekton" then 
		local RenektonQ = Caster(_Q, 225, SPELL_SELF)
		local RenektonW = Caster(_W, 225, SPELL_SELF)
		local RenektonE = Caster(_E, 450, SPELL_LINEAR)
		local RenektonR = Caster(_R, math.huge, SPELL_SELF)

		champion_list:Add(Champion("Renekton", DAMAGE_PHYSICAL, { RenektonQ, RenektonW, RenektonE, RenektonR }))
	end 

	-- [[ Shen ]] -- 
	if myHero.charName == "Shen" then 
		local ShenQ = Caster(_Q, 475, SPELL_TARGETED)
		local ShenE = Caster(_E, 1000, SPELL_LINEAR_COL, 1603, 0.187, 110, true)
		local ShenW = Caster(_W, 200, SPELL_TARGETED_FRIENDLY)
		local ShenR = Caster(_R, 18500, SPELL_TARGETED_FRIENDLY)

		champion_list:Add(Champion("Shen", DAMAGE_PHYSICAL, { ShenQ, ShenE, ShenW, ShenR }))
	end 

	-- [[ Shyvana ]] -- 
	if myHero.charName == "Shyvana" then 
		local ShyvanaQ = Caster(_Q, 200, SPELL_SELF)
		local ShyvanaW = Caster(_W, 300, SPELL_SELF)
		local ShyvanaE = Caster(_E, 950, SPELL_LINEAR, 1750, 0, 50, true) 
		local ShyvanaR = Caster(_R, 1000, SPELL_LINEAR)

		champion_list:Add(Champion("Shyvana", DAMAGE_PHYSICAL, { ShyvanaQ, ShyvanaW, ShyvanaE, ShyvanaR }))
	end 

	-- [[ Sivir ]] -- 
	if myHero.charName == "Sivir" then 
		local SivirQ = Caster(_Q, 1200, SPELL_LINEAR, 330, 0.25, 100, true)
		local SivirW = Caster(_W, math.huge, SPELL_SELF)
		local SivirE = Caster(_E, math.huge, SPELL_SELF)
		local SivirR = Caster(_R, 300, SPELL_SELF)

		champion_list:Add(Champion("Sivir", DAMAGE_PHYSICAL, { SivirQ, SivirW, SivirE, SivirR }))
	end 

	-- [[ Swain ]] -- 
	if myHero.charName == "Swain" then 
		local SwainQ = Caster(_Q, 625, SPELL_TARGETED)
		local SwainW = Caster(_W, 900, SPELL_CIRCLE, math.huge, 0.700, 270, true)
		local SwainE = Caster(_E, 625, SPELL_TARGETED) 
		local SwainR = Caster(_R, 700, SPELL_SELF)

		champion_list:Add(Champion("Swain", DAMAGE_PHYSICAL, { SwainQ, SwainW, SwainE, SwainR }, 
			function()
				object_watcher:Add("swain_demonForm")

				SwainR:UpdateCondition(function(Target)
						local crow = object_watcher:GetObject("swain_demonForm")
						return crow.object == nil 
					end)
			end))
	end 

	-- [[ Talon ]] -- 
	if myHero.charName == "Talon" then 
		local TalonQ = Caster(_Q, 300, SPELL_SELF)
		local TalonW = Caster(_W, 700, SPELL_CONE, 2000, 0.250, 200, true)
		local TalonE = Caster(_E, 700, SPELL_TARGETED)
		local TalonR = Caster(_R, 200, SPELL_SELF)

		champion_list:Add(Champion("Talon", DAMAGE_PHYSICAL, { TalonQ, TalonW, TalonE, TalonR }))
	end 

	-- [[ Tristana ]] -- 
	if myHero.charName == "Tristana" then 
		local TristanaQ = Caster(_Q, math.huge, SPELL_SELF)
		local TristanaW = Caster(_W, 900, SPELL_CIRCLE, math.huge, 0, 100, true)
		local TristanaE = Caster(_E, 650, SPELL_TARGETED)
		local TristanaR = Caster(_R, 645, SPELL_TARGETED)

		champion_list:Add(Champion("Tristana", DAMAGE_PHYSICAL, { TristanaQ, TristanaW, TristanaE, TristanaR }))
	end 

	-- [[ TwistedFate ]] -- 
	if myHero.charName == "TwistedFate" then 
		local Cards = {
			["Blue"] = {
				troy = "Card_Blue.troy",
				lock = "bluecardlock",
			},
			["Yellow"] = {
				troy = "Card_Yellow.troy",
				lock = "goldcardlock"
			},
			["Red"] = {
				troy = "Card_Red.troy",
				lock = "redcardlock"
			},
			["None"] = {troy="", lock=""}
		}

		function PickCard(card)
			if card then
				if myHero:GetSpellData(_W).name == "PickACard" then CastSpell(_W) end
				if myHero:GetSpellData(_W).name == card.lock then 
					CastSpellEx(_W) 
				end 
			end 
		end 

		local TwistedFateQ = Caster(_Q, 1450, SPELL_CONE, 1450, 0.2, 90)

		champion_list:Add(Champion("TwistedFate", DAMAGE_MAGIC, { TwistedFateQ },
			function() 
				AddTickCallback(function() 

						if Menu.Get("AutoFunctions").LaneClear then 
							if (myHero.mana / myHero.maxMana) < (70 / 100) then 
								PickCard(Cards["Blue"])
							else
								PickCard(Cards["Red"])
							end 
						end 

						if Menu.Get("CustomizableCombo").Active then 
							if Calculation.CountSurrounding(myHero, 225, GetEnemyHeroes()) >= 3 then 
								PickCard(Cards["Red"]) 
							elseif Menu.useYellow then
								PickCard(Cards["Yellow"])
							end 
						end 

					end)
			end))
	end 

	-- [[ Veigar ]] -- 
	if myHero.charName == "Veigar" then 
		local VeigarQ = Caster(_Q, 650, SPELL_TARGETED)
		local VeigarW = Caster(_W, 900, SPELL_CIRCLE, 1500, 1.35, 185, true)
		local VeigarE = Caster(_E, 800, SPELL_CIRCLE, math.huge, 0, 600, true)
		local VeigarR = Caster(_R, 650, SPELL_TARGETED)

		champion_list:Add(Champion("Veigar", DAMAGE_MAGIC, { VeigarQ, VeigarW, VeigarE, VeigarR }))
	end 

	-- [[ Vladimir ]] -- 
	if myHero.charName == "Vladimir" then 
		local VladimirQ = Caster(_Q, 600, SPELL_TARGETED)
		local VladimirW = Caster(_W, math.huge, SPELL_SELF)
		local VladimirE = Caster(_E, 600, SPELL_SELF)
		local VladimirR = Caster(_R, 700, SPELL_CIRCLE)

		champion_list:Add(Champion("Vladimir", DAMAGE_MAGIC, { VladimirQ, VladimirW, VladimirE, VladimirR }))
	end 

	-- [[ Yorick ]] -- 
	if myHero.charName == "Yorick" then 
		local YorickQ = Caster(_Q, 200, SPELL_SELF)
		local YorickW = Caster(_W, 600, SPELL_CIRCLE, math.huge, 0.250, 200)
		local YorickE = Caster(_E, 550, SPELL_TARGETED)
		local YorickR = Caster(_R, 850, SPELL_TARGETED_FRIENDLY)

		champion_list:Add(Champion("Yorick", DAMAGE_PHYSICAL, { YorickQ, YorickW, YorickE, YorickR }))
	end 
--} 

class 'Authorization' -- {

	local State = 0 
	_G.Init = false 
	_G.S = nil 
	
	function Authorization:__init()
		self.private_key = "2B851FF5F62B1344"
		local file_load = self:Load() 
		if file_load ~= nil then 
			self:Check(file_load)
			return
		end 
		self.text = GetClipboardText() 
		self:Check(self.text) 
	end 

	function Authorization:Check(API_KEY)  
		local text = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. os.getenv("USERNAME") .. os.getenv("COMPUTERNAME") .. os.getenv("PROCESSOR_LEVEL") .. os.getenv("PROCESSOR_REVISION"))
		local hwid = self:url_encode(text) 

		GetAsyncWebResult("iuser99.com", "auth.php?key=" .. self.private_key .. "&user=" .. GetUser() .. "&hwid=" .. hwid .. "&apikey=" .. API_KEY .. "&app=1", function(result) self:CheckResult(result) end)
	end 

	function Authorization:Load() 
		if FileExist(SCRIPT_PATH .. "iuser_serial.key") then 
			local file = io.open(SCRIPT_PATH .. "iuser_serial.key")
			local file_contents = file:read('*a')
			local result = self:descifrar(file_contents, "iapp") 
			file:close() 
			return result
		end 
		return nil  
	end 

	function Authorization:Save(key) 
		local file = io.open(SCRIPT_PATH .. "iuser_serial.key", "w" )
		local a = self:cifrar(key,"iapp")
	    file:write(tostring(a))
	    file:close()
	end 

	function Authorization:split(pString, pPattern)
	    local Table = {}
	    local fpat = "(.-)" .. pPattern
	    local last_end = 1
	    local s, e, cap = pString:find(fpat, 1)
	    while s do
	        if s ~= 1 or cap ~= "" then
	            table.insert(Table,cap)
	        end
	        last_end = e+1
	        s, e, cap = pString:find(fpat, last_end)
	    end
	    if last_end <= #pString then
	        cap = pString:sub(last_end)
	        table.insert(Table, cap)
	    end
	    return Table
	end

	function Authorization:url_encode(str)
		if (str) then
	  		str = string.gsub (str, "\n", "\r\n")
	    	str = string.gsub (str, "([^%w %-%_%.%~])",
	        function (c) return string.format ("%%%02X", string.byte(c)) end)
	    	str = string.gsub (str, " ", "+")
	  	end
	 	return str	
	end

	function Authorization:enc(data)
	    return ((data:gsub('.', function(x) 
	        local r,baseEnc='',x:byte()
	        for i=8,1,-1 do r=r..(baseEnc%2^i-baseEnc%2^(i-1)>0 and '1' or '0') end
	        return r;
	    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
	        if (#x < 6) then return '' end
	        local c=0
	        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
	        return baseEnc:sub(c+1,c+1)
	    end)..({ '', '==', '=' })[#data%3+1])
	end

	function Authorization:CheckResult(result) 
		if string.find(result, "Registered") then 
			self:Save(self.text)
			State = 1
			PrintChat("Registered.")
		elseif string.find(result, "TRUE") then 
			State = 1
			PrintChat("Loaded License!")
		elseif string.find(result, "invalid") then 
			PrintChat("Invalid API Key.")
		elseif string.find(result, "bad") then 
			PrintChat("Bad Login.")
		end 
	end 

	function Authorization:cifrar(str, pass)
		crypt = {}
		local seed = 0
		for i = 1, pass:len() do
			seed = seed + pass:byte(i) + i
		end
		math.randomseed(seed)
		for i = 1, str:len() do
			table.insert(crypt, string.char(str:byte(i) + math.random()*15))
		end
		return table.concat(crypt)
	end

	function Authorization:descifrar(str, pass)
	    uncry = {}
	    local seed = 0
	    for i = 1, pass:len() do
	        seed = seed + pass:byte(i) + i
	    end
	    math.randomseed(seed)
	    for i = 1, str:len() do
	        table.insert(uncry, string.char(str:byte(i) - math.random()*15))
	    end
	    return table.concat(uncry)
	end
-- }

class 'Script' (iScript) -- {
	
	function Script:__init() 
		iScript.__init(self)
		self.champion = champion_list:GetChampion() 
		if self.champion.custom_load ~= nil then 
			self.champion.custom_load() 
		end 
		iScript:Register(self.champion.name, self.champion.damage_type, self.champion.casters)
		AddTickCallback(function() self:OnTick() end)
	end 
	
	function Script:OnTick() 
		Target = iScript:GetTarget() 
		if Target then 
			iScript:CastCombo(Target) 
		end
	end 

-- }

local a = nil 

function OnLoad() 
	a = Authorization() 
end 

function OnTick() 
	if State == 0 then 
		return 
	elseif not Init then 
		S = Script() 
		Init = true 
	end  
end 