<?php exit() ?>--by Mistal 85.94.224.20
if myHero.charName ~= "Cassiopeia" or not VIP_USER then return end



require 'Prodiction'



local sbarrier = nil



--[[ no face direction exploit ]]

local CastSpellW = CastSpell

function CastSpell(spell, param, param2)

  if myHero:CanUseSpell(spell) == READY then

    if     spell == _Q or spell == _W then Packet('S_CAST', {spellId = spell, fromX = param, fromY = param2, toX = 0, toY = 0, targetNetworkId = 0}):send() 

    elseif spell == _E                then Packet('S_CAST', {spellId = spell, fromX = myHero.x, fromY = myHero.z, toX = 0, toY = 0, targetNetworkId = param.networkID}):send() 

    elseif spell == _R                then Packet('S_CAST', {spellId = spell, fromX = myHero.x, fromY = myHero.z, toX = param, toY = param2, targetNetworkId = 0}):send() 

    elseif spell == sbarrier          then CastSpellW(sbarrier)

    end



    return true

  end



  return false

end





local ts

local enemies = {}



local nextCheck = 0

local nextUpdate = 0

local healthBefore = 0

local healthBeforeTimer = 0



local enemyMinions

local allyMinions

local jungleMinions



local disableMovement = false



local lastAttack = 0

local previousAttackCooldown = 0

local previousWindUp = 0



local minionAttacked = nil

local minionAttackedTime = 0



local stacks = 0



--                     1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8

local levelUps     = { 1,3,3,2,0,4,0,0,0,0,4,0,0,2,2,4,2,2 } -- Cass skils sequence



-- item numbers

local ZHONYA = 3157

local POTION = 2003

local MANA = 2004

local CRYSTAL = 2041

local SERAPH = 3040



function GetSummoner(summoner)

	return ((myHero:GetSpellData(SUMMONER_1).name == summoner and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name == summoner and SUMMONER_2) or nil)

end



-- OnLoad Variables



local map = 0

local minionInfo = { }



function OnLoad()

	PrintChat(">> CassiOPeia Loaded (2013-11-27)")



	CassConfig = scriptConfig("CassiOPeia", "cass")

	CassConfig:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))

	CassConfig:addParam("CastUlt", "Casts Ult", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("S"))

	CassConfig:addParam("Farm", "Kill Jungle/Lane creeps", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))

  CassConfig:addParam("LaneClear", "Spam Spells at lane creeps", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("X"))

	CassConfig:addParam("MaxPoison", "Max Q first", SCRIPT_PARAM_ONOFF, false)

	CassConfig:addParam("Harras", "Auto enemies harras", SCRIPT_PARAM_ONOFF, true)

	CassConfig:addParam("Level", "Automatic Level UP (requires restart)", SCRIPT_PARAM_ONOFF, true)

  CassConfig:addParam("Barier", "Dont let me go under %hp", SCRIPT_PARAM_SLICE, 20, 0, 100, 0)

  CassConfig:addParam("PassiveExploit", "Passive exploit", SCRIPT_PARAM_SLICE, 95, 0, 500, 0)

	CassConfig:addParam("Potion", "Auto Potion", SCRIPT_PARAM_ONOFF, true)

	CassConfig:addParam("Movement", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)

	CassConfig:addParam("Draw", "Draw", SCRIPT_PARAM_ONOFF, false)

	CassConfig:addParam("FarmAA", "Farm: AA for last hits", SCRIPT_PARAM_ONOFF, true)



  CassConfig:permaShow("LaneClear")



	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1100, DAMAGE_MAGIC)

  tsClose = TargetSelector(TARGET_LESS_CAST_PRIORITY, 700, DAMAGE_MAGIC)



	CassConfig:addTS(ts)



  -- for prediction purposes

  minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Basic"] =      { aaDelay = 400, projSpeed = 0    }

	minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Caster"] =     { aaDelay = 484, projSpeed = 0.68 }

	minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_Wizard"] =     { aaDelay = 484, projSpeed = 0.68 }

	minionInfo[(myHero.team == 100 and "Blue" or "Red").."_Minion_MechCannon"] = { aaDelay = 365, projSpeed = 1.18 }

	minionInfo.obj_AI_Turret =                                                   { aaDelay = 150, projSpeed = 1.14 }



	enemies = GetEnemyHeroes()

	enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)

  allyMinions = minionManager(MINION_ALLY, 2000, myHero, MINION_SORT_HEALTH_ASC)

	jungleMinions = minionManager(MINION_JUNGLE, 850, myHero, MINION_SORT_HEALTH_DEC)



	if CassConfig.Level then

		autoLevelSetSequence(levelUps)

		autoLevelSetFunction(onChoiceFunction)

	end



	map = GetGame().map.index

	if map == 4 or map == 8 or map == 10 then

		ZHONYA = 3090

	end

	

  pQ = ProdictManager.GetInstance():AddProdictionObject(_Q, 925, math.huge, 0.535, 0, myHero, CastQ)

  pW = ProdictManager.GetInstance():AddProdictionObject(_W, 975, math.huge, 0.375, 0, myHero, CastW)

	

  if heroManager.iCount == 10 then

		DoPriority()

	end



	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerBarrier") then sbarrier = SUMMONER_1

	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerBarrier") then sbarrier = SUMMONER_2 end

end



function SetPriority(table, hero, priority)

	for i=1, #table, 1 do

		if hero.charName:find(table[i]) ~= nil then

			TS_SetHeroPriority(priority, hero.charName)

		end

	end

end



function DoPriority()

	local priorityTable = {

		AP = {

			"Ahri", "Akali", "Anivia", "Annie", "Brand", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",

			"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",

			"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",

		},



		Support = {

			"Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",

		},



		Tank = {

			"Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",

			"Warwick", "Yorick", "Zac",

		},



		AD_Carry = {

			"Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",

			"Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed",

		},



		Bruiser = {

			"Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",

			"Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao",

		},

	}



	for _, enemy in ipairs(enemies) do

		SetPriority(priorityTable.AD_Carry, enemy, 1)

		SetPriority(priorityTable.AP,       enemy, 2)

		SetPriority(priorityTable.Support,  enemy, 3)

		SetPriority(priorityTable.Bruiser,  enemy, 4)

		SetPriority(priorityTable.Tank,     enemy, 5)

	end



