<?php exit() ?>--by UglyOldGuy 64.17.247.66
if myHero.charName ~= "Poppy" then return end

local NintendoUsers = {"RoachxD", "UglyOldGuy", "asiangirl", "bothappy", "hellking298", "xtony211", "CoDice", "Uruu", "tjtjsqh", "Vortur", "laughing2g", "9845163", "angel1211", "138618", "jta87k", "BAWGroup", "LukrativeHD", "77544550", "andysmalll"}
local fn = { setmetatable, tonumber, tostring, IsDDev, debug.getinfo, GetWebResult, DownloadFile, GetUser, Base64Encode, Base64Decode, string.reverse, string.lower, string.gsub, string.sub, assert, ipairs, pairs, rawget,  select, debug.debug, debug.getlocal, debug.gethook, debug.setfenv, debug.sethook, debug.setlocal, debug.upvalueid, os.clock, string.find, string.format, table.insert, load, LoadVIPScript, LoadScript }

function DrugTest()
	for i, v in ipairs(fn) do
		if debug.getinfo(v).what ~= 'C' or debug.getinfo(v).source ~= '=[C]' or debug.getinfo(v).linedefined ~= -1 or debug.getinfo(v).namewhat ~= '' then PrintChat('<font color = "#FF0000">>> Error Staph Hacking: ' .. tostring(i) .. '</font>') return end
	end
end

function NintendoUser()
	for i, user in ipairs(NintendoUsers) do
  		if user == GetUser() then
   			PrintChat("<font color='#999966'> User Authenticated! Welcome Back </font>"..GetUser())
   			return true
  		end
 	end
  	PrintChat("<font color='#FF0000'> Error Authenticating User!! </font>")
   	return false
end

if not NintendoUser() or DrugTest() then return end

function OnLoad()
	Variables()
	PoppyMenu()
	PrintChat("<font color='#CC9900'> >> Poppy - The Legendary Blacksmith<<</font>")
end

function OnTick()
	Checks()

	ComboKey = PoppyMenu.combo.comboKey
	JungleKey = PoppyMenu.combo.jungleKey
	HarassKey = PoppyMenu.harass.harassKey	

	if ComboKey then PoppyCombo() end
	if JungleKey then JungleFarm() end
	if HarassKey then HarassCombo() end
	if PoppyMenu.ks.killSteal then KillSteal() end
	if PoppyMenu.ks.autoIgnite then AutoIgnite() end
	if PoppyMenu.skills.r.autoUlt then AutoUlt() end
	if PoppyMenu.skills.e.autoStun then AutoStun() end
end

function PoppyCombo()
	if Target and Target.valid then
		if PoppyMenu.combo.comboOrbwalk then
			OrbWalk(Target)
		end
		if PoppyMenu.combo.comboItems then
			UseItems(Target)
		end
		local Combo = {_Q, _E, _W}
		ExecuteCombo(Combo, Target)
	else
		if PoppyMenu.combo.comboOrbwalk then
			MoveToMouse()
		end
	end
end

function HarassCombo()
	if Target and Target.valid then
		if PoppyMenu.harass.harassOrbwalk then
			OrbWalk(Target)
		end
		local Harass = {}
		if PoppyMenu.harass.harassE then
			Harass = {_Q, _W, _E}
		else
			Harass = {_Q, _W}
		end
		ExecuteCombo(Harass, Target)
	else
		if PoppyMenu.harass.harassOrbwalk then
			MoveToMouse()
		end
	end
end

function CastSkill(Skill, enemy)
	if Skill == _Q then
		if GetDistanceSqr(enemy) > Spells.Q.range*Spells.Q.range or not Spells.Q.ready then
			return false
		else
			CastSpell(_Q)
			return true
		end
	elseif Skill == _W then
		if GetDistanceSqr(enemy) > Spells.W.range*Spells.W.range or not Spells.W.ready then
			return false
		else
			CastSpell(_W)
		end
	elseif Skill == _E then
		if GetDistanceSqr(enemy) > Spells.E.range*Spells.E.range or not Spells.E.ready then
			return false
		end
		if VIP_USER then
			Packet("S_CAST", {spellId = _E, targetNetworkId = enemy.networkID}):send()
			return true
		else
			CastSpell(_E, enemy)
			return true
		end
	elseif Skill == _R then
		if VIP_USER then
			Packet("S_CAST", {spellId = _R, targetNetworkId = enemy.networkID}):send()
		else
			CastSpell(_R, enemy)
		end
	end
end

function ExecuteCombo(Skills, enemy)
	for i, spell in ipairs(Skills) do
		CastSkill(spell, enemy)
	end
