<?php exit() ?>--by ahmedzarga23 197.1.228.95
	--[[Encrypt All Below.]]
	if os.time() > os.time{year=2014, month=6, day=30, hour=0, sec=1} then
		printMessage("A fail safe has disabled the script, contact the Author for access.")
	end
	printMessage("Loaded")
	if os.time() > os.time{year=2014, month=5, day=22, hour=0, sec=1} and os.time() < os.time{year=2014, month=5, day=23, hour=0, sec=1} then
	    printMessage("It's Pain's Birthday, wish him a happy birthday!")
    end

--{ Initiate Data Load
	local Kassadin = {
		Q = {range = 600, DamageType = _MAGIC, BaseDamage = 80, DamagePerLevel = 25, ScalingStat = _MAGIC, PercentScaling = _AP, Condition = 0.7, Extra = function() return (myHero:CanUseSpell(_Q) == READY) end},
		W = {range = myHero.range + 50, DamageType = _MAGIC, BaseDamage = 40, DamagePerLevel = 25, ScalingStat = _MAGIC, PercentScaling = _AP, Condition = 0.6, Extra = function() return (myHero:CanUseSpell(_W) == READY) end},
		E = {range = 630, speed = math.huge, delay = 0.5, width = 150, collision = false, DamageType = _MAGIC, BaseDamage = 80, DamagePerLevel = 25, ScalingStat = _MAGIC, PercentScaling = _AP, Condition = 0.7, Extra = function() return (myHero:CanUseSpell(_E) == READY) end},
		R = {range = 700, speed = math.huge, delay = 0.5, width = 270, collision = false, DamageType = _MAGIC, BaseDamage = 80, DamagePerLevel = 20, ScalingStat = _MAGIC, PercentScaling = _AP, Condition = 0, Extra = function() return (myHero:CanUseSpell(_R) == READY) end}
	}
--}
--{ Script Load
	function OnLoad()
		--{ Variables
			for i = 1, heroManager.iCount, 1 do
	        	local enemy = heroManager:getHero(i)
	      		if enemy.team ~= myHero.team and enemy.charName == "Blitzcrank" then
	            	Blitzcrank = enemy
	            	break
	       		end
	        end
			VP = VPrediction(true)
			OW = SOW(VP)
			OW:RegisterAfterAttackCallback(AutoAttackReset)
			TS = SimpleTS(STS_LESS_CAST_MAGIC)
			Selector.Instance()
			RSTACK = 0 
			RECALL = false
			SpellQ = Spell(_Q, Kassadin.Q["range"], true)
			SpellW = Spell(_W, Kassadin.W["range"], true)
			SpellE = Spell(_E, Kassadin.E["range"], true):SetSkillshot(VP, SKILLSHOT_CIRCULAR, Kassadin.E["width"], Kassadin.E["delay"], Kassadin.E["speed"], Kassadin.E["collision"])
			SpellR = Spell(_R, Kassadin.R["range"], false):SetSkillshot(VP, SKILLSHOT_CIRCULAR, Kassadin.R["width"], Kassadin.R["delay"], Kassadin.R["speed"], Kassadin.R["collision"])
			EnemyMinions = minionManager(MINION_ENEMY, Kassadin.Q["range"], myHero, MINION_SORT_MAXHEALTH_DEC)
		--}
		--{ DamageCalculator
			DamageCalculator = DamageLib()
			DamageCalculator:RegisterDamageSource(_Q, Kassadin.Q["DamageType"], Kassadin.Q["BaseDamage"], Kassadin.Q["DamagePerLevel"], Kassadin.Q["ScalingStat"], Kassadin.Q["PercentScaling"], Kassadin.Q["Condition"], Kassadin.Q["Extra"])
			DamageCalculator:RegisterDamageSource(_W, Kassadin.W["DamageType"], Kassadin.W["BaseDamage"], Kassadin.W["DamagePerLevel"], Kassadin.W["ScalingStat"], Kassadin.W["PercentScaling"], Kassadin.W["Condition"], Kassadin.W["Extra"])
			DamageCalculator:RegisterDamageSource(_E, Kassadin.E["DamageType"], Kassadin.E["BaseDamage"], Kassadin.E["DamagePerLevel"], Kassadin.E["ScalingStat"], Kassadin.E["PercentScaling"], Kassadin.E["Condition"], Kassadin.E["Extra"])
			DamageCalculator:RegisterDamageSource(_R, Kassadin.R["DamageType"], Kassadin.R["BaseDamage"], Kassadin.R["DamagePerLevel"], Kassadin.R["ScalingStat"], Kassadin.R["PercentScaling"], Kassadin.R["Condition"], Kassadin.R["Extra"])
		--}
		--{ Initiate Menu
			Menu = scriptConfig("Kassadin","LegendKassadin")
			--{ Script Information
				Menu:addSubMenu("Kassadin: Script Details","Script")
				Menu.Script:addParam("Author","Author: Pain",5,"")
				Menu.Script:addParam("Credits","Credits: Turtlebot, AWA, Hellsing.",5,"")
				Menu.Script:addParam("Version","Version: "..version,5,"")
			--}
			--{ General/Key Bindings
				Menu:addSubMenu("Kassadin: General","General")
				Menu.General:addParam("Combo","Combo",2,false,32)
				Menu.General:addParam("LastHit","Farm Creeps",2,false,string.byte("X"))
				Menu.General:addParam("Harass","Harass",2,false,string.byte("C"))				
				Menu.General:addParam("Flee","Rift Walk Flee",2,false,string.byte("T"))
			--}
			--{ Target Selector			
				Menu:addSubMenu("Kassadin: Target Selector","TS")
				Menu.TS:addParam("TS","Target Selector",7,2,{ "AllClass", "SourceLib", "Selector", "SAC:Reborn", "MMA" })
				ts = TargetSelector(8,Kassadin.R["range"],1,false)
				ts.name = "AllClass TS"
				Menu.TS:addTS(ts)				
			--}
			--{ Orbwalking
				Menu:addSubMenu("Kassadin: Orbwalking","Orbwalking")
				OW:LoadToMenu(Menu.Orbwalking)
			--}
			--{	Combo Settings
				Menu:addSubMenu("Kassadin: Combo","Combo")
				Menu.Combo:addParam("Q","Use Q in 'Combo'",1,true)
				Menu.Combo:addParam("W","Use W in 'Combo'",1,true)
				Menu.Combo:addParam("E","Use E in 'Combo'",1,true)
				Menu.Combo:addParam("R","Use R in 'Combo'",1,true)
			--}
			--{ Harass Settings
				Menu:addSubMenu("Kassadin: Harass","Harass")
				Menu.Harass:addParam("Mana","Minimum Mana Percentage",4,70,0,100,0)
				Menu.Harass:addParam("Q","Use Q in 'Harass'",1,true)
				Menu.Harass:addParam("W","Use W in 'Harass'",1,false)
				Menu.Harass:addParam("E","Use E in 'Harass'",1,true)
				Menu.Harass:addParam("R","Use R in 'Harass'",1,false)
			--}
			--{ Farm Settings
				Menu:addSubMenu("Kassadin: Farm","Farm")
				Menu.Farm:addParam("Mana","Minimum Mana Percentage",4,70,0,100,0)
				Menu.Farm:addParam("Q","Use Q in 'Farm'",1,true)
				Menu.Farm:addParam("E","Use E in 'Farm'",1,false)
			--}
			--{ Extra Settings
				Menu:addSubMenu("Kassadin: Extra","Extra")
				Menu.Extra:addParam("Tick","Tick Suppressor (Tick Delay)",4,20,1,50,0)
				Menu.Extra:addParam("AntiBlitz","Anti Blitzcrank (Jump away)",1,true)
				Menu.Extra:addParam("SafeR","Only Rift Walk Target in Mouse Range",1,true)
				Menu.Extra:addParam("StackR","Maximum Rift Walk Stacks",7,3,{ "One Stack", "Two Stacks", "Three Stacks", "Four Stacks", "Unlimited"})
				Menu.Extra:addParam("WEPROC","Activate E with W",1,true)
			--}
			--{ Draw Settings
				Menu:addSubMenu("Kassadin: Draw","Draw")
				DrawHandler = DrawManager()
				DrawHandler:CreateCircle(myHero,Kassadin.Q["range"],1,{255, 255, 255, 255}):AddToMenu(Menu.Draw, "Q Range", true, true, true):LinkWithSpell(SpellQ, true)
				DrawHandler:CreateCircle(myHero,Kassadin.W["range"],1,{255, 255, 255, 255}):AddToMenu(Menu.Draw, "W Range", true, true, true):LinkWithSpell(SpellW, true)
				DrawHandler:CreateCircle(myHero,Kassadin.E["range"],1,{255, 255, 255, 255}):AddToMenu(Menu.Draw, "E Range", true, true, true):LinkWithSpell(SpellE, true)
				DrawHandler:CreateCircle(myHero,Kassadin.R["range"],1,{255, 255, 255, 255}):AddToMenu(Menu.Draw, "R Range", true, true, true):LinkWithSpell(SpellR, true)
				DrawHandler:CreateCircle(mousePos,Kassadin.R["width"],5,{255, 255, 255, 255}):SetDrawCondition(function() return SpellR:IsReady() end):AddToMenu(Menu.Draw, "Safe R Range", true, true, true)
				DamageCalculator:AddToMenu(Menu.Draw,{_Q,_R,_E,_W,_AA,_AA})
			--}
			--{ Perma Show Settings
				Menu:addSubMenu("Kassadin: Perma Show","Perma")
				Menu.Perma:addParam("INFO","The following options require a restart [F9 x2] to take effect",5,"")
				Menu.Perma:addParam("GC","Perma Show 'General > Combo'",1,true)				
				Menu.Perma:addParam("GF","Perma Show 'General > Farm'",1,true)
				Menu.Perma:addParam("GH","Perma Show 'General > Harass'",1,true)
				Menu.Perma:addParam("GR","Perma Show 'General > Flee'",1,true)
				if Menu.Perma.GC then Menu.General:permaShow("Combo") end
				if Menu.Perma.GF then Menu.General:permaShow("LastHit") end
				if Menu.Perma.GH then Menu.General:permaShow("Harass") end
				if Menu.Perma.GR then Menu.General:permaShow("Flee") end
				Menu.Perma:addParam("CQ","Perma Show 'Combo > Q'",1,false)
				Menu.Perma:addParam("CW","Perma Show 'Combo > W'",1,false)
				Menu.Perma:addParam("CE","Perma Show 'Combo > E'",1,false)
				Menu.Perma:addParam("CR","Perma Show 'Combo > R'",1,false)
				if Menu.Perma.CQ then Menu.Combo:permaShow("Q") end
				if Menu.Perma.CW then Menu.Combo:permaShow("W") end
				if Menu.Perma.CE then Menu.Combo:permaShow("E") end
				if Menu.Perma.CR then Menu.Combo:permaShow("R") end
				Menu.Perma:addParam("HM","Perma Show 'Harass > Mana'",1,false)
				Menu.Perma:addParam("HQ","Perma Show 'Harass > Q'",1,false)
				Menu.Perma:addParam("HW","Perma Show 'Harass > W'",1,false)
				Menu.Perma:addParam("HE","Perma Show 'Harass > E'",1,false)
				Menu.Perma:addParam("HR","Perma Show 'Harass > R'",1,false)
				if Menu.Perma.HM then Menu.Harass:permaShow("Mana") end
				if Menu.Perma.HQ then Menu.Harass:permaShow("Q") end
				if Menu.Perma.HW then Menu.Harass:permaShow("W") end
				if Menu.Perma.HE then Menu.Harass:permaShow("E") end
				if Menu.Perma.HR then Menu.Harass:permaShow("R") end
				Menu.Perma:addParam("FM","Perma Show 'Farm > Mana'",1,false)
				Menu.Perma:addParam("FQ","Perma Show 'Farm > Q'",1,false)
				Menu.Perma:addParam("FE","Perma Show 'Farm > E'",1,false)
				if Menu.Perma.FM then Menu.Farm:permaShow("Mana") end
				if Menu.Perma.FQ then Menu.Farm:permaShow("Q") end
				if Menu.Perma.FE then Menu.Farm:permaShow("E") end
				Menu.Perma:addParam("ET","Perma Show 'Extra > Tick Delay'",1,false)
				Menu.Perma:addParam("EB","Perma Show 'Extra > Anti Blitzcrank'",1,false)
				Menu.Perma:addParam("ER","Perma Show 'Extra > Safe Rift Walk'",1,false)
				Menu.Perma:addParam("ES","Perma Show 'Extra > Rift Walk Stacks'",1,false)
				Menu.Perma:addParam("EE","Perma Show 'Extra > Activate E with W'",1,false)	
				if Menu.Perma.ET then Menu.Extra:permaShow("Tick") end
				if Menu.Perma.EB then Menu.Extra:permaShow("AntiBlitz") end
				if Menu.Perma.ER then Menu.Extra:permaShow("SafeR") end
				if Menu.Perma.ES then Menu.Extra:permaShow("StackR") end
				if Menu.Perma.EE then Menu.Extra:permaShow("WEPROC") end
			--}
		--}
	end
--}
--{ Script Loop
	function OnTick()
		--{ Tick Manager
			if GetTickCount() < (TickSuppressor or 0) then return end
			TickSuppressor = GetTickCount() + Menu.Extra.Tick
		--}
		--{ Variables
			QMANA = GetSpellData(_Q).mana
			WMANA = GetSpellData(_W).mana
			EMANA = GetSpellData(_E).mana
			RMANA = GetSpellData(_R).mana
			Farm = Menu.General.LastHit and Menu.Farm.Mana <= myHero.mana / myHero.maxMana * 100
			Combat = Menu.General.Combo or (Menu.General.Harass and Menu.Harass.Mana <= myHero.mana / myHero.maxMana * 100)
			QREADY = (SpellQ:IsReady() and ((Menu.General.Combo and Menu.Combo.Q) or (Menu.General.Harass and Menu.Harass.Q) or (Farm and Menu.Farm.Q) ))
			WREADY = (SpellW:IsReady() and ((Menu.General.Combo and Menu.Combo.W) or (Menu.General.Harass and Menu.Harass.W) ))
			EREADY = (SpellE:IsReady() and ((Menu.General.Combo and Menu.Combo.E) or (Menu.General.Harass and Menu.Harass.E) or (Farm and Menu.Farm.E) ))
			RREADY = (SpellR:IsReady() and ((Menu.General.Combo and Menu.Combo.R) or (Menu.General.Harass and Menu.Harass.R) ) and Menu.Extra.StackR > RSTACK)
			WEPROC = (SpellW:IsReady() and not SpellE:IsReady())			
			Target = GrabTarget()			
		--}	
		--{ Combo and Harass
			if Combat and Target then				
				if DamageCalculator:IsKillable(Target,{_Q,_R,_E,_W,_AA,_AA}) then
					if DamageCalculator:IsKillable(Target,{_Q}) and QREADY and myHero.mana >= QMANA then
						SpellQ:Cast(Target)
					elseif DamageCalculator:IsKillable(Target,{_Q,_E}) and QREADY and EREADY and myHero.mana >= QMANA + EMANA then
						SpellQ:Cast(Target)
						SpellE:Cast(Target)
					elseif DamageCalculator:IsKillable(Target,{_Q,_E,_R}) and QREADY and EREADY and RREADY and myHero.mana >= QMANA + EMANA + RMANA then
						if (Menu.Extra.SafeR and GetDistance(mousePos,Target) <= Kassadin.R["width"]) or not Menu.Extra.SafeR then
							SpellR:Cast(Target)
						end
						SpellQ:Cast(Target)
						SpellE:Cast(Target)
					else
						if RREADY then
							if (Menu.Extra.SafeR and GetDistance(mousePos,Target) <= Kassadin.R["width"]) or not Menu.Extra.SafeR then
								SpellR:Cast(Target)
							end
						end
						if QREADY then
							SpellQ:Cast(Target)
						elseif EREADY then
							SpellE:Cast(Target)	
						end
					end
				else
					if RREADY then
						if (Menu.Extra.SafeR and GetDistance(mousePos,Target) <= Kassadin.R["width"]) or not Menu.Extra.SafeR then
							SpellR:Cast(Target)
						end					
					end
					if QREADY then
						SpellQ:Cast(Target)					
					elseif EREADY then
						SpellE:Cast(Target)
					end
				end
				if Menu.Orbwalking.Enabled and (Menu.Orbwalking.Mode0 or Menu.Orbwalking.Mode1) then
					OW:ForceTarget(Target)
				end
			end
		--}
		--{ Farming
			if Farm then
				EnemyMinions:update()
				for i, Minion in pairs(EnemyMinions.objects) do
					if Minion then
						if EREADY and DamageCalculator:IsKillable(Minion,{_E}) then
							SpellE:Cast(Minion)
						elseif QREADY and DamageCalculator:IsKillable(Minion,{_Q}) then
							SpellQ:Cast(Minion)
						end
					end
				end
			end
		--}
		--{ Rift Walk Flee
			if Menu.General.Flee then
				local CastPosition = myHero + Vector(mousePos.x-myHero.x,myHero.y,mousePos.z-myHero.z):normalized()*(Kassadin.R["range"])
				myHero:MoveTo(CastPosition.x,CastPosition.z)
				if SpellR:IsReady() then					
					CastSpell(_R,CastPosition.x,CastPosition.z)
				end
			end
		--}
		--{ Anti Blitzcrank
			if Blitzcrank ~= nil and Menu.Extra.AntiBlitz and grab ~= nil and grab:GetDistance(myHero) < 400 then
				if math.abs((myHero.x-Blitzcrank.x) * (grab.z - Blitzcrank.z) - (myHero.z-Blitzcrank.z) * (grab.x - Blitzcrank.x)) < 39000 then
					local destX = myHero.x * 4 - Blitzcrank.x*3
					local destZ = myHero.z * 4  - Blitzcrank.z*3
					CastSpell(_R, destX, destZ)
				end
			end
		--}
		--{ Activate E with W
			if not RECALL and Menu.Extra.WEPROC and WEPROC then
				CastSpell(_W)
			end
		--}
	end
--}
--{ Auto Attack Reset
	function AutoAttackReset()
		if Target and Combat then
			if WREADY then
				SpellW:Cast(Target)
			end
		end
	end
--}
--{ Target Selector
	function GrabTarget()
		if _G.MMA_Loaded and Menu.TS.TS == 5 then
			return _G.MMA_ConsideredTarget(MaxRange()) 
		elseif _G.AutoCarry and Menu.TS.TS == 4 then
			return _G.AutoCarry.Crosshair:GetTarget()
		elseif _G.Selector_Enabled and Menu.TS.TS == 3 then
			return Selector.GetTarget(SelectorMenu.Get().mode, 'AP', {distance = MaxRange()})
		elseif Menu.TS.TS == 2 then
			return TS:GetTarget(MaxRange())
		else
			ts.range = MaxRange()
			ts:update()
			return ts.target
		end
	end
--}
--{ Target Selector Range
	function MaxRange()
		if RREADY then
			return Kassadin.R["range"]
		end
		if QREADY then
			return Kassadin.Q["range"]
		end
		if EREADY then
			return Kassadin.E["range"]
		end
		if WREADY then
			return Kassadin.W["range"]
		end
		return myHero.range + 50
	end
--}
--{ Rift Walk Stack Manager
	function OnGainBuff(unit, buff)
		if unit.isMe then
			if buff.name == "RiftWalk" then
				RSTACK = RSTACK + 1
			end
			if buff.name:lower():find("recall") then
				RECALL = true
			end
		end
	end
	function OnUpdateBuff(unit, buff)
		if unit.isMe and buff.name == "RiftWalk" and RSTACK >= 1 and RSTACK < 4 then
			RSTACK = RSTACK + 1
		end
	end
	function OnLoseBuff(unit, buff)
		if unit.isMe then
			if buff.name == "RiftWalk" then
				RSTACK = 0
			end
			if buff.name:lower():find("recall") then
				RECALL = false
			end
		end
	end
--}
--{ Object Manager
	function OnCreateObj(object)
		if object ~= nil and object.name:find("FistGrab") then
	        grab = object
		end
	end
	function OnDeleteObj(object)
		if object ~= nil and object.name:find("FistGrab") then
	        grab = nil
		end
	end
--}