end



function OnUnload()

  PrintChat("CassiOPeia turned off")

end



function onChoiceFunction() -- leveling

	if CassConfig.MaxPoison then

		if myHero:GetSpellData(_Q).level < 5 then return 1 else return 3 end

	else

    if myHero.level == 8 then return 1 end

		if myHero:GetSpellData(_E).level < 5 then return 3 else return 1 end

	end

end



local inRange = 0

local blueBuff = false



local onReadyFired = false



function OnReady()

	if map == 1 then

		BuyItem(1056) -- doran ring

		BuyItem(2004) -- health potion

		BuyItem(2003) -- mana potion



		BuyItem(3340) -- yellow trinket



		SendChat("/l")



		if myHero.team == TEAM_BLUE then

			myHero:MoveTo(5300,5300)

		elseif myHero.team == TEAM_RED then

			myHero:MoveTo(8500,8300)

		end

	end

end



local tstarget = nil

local brandfire = nil



function OnTick()

	if not onReadyFired and myHero.level == 1 and myHero:CanUseSpell(SUMMONER_1) == READY then OnReady() onReadyFired = true end



  -- updates

	ts:update()

  tsClose:update()



  if tsClose.target then tstarget = tsClose.target else tstarget = ts.target end



	enemyMinions:update()

  allyMinions:update()

	jungleMinions:update()



	if not myHero.dead then

		inRange = CountEnemyHeroInRange(1000)

		blueBuff = TargetHaveBuff("crestoftheancientgolem") 



    PassiveExploit()

    if brandfire and GetDistance(brandfire) <= 335 then CastItem(ZHONYA) end



		-- ult things

		if CassConfig.CastUlt then CastR(1, false) end



		if CassConfig.Combo then 

      if tstarget ~= nil and myHero:CanUseSpell(_W) == READY then pW:GetPredictionCallBack(tstarget, CastW) end

      AutoE() 

      if tstarget ~= nil and myHero:CanUseSpell(_Q) == READY then pQ:GetPredictionCallBack(tstarget, CastQ) end
    else
      AutoE()

    end



		-- jungle

		if not CassConfig.Combo then JungleE() end -- last hits even when not doing jungle

		if CassConfig.Farm then

			if CassConfig.FarmAA then FarmAA() end

			if CassConfig.LaneClear then FarmQ(enemyMinions, true) end

			if CassConfig.LaneClear then FarmW() end



			FarmQ(jungleMinions)

			JungleW()

		end



		if inRange == 0 then FarmE() end

		if CassConfig.Potion then Potion() end



    if not CassConfig.Combo then 

		  if CassConfig.Harras then 

        if tstarget ~= nil and myHero:CanUseSpell(_Q) == READY then pQ:GetPredictionCallBack(tstarget, CastQ) end

      end

		  if blueBuff then 

        if tstarget ~= nil and myHero:CanUseSpell(_W) == READY then pW:GetPredictionCallBack(tstarget, CastW) end

      end

    end



		if CassConfig.Movement and (CassConfig.Combo or (CassConfig.Farm and not disableMovement)) then myHero:MoveTo(mousePos.x, mousePos.z) end

	end

end



local autocass = false



function PassiveExploit()

  if not myHero:CanUseSpell(_E) == READY then return end



  if minionAttacked and not minionAttacked.dead and autocass and minionAttacked.health < myHero:CalcDamage(minionAttacked, myHero.totalDamage) and GetDistance(minionAttacked, cassaa) <= CassConfig.PassiveExploit then

    local stack = stacks

    CastSpell(_E, minionAttacked)

    cassaa = nil

  else 

    for _, minion in pairs(enemyMinions.objects) do

      if minion and not minion.dead and autocass and minion.health < myHero:CalcDamage(minion, myHero.totalDamage) and GetDistance(minion, cassaa) <= CassConfig.PassiveExploit then

        CastSpell(_E, minion)

        cassaa = nil

      end

    end

  end

end



local oriball = nil



function OnCreateObj(obj)

  if obj.name == "CassBasicAttack_mis.troy" then

    cassaa = obj

    autocass = true

  end



  if obj.name == "BrandWildfire_mis.troy" then brandfire = obj end



  if obj.name:find("yomu_ring_green") then

    oriball = obj

    return

  end



  if obj.name:find("Oriana_Ghost_bind") then

    for i, target in pairs(enemies) do

      if GetDistance(target, obj) < 50 then

        oriball = target

      end

    end

  end



end

 

function OnDeleteObj(obj)

  if obj.name == "CassBasicAttack_mis.troy" then

    cassaa = nil

    autocass = false

  end



  if obj.name == "BrandWildfire_mis.troy" then brandfire = nil end

  if obj.name:find("yomu_ring_green") or obj.name:find("Oriana_Ghost_bind") then oriball = nil end

end



--[[ not used yet 



function StacksUpdate(unit, buff)

  if buff.name == "cassiopeiadeadlycadence" then 

    if buff.stack then stacks = buff.stack else stacks = 0 end

  end

end



function OnGainBuff(unit, buff) StacksUpdate(unit, buff) end

function OnUpdateBuff(unit, buff) StacksUpdate(unit, buff) end

function OnLoseBuff(unit, buff) StacksUpdate(unit, buff) end



]]



function round(num, idp)

	local mult = 10^(idp or 0)



	return math.floor(num * mult + 0.5) / mult

end



-- minions predictions



local incomingDamage = { }



function getTrueRange()

  return myHero.range + GetDistance(myHero.minBBox)

end



function isAllyMinionInRange(minion)

	if minion ~= nil and minion.team == myHero.team

		and (minion.type == "obj_AI_Minion" or minion.type == "obj_AI_Turret")

		and GetDistance(minion) <= 2000 then return true

	else return false end

end