end

function JungleFarm()
	local JungleMob = GetJungleMob()
	if JungleMob ~= nil then
		if ShenMenu.jungle.jungleOrbwalk then
			OrbWalk(JungleMob)
		end
		local JungleCombo = {_Q, _W}
		ExecuteCombo(JungleCombo, JungleMob)
	else
		if ShenMenu.jungle.jungleOrbwalk then
			MoveToMouse()
		end
	end
end

function KillSteal()
	for _, enemy in pairs(GetEnemyHeroes()) do
		Spells.E.dmg = getDmg("E", enemy, myHero)
		if Spells.Q.ready and Spells.E.ready then
			if enemy.health < Spells.E.dmg then
				CastSkill(_E, enemy)
			end
		end
	end
end

function AutoIgnite()
	if iReady then
		for _, enemy in pairs(GetEnemyHeroes()) do
			local igniteDmg = getDmg("IGNITE", enemy, myHero)
			if enemy.health < igniteDmg then
				CastSpell(ignite, enemy)
			end
		end
	end
end

function AutoStun()
	if Spells.E.ready then
		local Prediction = TargetPredictionVIP(1000, 2200, 0.25)
		for i, enemyHero in ipairs(GetEnemyHeroes()) do
            if not PoppyMenu.skills.e["disablePush"..i] then
            	if enemyHero ~= nil and enemyHero.valid and not enemyHero.dead and enemyHero.visible and GetDistance(enemyHero) <= Spells.E.range and GetDistance(enemyHero) > 0 then
                    local enemyPosition = VIP_USER and Prediction:GetPrediction(enemyHero) or enemyHero
                    local PushPos = enemyPosition + (Vector(enemyPosition) - myHero):normalized()*PoppyMenu.skills.e.pushDistance
	        		if enemyHero.x > 0 and enemyHero.z > 0 then
                        local checks = math.ceil((PoppyMenu.skills.e.pushDistance)/65)
                        local checkDistance = (PoppyMenu.skills.e.pushDistance)/checks
                        local InsideTheWall = false
                        for k=1, checks, 1 do
                            local checksPos = enemyPosition + (Vector(enemyPosition) - myHero):normalized()*(checkDistance*k)
                            local WallContainsPosition = IsWall(D3DXVECTOR3(checksPos.x, checksPos.y, checksPos.z))
                            if WallContainsPosition then
                                InsideTheWall = true
                                break
                            end
                        end
                        
                        if InsideTheWall then CastSkill(_E, enemyHero) end
                    end
                end
            end
        end
    end
end

function AutoUlt()
	if Spells.R.ready then
		if CountEnemyHeroInRange(1000, myHero) >= PoppyMenu.skills.r.ultEnemies then
			if myHero.health < myHero.maxHealth * (PoppyMenu.skills.r.ultHealth / 100) then
				FindBestUltTarget()
			end
		end
	end
end

function FindBestUltTarget()
	local UltTarget = nil
	for _, enemy in ipairs(GetEnemyHeroes()) do
		if UltTarget == nil or UltTarget.damage > enemy.damage then
			UltTarget = enemy
		end
	end
	if CountEnemyHeroInRange(1000, myHero) < 2 and Target and Target.valid then
		UltTarget = Target
	end
	if UltTarget ~= nil and UltTarget.valid and GetDistanceSqr(UltTarget) < Spells.R.range * Spells.R.range then
		CastSkill(_R, UltTarget)
	end
end