function getPredictedDamageOnMinion(minion)

  local predictedDamage = 0



  if minion ~= nil then

    local distanceToMinion = GetDistance(minion)

    if distanceToMinion < getTrueRange() then

      for l, attack in pairs(incomingDamage) do

        if attack.sourceType ~= "obj_AI_Turret" then

          predictedDamage = predictedDamage + getPredictedDamage(l, minion, attack)

        end

      end

    end

  end



  return predictedDamage

end



function getMinionDelay(minion)

	return ( minion.type == "obj_AI_Turret" and minionInfo.obj_AI_Turret.aaDelay or minionInfo[minion.charName].aaDelay )

end



function getMinionProjSpeed(minion)

	return ( minion.type == "obj_AI_Turret" and minionInfo.obj_AI_Turret.projSpeed or minionInfo[minion.charName].projSpeed )

end



function getNewAttackDetails(source, target)

	return  {

			sourceName = source.name,

			targetName = target.name,

			damage = source:CalcDamage(target),

			started = GetTickCount(),

			origin = { x = source.x, z = source.z },

			delay = getMinionDelay(source),

			speed = getMinionProjSpeed(source),

			sourceType = source.type

  }

end



function getAllyMinion(name)

	for i, minion in pairs(allyMinions.objects) do

		if minion ~= nil and minion.valid and minion.name == name then

			return minion

		end

	end



	return nil

end



function getEnemyMinion(name)

	for i, minion in pairs(enemyMinions.objects) do

		if minion ~= nil and ValidTarget(minion) and minion.name == name then

			return minion

		end

	end



	return nil

end



function minionSpellStillViable(attack)

	if attack == nil then return false end

	local sourceMinion = getAllyMinion(attack.sourceName)

	local targetMinion = getEnemyMinion(attack.targetName)

	if sourceMinion == nil or targetMinion == nil then return false end

	if sourceMinion.dead or targetMinion.dead or GetDistance(sourceMinion, attack.origin) > 3 then return false else return true end

end



function isSameMinion(minion1, minion2)

	if minion1.networkID == minion2.networkID then return true

	else return false end

end



function getTimeToHit(enemy, speed)

	return (( GetDistance(enemy) / speed ) + GetLatency()/2)

end



local cassiopeiaProjSpeed = 1.22



function getMinionTimeToHit(minion, attack)

	local sourceMinion = getAllyMinion(attack.sourceName)

	return ( attack.speed == 0 and ( attack.delay ) or ( attack.delay + GetDistance(sourceMinion, minion) / attack.speed ) )

end



function getPredictedDamage(counter, minion, attack)

	if not minionSpellStillViable(attack) then

		incomingDamage[counter] = nil

	elseif isSameMinion(minion, getEnemyMinion(attack.targetName)) then

		local myTimeToHit = getTimeToHit(minion, cassiopeiaProjSpeed)

		minionTimeToHit = getMinionTimeToHit(minion, attack)

		if GetTickCount() >= (attack.started + minionTimeToHit) then

			incomingDamage[counter] = nil

		elseif GetTickCount() + myTimeToHit > attack.started + minionTimeToHit then

			return attack.damage

		end

	end



	return 0

end



function getKillableCreep(iteration)

	local minion = enemyMinions.objects[iteration]

	if minion ~= nil then

		local distanceToMinion = GetDistance(minion)

		local predictedDamage = 0

		if distanceToMinion < getTrueRange() then

			for l, attack in pairs(incomingDamage) do

				predictedDamage = predictedDamage + getPredictedDamage(l, minion, attack)

			end



			local myDamage = myHero:CalcDamage(minion, myHero.totalDamage + 2)

			if minion.health - minion.maxHealth < 0.5 then myDamage = myDamage * 1.05 end -- executioner

			myDamage = myDamage - 10



			if minion.health + 1.2 - predictedDamage < myDamage then

					return minion

			end

		end

	end



	return nil

end



function round(num, idp)

  local mult = 10^(idp or 0)

  return math.floor(num * mult + 0.5) / mult

end



local BShield,SShield,Shield,CC = false,false,false,false

local shottype,radius,maxdistance = 0,0,0

local hitchampion = false



function OnProcessSpell(unit, spell)

  if unit.team ~= myHero.team and spell.name == "OrianaDetonateCommand" and oriball and GetDistance(oriball) <= 400 then CastItem(ZHONYA) end



  if spell.name == "SummonerFlash" and unit.team ~= myHero.team and ((GetDistance(unit) < 3400 and unit.level <= 11) or unit.level <= 6) then 

    DelayAction(SendChat, math.random(7, 15), { "" .. unit.charName:lower() .. " no flash" }) 

  end



	if unit.isMe and spell.name:lower():find("attack") then

		lastAttack = GetTickCount() - GetLatency()/2

    previousWindUp = spell.windUpTime*1000

		previousAttackCooldown = spell.animationTime*1000

  end



	if isAllyMinionInRange(unit) then

    for i,minion in pairs(enemyMinions.objects) do

      if ValidTarget(minion) and minion ~= nil and GetDistance(minion, spell.endPos) < 3 then

        if unit ~= nil and (minionInfo[unit.charName] or unit.type == "obj_AI_turret") then

			    incomingDamage[unit.name] = getNewAttackDetails(unit, minion)

        end

      end

    end

  end



  if CassConfig.Barier > 0 then 

	  if unit.team ~= myHero.team and not myHero.dead and not (unit.name:find("Minion_") or unit.name:find("Odin")) then



		  local HitFirst = false



		  BShield,SShield,Shield,CC = false,false,false,false

		  shottype,radius,maxdistance = 0,0,0

		  if unit.type == "obj_AI_Hero" then

			  spelltype, casttype = getSpellType(unit, spell.name)

			  if casttype == 4 or casttype == 5 then return end

			  if spelltype == "BAttack" or spelltype == "CAttack" or spell.name:find("SummonerDot") then

				  Shield = true

			  elseif spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then

				  HitFirst = skillShield[unit.charName][spelltype]["HitFirst"]

				  BShield = skillShield[unit.charName][spelltype]["BShield"]

				  SShield = skillShield[unit.charName][spelltype]["SShield"]

				  Shield = skillShield[unit.charName][spelltype]["Shield"]

				  CC = skillShield[unit.charName][spelltype]["CC"]

				  shottype = skillData[unit.charName][spelltype]["type"]

				  radius = skillData[unit.charName][spelltype]["radius"]

				  maxdistance = skillData[unit.charName][spelltype]["maxdistance"]

			  end

		  else

			  Shield = true

		  end



			  if not myHero.dead and myHero.health > 0 then



				  hitchampion = false

				  local allyHitBox = getHitBox(myHero)

				  if shottype == 0 then hitchampion = spell.target and spell.target.networkID == myHero.networkID

				  elseif shottype == 1 then hitchampion = checkhitlinepass(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 2 then hitchampion = checkhitlinepoint(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 3 then hitchampion = checkhitaoe(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 4 then hitchampion = checkhitcone(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 5 then hitchampion = checkhitwall(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 6 then hitchampion = checkhitlinepass(unit, spell.endPos, radius, maxdistance, myHero, allyHitBox) or checkhitlinepass(unit, Vector(unit)*2-spell.endPos, radius, maxdistance, myHero, allyHitBox)

				  elseif shottype == 7 then hitchampion = checkhitcone(spell.endPos, unit, radius, maxdistance, myHero, allyHitBox)

				  end



				  if hitchampion then



						  local skilldamage = shieldCheck(unit,spell,myHero)

              local hpAfter = myHero.health-skilldamage

						  if hpAfter/myHero.maxHealth < CassConfig.Barier / 100 then



                if sbarrier ~= nil and myHero:CanUseSpell(sbarrier) == READY and hpAfter + 95 + (20 * myHero.level) > 25 then

							    CastSpell(sbarrier)

                elseif GetInventoryItemIsCastable(ZHONYA) then

                  CastItem(ZHONYA)

                end

						  end



				  end

			  end



	  end	

  end



end



function shieldCheck(object,spell,target)



	local adamage = object:CalcDamage(target,object.totalDamage)

	local InfinityEdge,onhitdmg,onhittdmg,onhitspelldmg,onhitspelltdmg,muramanadmg,skilldamage,skillTypeDmg = 0,0,0,0,0,0,0,0



	if object.type ~= "obj_AI_Hero" then

		if spell.name:find("BasicAttack") then skilldamage = adamage

		elseif spell.name:find("CritAttack") then skilldamage = adamage*2 end

	else

		if GetInventoryHaveItem(3186,object) then onhitdmg = getDmg("KITAES",target,object) end

		if GetInventoryHaveItem(3114,object) then onhitdmg = onhitdmg+getDmg("MALADY",target,object) end

		if GetInventoryHaveItem(3091,object) then onhitdmg = onhitdmg+getDmg("WITSEND",target,object) end

		if GetInventoryHaveItem(3057,object) then onhitdmg = onhitdmg+getDmg("SHEEN",target,object) end

		if GetInventoryHaveItem(3078,object) then onhitdmg = onhitdmg+getDmg("TRINITY",target,object) end

		if GetInventoryHaveItem(3100,object) then onhitdmg = onhitdmg+getDmg("LICHBANE",target,object) end

		if GetInventoryHaveItem(3025,object) then onhitdmg = onhitdmg+getDmg("ICEBORN",target,object) end

		if GetInventoryHaveItem(3087,object) then onhitdmg = onhitdmg+getDmg("STATIKK",target,object) end

		if GetInventoryHaveItem(3153,object) then onhitdmg = onhitdmg+getDmg("RUINEDKING",target,object) end

		if GetInventoryHaveItem(3209,object) then onhittdmg = getDmg("SPIRITLIZARD",target,object) end

		if GetInventoryHaveItem(3184,object) then onhittdmg = onhittdmg+80 end

		if GetInventoryHaveItem(3042,object) then muramanadmg = getDmg("MURAMANA",target,object) end

		if spelltype == "BAttack" then

			skilldamage = (adamage+onhitdmg+muramanadmg)*1.07+onhittdmg

		elseif spelltype == "CAttack" then

			if GetInventoryHaveItem(3031,object) then InfinityEdge = .5 end

			skilldamage = (adamage*(2.1+InfinityEdge)+onhitdmg+muramanadmg)*1.07+onhittdmg --fix Lethality

		elseif spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then

			if GetInventoryHaveItem(3151,object) then onhitspelldmg = getDmg("LIANDRYS",target,object) end

			if GetInventoryHaveItem(3188,object) then onhitspelldmg = getDmg("BLACKFIRE",target,object) end

			if GetInventoryHaveItem(3209,object) then onhitspelltdmg = getDmg("SPIRITLIZARD",target,object) end

			muramanadmg = skillShield[object.charName][spelltype]["Muramana"] and muramanadmg or 0

			if casttype == 1 then

				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,1,spell.level)

			elseif casttype == 2 then

				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,2,spell.level)

			elseif casttype == 3 then

				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,3,spell.level)

			end

			if skillTypeDmg == 2 then

				skilldamage = (skilldamage+adamage+onhitspelldmg+onhitdmg+muramanadmg)*1.07+onhittdmg+onhitspelltdmg

			else

				if skilldamage > 0 then skilldamage = (skilldamage+onhitspelldmg+muramanadmg)*1.07+onhitspelltdmg end

			end

		elseif spell.name:find("SummonerDot") then

			skilldamage = getDmg("IGNITE",target,object)

		end

	end



	return skilldamage

end



function getHitBox(hero)

    local hitboxTable = { ['HeimerTGreen'] = 50.0, ['Darius'] = 80.0, ['ZyraGraspingPlant'] = 20.0, ['HeimerTRed'] = 50.0, ['ZyraThornPlant'] = 20.0, ['Nasus'] = 80.0, ['HeimerTBlue'] = 50.0, ['SightWard'] = 1, ['HeimerTYellow'] = 50.0, ['Kennen'] = 55.0, ['VisionWard'] = 1, ['ShacoBox'] = 10, ['HA_AP_Poro'] = 0, ['TempMovableChar'] = 48.0, ['TeemoMushroom'] = 50.0, ['OlafAxe'] = 50.0, ['OdinCenterRelic'] = 48.0, ['Blue_Minion_Healer'] = 48.0, ['AncientGolem'] = 100.0, ['AnnieTibbers'] = 80.0, ['OdinMinionGraveyardPortal'] = 1.0, ['OriannaBall'] = 48.0, ['LizardElder'] = 65.0, ['YoungLizard'] = 50.0, ['OdinMinionSpawnPortal'] = 1.0, ['MaokaiSproutling'] = 48.0, ['FizzShark'] = 0, ['Sejuani'] = 80.0, ['Sion'] = 80.0, ['OdinQuestIndicator'] = 1.0, ['Zac'] = 80.0, ['Red_Minion_Wizard'] = 48.0, ['DrMundo'] = 80.0, ['Blue_Minion_Wizard'] = 48.0, ['ShyvanaDragon'] = 80.0, ['HA_AP_OrderShrineTurret'] = 88.4, ['Heimerdinger'] = 55.0, ['Rumble'] = 80.0, ['Ziggs'] = 55.0, ['HA_AP_OrderTurret3'] = 88.4, ['HA_AP_OrderTurret2'] = 88.4, ['TT_Relic'] = 0, ['Veigar'] = 55.0, ['HA_AP_HealthRelic'] = 0, ['Teemo'] = 55.0, ['Amumu'] = 55.0, ['HA_AP_ChaosTurretShrine'] = 88.4, ['HA_AP_ChaosTurret'] = 88.4, ['HA_AP_ChaosTurretRubble'] = 88.4, ['Poppy'] = 55.0, ['Tristana'] = 55.0, ['HA_AP_PoroSpawner'] = 50.0, ['TT_NGolem'] = 80.0, ['HA_AP_ChaosTurretTutorial'] = 88.4, ['Volibear'] = 80.0, ['HA_AP_OrderTurretTutorial'] = 88.4, ['TT_NGolem2'] = 80.0, ['HA_AP_ChaosTurret3'] = 88.4, ['HA_AP_ChaosTurret2'] = 88.4, ['Shyvana'] = 50.0, ['HA_AP_OrderTurret'] = 88.4, ['Nautilus'] = 80.0, ['ARAMOrderTurretNexus'] = 88.4, ['TT_ChaosTurret2'] = 88.4, ['TT_ChaosTurret3'] = 88.4, ['TT_ChaosTurret1'] = 88.4, ['ChaosTurretGiant'] = 88.4, ['ARAMOrderTurretFront'] = 88.4, ['ChaosTurretWorm'] = 88.4, ['OdinChaosTurretShrine'] = 88.4, ['ChaosTurretNormal'] = 88.4, ['OrderTurretNormal2'] = 88.4, ['OdinOrderTurretShrine'] = 88.4, ['OrderTurretDragon'] = 88.4, ['OrderTurretNormal'] = 88.4, ['ARAMChaosTurretFront'] = 88.4, ['ARAMOrderTurretInhib'] = 88.4, ['ChaosTurretWorm2'] = 88.4, ['TT_OrderTurret1'] = 88.4, ['TT_OrderTurret2'] = 88.4, ['ARAMChaosTurretInhib'] = 88.4, ['TT_OrderTurret3'] = 88.4, ['ARAMChaosTurretNexus'] = 88.4, ['OrderTurretAngel'] = 88.4, ['Mordekaiser'] = 80.0, ['TT_Buffplat_R'] = 0, ['Lizard'] = 50.0, ['GolemOdin'] = 80.0, ['Renekton'] = 80.0, ['Maokai'] = 80.0, ['LuluLadybug'] = 50.0, ['Alistar'] = 80.0, ['Urgot'] = 80.0, ['LuluCupcake'] = 50.0, ['Gragas'] = 80.0, ['Skarner'] = 80.0, ['Yorick'] = 80.0, ['MalzaharVoidling'] = 10.0, ['LuluPig'] = 50.0, ['Blitzcrank'] = 80.0, ['Chogath'] = 80.0, ['Vi'] = 50, ['FizzBait'] = 0, ['Malphite'] = 80.0, ['EliseSpiderling'] = 1.0, ['Dragon'] = 100.0, ['LuluSquill'] = 50.0, ['Worm'] = 100.0, ['redDragon'] = 100.0, ['LuluKitty'] = 50.0, ['Galio'] = 80.0, ['Annie'] = 55.0, ['EliseSpider'] = 50.0, ['SyndraSphere'] = 48.0, ['LuluDragon'] = 50.0, ['Hecarim'] = 80.0, ['TT_Spiderboss'] = 200.0, ['Thresh'] = 55.0, ['ARAMChaosTurretShrine'] = 88.4, ['ARAMOrderTurretShrine'] = 88.4, ['Blue_Minion_MechMelee'] = 65.0, ['TT_NWolf'] = 65.0, ['Tutorial_Red_Minion_Wizard'] = 48.0, ['YorickRavenousGhoul'] = 1.0, ['SmallGolem'] = 80.0, ['OdinRedSuperminion'] = 55.0, ['Wraith'] = 50.0, ['Red_Minion_MechCannon'] = 65.0, ['Red_Minion_Melee'] = 48.0, ['OdinBlueSuperminion'] = 55.0, ['TT_NWolf2'] = 50.0, ['Tutorial_Red_Minion_Basic'] = 48.0, ['YorickSpectralGhoul'] = 1.0, ['Wolf'] = 50.0, ['Blue_Minion_MechCannon'] = 65.0, ['Golem'] = 80.0, ['Blue_Minion_Basic'] = 48.0, ['Blue_Minion_Melee'] = 48.0, ['Odin_Blue_Minion_caster'] = 48.0, ['TT_NWraith2'] = 50.0, ['Tutorial_Blue_Minion_Wizard'] = 48.0, ['GiantWolf'] = 65.0, ['Odin_Red_Minion_Caster'] = 48.0, ['Red_Minion_MechMelee'] = 65.0, ['LesserWraith'] = 50.0, ['Red_Minion_Basic'] = 48.0, ['Tutorial_Blue_Minion_Basic'] = 48.0, ['GhostWard'] = 1, ['TT_NWraith'] = 50.0, ['Red_Minion_MechRange'] = 65.0, ['YorickDecayedGhoul'] = 1.0, ['TT_Buffplat_L'] = 0, ['TT_ChaosTurret4'] = 88.4, ['TT_Buffplat_Chain'] = 0, ['TT_OrderTurret4'] = 88.4, ['OrderTurretShrine'] = 88.4, ['ChaosTurretShrine'] = 88.4, ['WriggleLantern'] = 1, ['ChaosTurretTutorial'] = 88.4, ['TwistedLizardElder'] = 65.0, ['RabidWolf'] = 65.0, ['OrderTurretTutorial'] = 88.4, ['OdinShieldRelic'] = 0, ['TwistedGolem'] = 80.0, ['TwistedSmallWolf'] = 50.0, ['TwistedGiantWolf'] = 65.0, ['TwistedTinyWraith'] = 50.0, ['TwistedBlueWraith'] = 50.0, ['TwistedYoungLizard'] = 50.0, ['Summoner_Rider_Order'] = 65.0, ['Summoner_Rider_Chaos'] = 65.0, ['Ghast'] = 60.0, ['blueDragon'] = 100.0, }

    return (hitboxTable[hero.charName] ~= nil and hitboxTable[hero.charName] ~= 0) and hitboxTable[hero.charName] or 65

end



function Potion()

	if not TargetHaveBuff("Recall", myHero) and inRange > 0 then



		if myHero.health < myHero.maxHealth - 200 then

			if GetInventoryItemIsCastable(CRYSTAL) and not TargetHaveBuff("ItemCrystalFlask", myHero) then

				CastItem(CRYSTAL)

			elseif GetInventoryItemIsCastable(POTION) and not TargetHaveBuff("RegenerationPotion", myHero) then

				CastItem(POTION)

			end

		end



		if myHero.mana < 200 then

			if GetInventoryItemIsCastable(CRYSTAL) and not TargetHaveBuff("ItemCrystalFlask", myHero) then

				CastItem(CRYSTAL)

			elseif GetInventoryItemIsCastable(MANA) and not TargetHaveBuff("FlaskOfCrystalWater", myHero) then

				CastItem(MANA)

			end

		end



	end

end



function _sign(x)

	if x> 0 then return 1

	elseif x<0 then return -1

	end

end



function _areClockwise(testv1, testv2)

	if testv1.z ~= nil and testv2.z ~= nil then

		return -testv1.x * testv2.z + testv1.z * testv2.x>0

	else

		return -testv1.x * testv2.y + testv1.y * testv2.x>0

	end

end



function IsFacingMe(target)

	return GetDistance(target.visionPos) < GetDistance(target)

end



function GetCassMECS(Center, AngleDegree, Radius, Minimum, Faced)

	local enemyPos = nil

	local Points = {}

	local nFaced = 0

	local n = 1

	local v1,v2,v3 = 0,0,0

	local largeN,largeV1,largeV2 = 0,0,0

	local theta1,theta2,smallBisect = 0,0,0

	for i=1, #enemies do

		local enemy = enemies[i]

		if Center == player then

			enemyPos = GetPredictionPos(enemy, 600)

			if ValidTarget(enemy, Radius) and enemyPos and GetDistance(enemyPos) < Radius then

				if Faced then

					if IsFacingMe(enemy) then

						nFaced = nFaced + 1

					else

						nFaced = nFaced + 0.67

					end



				else --not only faced

					nFaced = nFaced + 1

				end



				Points[#Points+1] = enemy -- enemy instead enemyPos

			end

		end

	end



	if #Points == 0 or nFaced < Minimum then return nil end

	if #Points == 1 and nFaced >= Minimum then

		return Points[1]

	end



	if #Points >= 2 and nFaced >= Minimum then

		for i=1, #Points,1 do

			for j=1,#Points, 1 do

				if i~=j then

					v1 = Vector(Points[i].x-Center.x , Points[i].z-Center.z)

					v2 = Vector(Points[j].x-Center.x , Points[j].z-Center.z)

					thetav1 = _sign(v1.y)*90-math.deg(math.atan(v1.x/v1.y))

					thetav2 = _sign(v2.y)*90-math.deg(math.atan(v2.x/v2.y))

					thetaBetween = thetav2-thetav1



					if (thetaBetween) <= AngleDegree and thetaBetween > 0 then

						if #Points == 2 then

							largeV1 = v1

							largeV2 = v2

						else

							tempN = 0

							for k=1, #Points,1 do

								if k~=i and k~=j then

									v3 = Vector(Points[k].x-Center.x , Points[k].z-Center.z)

									if _areClockwise(v3,v1) and not _areClockwise(v3,v2) then

										tempN = tempN+1

									end

								end

							end



							if tempN > largeN then

								largeN = tempN

								largeV1 = v1

								largeV2 = v2

							end

						end

					end

				end

			end

		end

	end



	if largeV1 == 0 or largeV2 == 0 then

		return nil

	else

		if largeV1.y == 0 then

			theta1 = 0

		else

			theta1 = _sign(largeV1.y)*90-math.deg(math.atan(largeV1.x/largeV1.y))

		end



		if largeV2.y == 0 then

			theta2 = 0

		else

			theta2 = _sign(largeV2.y)*90-math.deg(math.atan(largeV2.x/largeV2.y))

		end



		smallBisect = math.rad((theta1 + theta2) / 2)

		vResult = {}

		vResult.x = Radius*math.cos(smallBisect)+player.x

		vResult.y = player.y

		vResult.z = Radius*math.sin(smallBisect)+player.z

		if largeN >= Minimum or #Points == 2 then

			return vResult

		end

	end

end



local RRange = 750 --850



function CastR(n, faced)

	if myHero:CanUseSpell(_R) == READY then

		local ultEnemies = CountEnemyHeroInRange(RRange + 300)

		if ultEnemies >= n then

			local vec = GetCassMECS(myHero, 70, 750, n, faced) -- 80 degree R

			if vec ~= nil and GetDistance(vec) < RRange then

				CastSpell(_R, vec.x, vec.z)

			end

		end

	end

end



function OnDraw()

	if CassConfig.Draw then DrawCircle(myHero.x, myHero.y, myHero.z, 850, 0xFFFFFF) end



  if oriball then DrawCircle(oriball.x, oriball.y, oriball.z, 100, 0xFFFFFF) end

end



function CastQ(target, pos, spell)

	if not target then target = tstarget end



	if myHero:CanUseSpell(_Q) == READY and ValidTarget(target) then

		local predPos 

    if pos then 

      predPos = pos

    end



		if predPos then

			local distance = GetDistance(myHero, predPos)



      local castPos = nil

			if distance < 850 then

        castPos = predPos

			elseif distance < 925 then

				castPos = Vector(myHero) + (Vector(predPos) - Vector(myHero)):normalized() * 850

			end



      if castPos then

        CastSpell(_Q, castPos.x, castPos.z)

      end

		end

	end

end



function CastW(target, pos, spell)

  if not target then target = tstarget end



	if myHero:CanUseSpell(_W) == READY and ValidTarget(target) then

		local predPos 

    if pos then 

      predPos = pos

    end



		if predPos then

			local distance = GetDistance(myHero, predPos)



			if distance < 850 then

				CastSpell(_W, predPos.x, predPos.z)

			elseif distance < 975 then

				local castPos = Vector(myHero) + (Vector(predPos) - Vector(myHero)):normalized() * 850

				CastSpell(_W, castPos.x, castPos.z)

			end

		end

	end

end



function FarmQ(minions, mec)

	if myHero:CanUseSpell(_Q) == READY and not disableMovement then

		local pos = nil

		if minions.iCount < 20 and mec then

			pos = GetMEC(75, 850, nil, false)

		end



		if pos then

			CastSpell(_Q, pos.center.x, pos.center.z)

		elseif minions.objects[1] ~= nil and minions.objects[1] and minions.objects[1].health > myHero:CalcDamage(minions.objects[1], myHero.totalDamage) then

			CastSpell(_Q, minions.objects[1].x, minions.objects[1].z)

		end

	end

end



function FarmW()

	if enemyMinions.iCount > 6 and not disableMovement then

		pos = GetMEC(200, 850, nil, true)

		if pos then

			CastSpell(_W, pos.center.x, pos.center.z)

		end

	end

end



function JungleE()

	if myHero:CanUseSpell(_E) == READY then

		for index, minion in pairs(jungleMinions.objects) do

			if GetDistance(myHero, minion) < 700 then

				if TargetPoisoned(minion) then

					CastSpell(_E, minion)

				end



				-- steal large jungle minions

				if minion.health < getDmg("E", minion, myHero) and minion.maxHealth > 1000 then

					CastSpell(_E, minion)

				end

			end



		end

	end

end



function FarmE()

	if myHero:CanUseSpell(_E) == READY and not disableMovement then



		for index, minion in pairs(enemyMinions.objects) do

			local damage = getDmg("E", minion, myHero)

			if (minion.health < damage or blueBuff) and TargetPoisoned(minion) and GetDistance(myHero, minion) < 700 then

				CastSpell(_E, minion)

			end

		end

	end

end



function JungleW()

	if myHero:CanUseSpell(_W) == READY and jungleMinions.objects[1] ~= nil and jungleMinions.objects[1] and TargetPoisoned(jungleMinions.objects[1]) then

		CastSpell(_W, jungleMinions.objects[1].x, jungleMinions.objects[1].z)

	end

end



function timeToShoot()

	return (GetTickCount() + GetLatency()/2 > lastAttack + previousAttackCooldown)

end



local distance = 0



function FarmAA()

  local minion = getKillableCreep(1)



	if not minionAttacked and minion and timeToShoot() then

		disableMovement = true



    minionAttacked = minion

    minionAttackedTime = GetGameTimer()

    

		myHero:Attack(minion)



	elseif minionAttacked and (minionAttacked.dead or not minionAttacked.valid or (GetGameTimer() - minionAttackedTime) > 1) then

		disableMovement = false

    minionAttacked = false

	end

end





function TargetPoisoned2(target)

  if not onTickTM:isReady() then return false end



  for i = 1, objManager.maxObjects do

        local obj = objManager:GetObject(i)

        if obj and target then

            if (obj.name:lower():find("global_poison")) and GetDistance(obj, target) < 100 then

                return true

            end

        end

    end



  return false

end



function TargetPoisoned(target)

  local delay = math.max(GetDistance(target), 700)/1800 + 0.125

  for i = 1, target.buffCount do

    local tBuff = target:getBuff(i)

    if BuffIsValid(tBuff) and (tBuff.name == "cassiopeianoxiousblastpoison" or tBuff.name == "cassiopeiamiasmapoison" 

      or tBuff.name == "toxicshotparticle" or tBuff.name == "bantamtraptarget" or tBuff.name == "poisontrailtarget" 

      or tBuff.name == "deadlyvenom") and tBuff.endT - delay - GetGameTimer() > 0 then

      return true

    end

  end 

	

  return false

end



function AutoE()

	if myHero:CanUseSpell(_E) == READY then

		if ValidTarget(tstarget, 700) then

			if (TargetPoisoned(tstarget)) or (getDmg("E", tstarget, myHero) > tstarget.health and inRange <= 2) then

        CastSpell(_E, tstarget)

			end

		else

			for _, enemy in pairs(enemies) do

				if ValidTarget(enemy, 700) then

					if (TargetPoisoned(enemy)) or (getDmg("E", enemy, myHero) > enemy.health and inRange <= 2) then

					  CastSpell(_E, enemy)

					end

				end

			end

		end

	end

end



class'MEC'

function MEC:__init(points)

	self.circle = Circle()

	self.points = {}

	if points then

		self:SetPoints(points)

	end

end



function MEC:SetPoints(points)

	self.points = {}

	for _, p in ipairs(points) do

		table.insert(self.points, Vector(p))

	end

end



function MEC:HalfHull(left, right, pointTable, factor)

	local input = pointTable

	table.insert(input, right)

	local half = {}

	table.insert(half, left)

	for _, p in ipairs(input) do

		table.insert(half, p)

		while #half >= 3 do

			local dir = factor * VectorDirection(half[(#half + 1) - 3], half[(#half + 1) - 1], half[(#half + 1) - 2])

			if dir <= 0 then

				table.remove(half, #half - 1)

			else

				break

			end

		end

	end

	return half

end



function MEC:ConvexHull()

	local left, right = self.points[1], self.points[#self.points]

	local upper, lower, ret = {}, {}, {}

	for i = 2, #self.points - 1 do

		if VectorType(self.points[i]) == false then PrintChat("self.points[i]") end

		table.insert((VectorDirection(left, right, self.points[i]) < 0 and upper or lower), self.points[i])

	end

	local upperHull = self:HalfHull(left, right, upper, -1)

	local lowerHull = self:HalfHull(left, right, lower, 1)

	local unique = {}

	for _, p in ipairs(upperHull) do

		unique["x" .. p.x .. "z" .. p.z] = p

	end

	for _, p in ipairs(lowerHull) do

		unique["x" .. p.x .. "z" .. p.z] = p

	end

	for _, p in pairs(unique) do

		table.insert(ret, p)

	end

	return ret

end



function MEC:Compute()

	if #self.points == 0 then return nil end

	if #self.points == 1 then

		self.circle.center = self.points[1]

		self.circle.radius = 0

		self.circle.radiusPoint = self.points[1]

	elseif #self.points == 2 then

		local a = self.points

		self.circle.center = a[1]:center(a[2])

		self.circle.radius = a[1]:dist(self.circle.center)

		self.circle.radiusPoint = a[1]

	else

		local a = self:ConvexHull()

		local point_a = a[1]

		local point_b

		local point_c = a[2]

		if not point_c then

			self.circle.center = point_a

			self.circle.radius = 0

			self.circle.radiusPoint = point_a

			return self.circle

		end

		while true do

			point_b = nil

			local best_theta = 180.0

			for _, point in ipairs(self.points) do

				if (not point == point_a) and (not point == point_c) then

					local theta_abc = point:angleBetween(point_a, point_c)

					if theta_abc < best_theta then

						point_b = point

						best_theta = theta_abc

					end

				end

			end

			if best_theta >= 90.0 or (not point_b) then

				self.circle.center = point_a:center(point_c)

				self.circle.radius = point_a:dist(self.circle.center)

				self.circle.radiusPoint = point_a

				return self.circle

			end

			local ang_bca = point_c:angleBetween(point_b, point_a)

			local ang_cab = point_a:angleBetween(point_c, point_b)

			if ang_bca > 90.0 then

				point_c = point_b

			elseif ang_cab <= 90.0 then

				break

			else

				point_a = point_b

			end

		end

		local ch1 = (point_b - point_a) * 0.5

		local ch2 = (point_c - point_a) * 0.5

		local n1 = ch1:perpendicular2()

		local n2 = ch2:perpendicular2()

		ch1 = point_a + ch1

		ch2 = point_a + ch2

		self.circle.center = VectorIntersection(ch1, n1, ch2, n2)

		self.circle.radius = self.circle.center:dist(point_a)

		self.circle.radiusPoint = point_a

	end

	return self.circle

end



function GetMEC(radius, range, target, isW)

	assert(type(radius) == "number" and type(range) == "number" and (target == nil or target.team ~= nil), "GetMEC: wrong argument types (expected <number>, <number>, <object> or nil)")

	local points = {}

	for _, object in pairs(enemyMinions.objects) do

		if (target == nil and ValidTarget(object, (range + radius))) or (target and ValidTarget(object, (range + radius), (target.team ~= player.team)) and (ValidTargetNear(object, radius * 2, target) or object.networkID == target.networkID)) and not TargetPoisoned(target) then

			table.insert(points, Vector(object))

		end

	end

	return _CalcSpellPosForGroup(radius, range, points, isW)

end



function _CalcSpellPosForGroup(radius, range, points, isW)

	if #points == 0 then

		return nil

	elseif #points < 6 and isW then

		return nil

	elseif #points == 1 then

		return Circle(Vector(points[1]))

	end

	local mec = MEC()

	local combos = {}

	for j = #points, 2, -1 do

		local spellPos

		combos[j] = {}

		_CalcCombos(j, points, combos[j])

		for _, v in ipairs(combos[j]) do

			mec:SetPoints(v)

			local c = mec:Compute()

			if c ~= nil and c.radius <= radius and c.center:dist(player) <= range and (spellPos == nil or c.radius < spellPos.radius) then

				spellPos = Circle(c.center, c.radius)

			end

		end

		if spellPos ~= nil then return spellPos end

	end

end



function _CalcCombos(comboSize, targetsTable, comboTableToFill, comboString, index_number)

	local comboString = comboString or ""

	local index_number = index_number or 1

	if string.len(comboString) == comboSize then

		local b = {}

		for i = 1, string.len(comboString), 1 do

			local ai = tonumber(string.sub(comboString, i, i))

			table.insert(b, targetsTable[ai])

		end

		return table.insert(comboTableToFill, b)

	end

	for i = index_number, #targetsTable, 1 do

		_CalcCombos(comboSize, targetsTable, comboTableToFill, comboString .. i, i + 1)

	end

end



class'Circle'

function Circle:__init(center, radius)

	assert((VectorType(center) or center == nil) and (type(radius) == "number" or radius == nil), "Circle: wrong argument types (expected <Vector> or nil, <number> or nil)")

	self.center = Vector(center) or Vector()

	self.radius = radius or 0

end



function Circle:Contains(v)

	assert(VectorType(v), "Contains: wrong argument types (expected <Vector>)")

	return math.close(self.center:dist(v), self.radius)

end



function Circle:__tostring()

	return "{center: " .. tostring(self.center) .. ", radius: " .. tostring(self.radius) .. "}"

end