function Variables()
	Spells = {
		
		["Q"] = {key = _Q, name = "Devastating Blow",    range = 150, ready = false, mana = 0, dmg = 0, data = myHero:GetSpellData(_Q)},
		["W"] = {key = _W, name = "Paragon of Demacia",  range = 300, ready = false, mana = 0,          data = myHero:GetSpellData(_W)},
		["E"] = {key = _E, name = "Heroic Charge",       range = 525, ready = false, mana = 0, dmg = 0, data = myHero:GetSpellData(_E)},
		["R"] = {key = _R, name = "Diplomatic Immunity", range = 900, ready = false, mana = 0,          data = myHero:GetSpellData(_R)}
	}
	
	OrbWalker = {LastAttack = 0, BaseAnimationTime = 0.65, BaseWindupTime = 3}

	enemyMinions = minionManager(MINION_ENEMY, Spells.Q.range, myHero, MINION_SORT_MAXHEALTH_ASC)
	TargetSelector = TargetSelector(TARGET_NEAR_MOUSE, Spells.R.range, DAMAGE_PHYSICAL)
	TargetSelector.name = "Poppy"
	priorityTable = {
	    AP = {
	        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
	        "Kassadin", "Katarina", "Kayle", "Kennen", "Poppy", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
	        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	            },
	    Support = {
	        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
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
	
	Items = {
		["BLACKFIRE"]	= { id = 3188, range = 750, ready = false, dmg = 0 },
		["BRK"]			= { id = 3153, range = 500, ready = false, dmg = 0 },
		["BWC"]			= { id = 3144, range = 450, ready = false, dmg = 0 },
		["DFG"]			= { id = 3128, range = 750, ready = false, dmg = 0 },
		["HXG"]			= { id = 3146, range = 700, ready = false, dmg = 0 },
		["ODYNVEIL"]	= { id = 3180, range = 525, ready = false, dmg = 0 },
		["DVN"]			= { id = 3131, range = 200, ready = false, dmg = 0 },
		["ENT"]			= { id = 3184, range = 350, ready = false, dmg = 0 },
		["HYDRA"]		= { id = 3074, range = 350, ready = false, dmg = 0 },
		["TIAMAT"]		= { id = 3077, range = 350, ready = false, dmg = 0 },
		["YGB"]			= { id = 3142, range = 350, ready = false, dmg = 0 }
	}

	ShieldAbilities = {
						"GarenSlash2", "SiphoningStrikeAttack", "LeonaShieldOfDaybreakAttack", "RenektonExecute", 
						"ShyvanaDoubleAttackHit", "DariusNoxianTacticsONHAttack", "TalonNoxianDiplomacyAttack",
						"Parley", "MissFortuneRicochetShot", "RicochetAttack", "jaxrelentlessattack", "Attack"
					}
	
	JungleMobNames = {
        ["wolf8.1.1"] = true,
        ["wolf8.1.2"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.1"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["wolf2.1.1"] = true,
        ["wolf2.1.2"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.1"] = true,
        ["LesserWraith3.1.2"] = true,
        ["LesserWraith3.1.4"] = true,
        ["YoungLizard4.1.2"] = true,
        ["YoungLizard4.1.3"] = true,
        ["SmallGolem5.1.1"] = true,
	}

	FocusJungleNames = {
        ["Dragon6.1.1"] = true,
        ["Worm12.1.1"] = true,
        ["GiantWolf8.1.1"] = true,
        ["AncientGolem7.1.1"] = true,
        ["Wraith9.1.1"] = true,
        ["LizardElder10.1.1"] = true,
        ["Golem11.1.2"] = true,
        ["GiantWolf2.1.1"] = true,
        ["AncientGolem1.1.1"] = true,
        ["Wraith3.1.1"] = true,
        ["LizardElder4.1.1"] = true,
        ["Golem5.1.2"] = true,
		["GreatWraith13.1.1"] = true,
		["GreatWraith14.1.1"] = true,
	}
	--[[
	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(JungleFocusMobs, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobs, object)
			end
		end
	end]]--

	local gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end
	if heroManager.iCount < 10 then
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end
end

function ArrangePrioritys()
    for i, enemy in pairs(GetEnemyHeroes()) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function ArrangeTTPrioritys()
	for i, enemy in pairs(GetEnemyHeroes()) do
		SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 1)
        SetPriority(priorityTable.Support, enemy, 2)
        SetPriority(priorityTable.Bruiser, enemy, 2)
        SetPriority(priorityTable.Tank, enemy, 3)
	end
end

if VIP_USER then
	function OnTowerFocus(tower, target)
		if PoppyMenu.skills.r.autoUlt and PoppyMenu.skills.r.towerUlt and Spells.R.ready then
    		if tower and target and target.networkID == myHero.networkID then
    			FindBestUltTarget()
    		end
    	end
	end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

function PoppyMenu()
	PoppyMenu = scriptConfig("Poppy - The Legendary Blacksmith", "Poppy")
	
	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Skills Settings", "skills")

		PoppyMenu.skills:addSubMenu(""..Spells.E.name.." (E)", "e")
			PoppyMenu.skills.e:addParam("autoStun", "Auto Stun Enemies", SCRIPT_PARAM_ONOFF, true)
			PoppyMenu.skills.e:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 1, { "Prodiction", "VPrediction" })
			PoppyMenu.skills.e:addParam("pushDistance", "Push Distance", SCRIPT_PARAM_SLICE, 250, 0, 290, 0)
			PoppyMenu.skills.e:addParam("disableOn", "Disable On:", SCRIPT_PARAM_INFO, "")
			for i, enemy in ipairs(GetEnemyHeroes()) do
       			PoppyMenu.skills.e:addParam("disablePush"..i, " "..enemy.charName, SCRIPT_PARAM_ONOFF, false)
    		end

		PoppyMenu.skills:addSubMenu(""..Spells.R.name.." (R)", "r")
			PoppyMenu.skills.r:addParam("autoUlt", "Auto Ult Enemies", SCRIPT_PARAM_ONOFF, true)
			PoppyMenu.skills.r:addParam("towerUlt", "Auto Ult Tower Dives", SCRIPT_PARAM_ONOFF, true)
			PoppyMenu.skills.r:addParam("ultEnemies", "Auto Ult if # Enemies Around", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
			PoppyMenu.skills.r:addParam("ultHealth", "if health < %", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)
			PoppyMenu.skills.r:addParam("dontult", "Don't Ult:", SCRIPT_PARAM_INFO, "")
			for i, enemy in ipairs(GetEnemyHeroes()) do
       			PoppyMenu.skills.r:addParam("dontUlt"..i, " "..enemy.charName, SCRIPT_PARAM_ONOFF, false)
    		end

	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Combo Settings", "combo")
		PoppyMenu.combo:addParam("comboKey", "Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 88)
		PoppyMenu.combo:addParam("harassE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, false)
		PoppyMenu.combo:addParam("comboItems", "Use Items With Combo", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.combo:addParam("comboOrbwalk", "OrbWalk on Combo", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.combo:permaShow("comboKey") 

	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Harass Settings", "harass")
		PoppyMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		PoppyMenu.harass:addParam("harassE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, false)
		PoppyMenu.harass:addParam("harassOrbwalk", "OrbWalk on Harass", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.harass:permaShow("harassKey") 
			
	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Clear Settings", "jungle")
		PoppyMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86)
		PoppyMenu.jungle:addParam("jungleQ", "Clear with "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.jungle:addParam("jungleW", "Use "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.jungle:addParam("jungleE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.jungle:addParam("jungleOrbwalk", "Orbwalk the Jungle", SCRIPT_PARAM_ONOFF, true)

	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - KillSteal Settings", "ks")
		PoppyMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
		PoppyMenu.ks:permaShow("killSteal")
			
	PoppyMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Drawing Settings", "drawing")	
		PoppyMenu.drawing:addParam("eDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, true)

	PoppyMenu:addTS(TargetSelector)
end

function GetTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then
    	return _G.MMA_Target
   	elseif _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair then
   		return _G.AutoCarry.Attack_Crosshair.target
   	elseif TargetSelector.target and not TargetSelector.target.dead and TargetSelector.target.type  == "obj_AI_Hero" then
    	return TargetSelector.target
    else
    	return nil
    end
end

function UseItems(enemy)
	for i, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) and GetDistanceSqr(enemy) <= item.range*item.range then
			CastItem(item.id, enemy)
		end
	end
end

function OrbWalk(target)
	if CanAttack() and GetDistanceSqr(target) <= ((myHero.range * myHero.range) + 65) then
		Attack(target)
	elseif CanMove() then
		MoveToMouse()
	end
end

function Attack(target)
	OrbWalker.LastAttack = GetTickCount() + (GetLatency()/2)
	myHero:Attack(target)
end

function CanAttack()
	return (GetTickCount() + GetLatency()/2 > OrbWalker.LastAttack + OrbWalker.BaseAnimationTime)
end

function CanMove()
	return (GetTickCount() + GetLatency()/2 > OrbWalker.LastAttack + OrbWalker.BaseWindupTime) and not _G.evade
end

function MoveToMouse()
	if GetDistance(mousePos) then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end

function OnProcessSpell(object, spell)
	if object == myHero then
		if spell.name:lower():find("attack") then
			OrbWalker.LastAttack = (GetTickCount() - (GetLatency()/2))
			OrbWalker.BaseAnimationTime = spell.animationTime*1000
			OrbWalker.BaseWindupTime = spell.windUpTime*1000
        end
    end
end

function OnDraw()
	if not myHero.dead then
		if Spells.E.range and PoppyMenu.drawing.eDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.E.range, 0xCC9900)
		end
	end
end

function Checks()
	-- Updates Targets --
	Target = GetTarget()

	-- Updates Items --
	for i, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) then
			item.ready = true
		end
	end
	
	-- Updates Spell Info --
	for i, spell in pairs(Spells) do
		if (myHero:CanUseSpell(spell.key) == READY) then
			spell.ready = true
			spell.energy = myHero:GetSpellData(spell.key).mana
		end
	end

	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	end

	iReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)

	-- Updates Minions --
	enemyMinions:update()
end