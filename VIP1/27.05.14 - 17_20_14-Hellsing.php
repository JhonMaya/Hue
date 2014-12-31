<?php exit() ?>--by Hellsing 85.16.226.30
--Auto Download Required LIBS

local REQUIRED_LIBS = {
		["VPrediction"] = "https://raw.github.com/honda7/BoL/master/Common/VPrediction.lua",
		["SOW"] = "https://raw.github.com/honda7/BoL/master/Common/SOW.lua",
		["SourceLib"] = "https://raw.github.com/TheRealSource/public/master/common/SourceLib.lua",

}



local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0
local SELF_NAME = GetCurrentEnv() and GetCurrentEnv().FILE_NAME or ""



function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<b>[Lee Sin]: Required libraries downloaded successfully, please reload (double F9).</b>")
	end
end





for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end


if DOWNLOADING_LIBS then return end
--End auto downloading LIBS

require "VPrediction"
require "SourceLib"
require "SOW"
require "Collision"
		local collision
col = Collision(1200, 2000, 0.2, 100)
newcol = Collision(1200, 1500, 0.1, 100)

local VP = nil
local efarm = false
local efarmHK = 85
local Assistant
local enemyTable = GetEnemyHeroes()
local informationTable = {}
local spellExpired = true
local qrange = 975
local qwidth = 75
local wrange = 700
local erange = 425
local rrange = 375
local qRange, qDelay, qSpeed, qWidth = 1050, 0.25, 1400, 90
local ignite = nil
local useSight, lastWard, targetObj, friendlyObj = nil, nil, nil, nil
local BRKSlot, DFGSlot, HXGSlot, BWCSlot, TMTSlot, RAHSlot, RNDSlot, YGBSlot = nil, nil, nil, nil, nil, nil, nil, nil
local QREADY, WREADY, EREADY, RREADY, IREADY = false, false, false, false, false
local waittxt = {}
local wDelay = 0
local lastJump = 0
local allyMinions = {}
local lastTime, lastTimeQ, bonusDmg = 0, 0, 0
local wardTarget
local jumpReady = false
local qDmgs = {50, 80, 110, 140, 170}

function OnLoad()
   ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 975 ,DAMAGE_PHYSICAL)
   ts.name = "LeeSin"
   VP = VPrediction()
   SOWi = SOW(VP)
   LoadMenu()
   Ignite()
   PrintFloatText(myHero,11,"LETS DO PLAYS >:D !")
   EnemyMinions = minionManager(MINION_ENEMY, 1050, myHero, MINION_SORT_HEALTH_ASC)
	 allyMinions = minionManager(MINION_ALLY, 1050, myHero, MINION_SORT_HEALTH_DES)
end

function OnTick()
     local target = GetTarget()
        if target ~= nil then
                if string.find(target.type, "Hero") and target.team ~= myHero.team then
                        targetObj = target
                elseif target.team == myHero.team then
                        friendlyObj = target
                end
        end
    GlobalInfos()
    ts:update()
    HarassKey = Config.harass.harassKey
    if HarassKey then Harass() end
    tstarget = ts.target
    if ValidTarget(tstarget) and tstarget.type == "obj_AI_Hero" then
        Target = tstarget
    else
        Target = nil
    end
		if ts.target then
    if Config.ComboS.Fight then Fight() end
		if Config.farm.eFarm then eFarm() end
		end
		if myHero.dead then
                return
        end
       
        local SIGHTlot = GetInventorySlotItem(2049)
        local SIGHTREADY = (SIGHTlot ~= nil and myHero:CanUseSpell(SIGHTlot) == READY)
        local SIGHTlot2 = GetInventorySlotItem(2045)
        local SIGHTREADY2 = (SIGHTlot2 ~= nil and myHero:CanUseSpell(SIGHTlot2) == READY)
        local SIGHTlot3 = GetInventorySlotItem(3340)
        local SIGHTREADY3 = (SIGHTlot3 ~= nil and myHero:CanUseSpell(SIGHTlot3) == READY)
        local SIGHTlot4 = GetInventorySlotItem(2044)
        local SIGHTREADY4 = (SIGHTlot4 ~= nil and myHero:CanUseSpell(SIGHTlot4) == READY)
        local SIGHTlot5 = GetInventorySlotItem(3361)
        local SIGHTREADY5 = (SIGHTlot5 ~= nil and myHero:CanUseSpell(SIGHTlot5) == READY)
        local SIGHTlot6 = GetInventorySlotItem(3362)
        local SIGHTREADY6 = (SIGHTlot6 ~= nil and myHero:CanUseSpell(SIGHTlot6) == READY)
        local SIGHTlot7 = GetInventorySlotItem(3154)
        local SIGHTREADY7 = (SIGHTlot7 ~= nil and myHero:CanUseSpell(SIGHTlot7) == READY)
        local SIGHTlot8 = GetInventorySlotItem(3160)
        local SIGHTREADY8 = (SIGHTlot8 ~= nil and myHero:CanUseSpell(SIGHTlot8) == READY)
       
        useSight = nil
        if SIGHTREADY then
                useSight = SIGHTlot
        elseif SIGHTREADY2 then
                useSight = SIGHTlot2
        elseif SIGHTREADY7 then
                useSight = SIGHTlot7
        elseif SIGHTREADY8 then
                useSight = SIGHTlot8
        elseif SIGHTREADY3 then
                useSight = SIGHTlot3
        elseif SIGHTREADY5 then
                useSight = SIGHTlot5
        elseif SIGHTREADY6 then
                useSight = SIGHTlot6
        elseif SIGHTREADY4 then
                useSight = SIGHTlot4
        end
				if Config.misc.wardjump then
                wardjump()
                return
								end
				if Config.ComboS.Insec then
        if Insec() then return end
        end
       
        if Config.ComboS.Insec then
                local insec = nil
                if Config.ComboS.Insec then insec = targetObj end
                combo(insec)
                return
        end
				
				bonusDmg = myHero.addDamage * 0.90
       
        local target = GetTarget()
        if target ~= nil then
                if string.find(target.type, "Hero") and target.team ~= myHero.team then
                        targetObj = target
                elseif target.team == myHero.team then
                        friendlyObj = target
                end
        end
		
end


function LoadMenu()
     Config = scriptConfig("DJ Lee Sean by Lucas and Pyr", "Die")
     
     Config:addSubMenu("Lee Sean - Combo Settings", "ComboS")          
        Config.ComboS:addParam("Fight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
				Config.ComboS:addSubMenu("Disable Ult On", "disable")
				for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                if enemy.team ~= myHero.team then
                        Config.ComboS.disable:addParam("focus"..enemy.charName, "Ult on "..enemy.charName, SCRIPT_PARAM_ONOFF, true)
                end
        end
				Config.ComboS:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
				Config.ComboS:addParam("useR", "Use R when Killable", SCRIPT_PARAM_ONOFF, true)
				Config.ComboS:addParam("antigapcloser", "Ult GapClosing Enemies", SCRIPT_PARAM_ONOFF, false)
				Config.ComboS:addParam("Insec", "Insec Him", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
   
     Config:addSubMenu("Lee Sean - Harass Settings", "harass")
        Config.harass:addParam("harassKey", "Harass Key (T)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
        Config.harass:permaShow("harassKey")

    Config:addSubMenu("Lee Sean - Farm Settings", "farm")
        Config.farm:addParam("efarm", "EFarm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
   
    Config:addSubMenu("Lee Sean - Ignite Settings", "lignite")    
        Config.lignite:addParam("igniteOptions", "Ignite Options", SCRIPT_PARAM_LIST, 2, { "Don't use", "Burst"})
        Config.lignite:permaShow("igniteOptions")
        Config.lignite:addParam("autoIgnite", "Ks Ignite", SCRIPT_PARAM_ONOFF, true)

    Config:addSubMenu("Lee Sean - Misc Settings", "misc")
        Config.misc:addParam("wardjump", "Ward Jump", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	Config.misc:addParam("wardjumpmax", "Ward Jump on max range if mouse too far", SCRIPT_PARAM_ONOFF, false)			
	
	  Config:addSubMenu("Lee Sean - Draw", "Draw")
		Config.Draw:addParam("drawInsec", "Draw InSec Line", SCRIPT_PARAM_ONOFF, false)

    Config:addSubMenu("Lee Sean - Orbwalking", "Orbwalking")
        SOWi:LoadToMenu(Config.Orbwalking)
       
    Config.ComboS:permaShow("Fight")
    Config:addTS(ts)
end


PrintChat("<font color=\"#FF0000\" >>DJ Lee Sean By Lucas and Pyr v 0.1<</font> ")

function autoIgnite()
        if Config.lignite.autoIgnite then
                if iReady then
                        local ignitedmg = 0
                        for i = 1, heroManager.iCount, 1 do
                                local enemyhero = heroManager:getHero(i)
                                        if ValidTarget(enemyhero,600) then
                                                ignitedmg = 50 + 20 * myHero.level
                                                if enemyhero.health <= ignitedmg then
                                                        CastSpell(ignite, enemyhero)
                                                end
                                        end
                        end
                end
        end
end

function castR(target)
	rPred = nil
	
	for i, target in pairs(GetEnemyHeroes()) do
		CastPosition,  HitChance,  Position = VP:GetLineCastPosition(target, 0.1, 100, 375, 1500, myHero, true)
        if HitChance >= 2 and GetDistance(CastPosition) < 375 then
			local collition = Collision(1200, 2000, 0.2, 100)
			local Enemies = 0
			local maxDistance = myHero + (Vector(rPred) - myHero):normalized()*1000
            local collision, champs = col:GetHeroCollision(myHero, maxDistance, HERO_ENEMY)
			if RREADY and GetDistance(maxDistance, myHero) < 1200 and collision and champs then
				for i, champs in pairs(champs) do
					Enemies = Enemies + 1
				end
				if Enemies >= Config.ultimate.ultCount then CastSpell(_R, target) end
			end
		end
	end
end


function castQ(target)
	for i, target in pairs(GetEnemyHeroes()) do
            CastPosition,  HitChance,  Position = VP:GetLineCastPosition(target, 0.1, 80, qrange, 1500, myHero, true)
            if (HitChance >= 2 or 5) and (GetDistance(CastPosition) < 1050) then
						local collision = Collision(975, 1500, .1, 80)
                    willCollide = collision:GetMinionCollision(CastPosition, myHero)
                    if (ValidTarget(ts.target) and QREADY and (GetDistance(CastPosition, myHero) < 1050) and minionCollisionWidth == 0) then
                        CastSpell(_Q, CastPosition.x, CastPosition.z)
                    end
										if (ValidTarget(ts.target) and QREADY and (GetDistance(CastPosition, myHero) < 975 and 80 > 0) and not willCollide and myHero:GetSpellData(_Q).name == "BlindMonkQOne") then 
											CastSpell(_Q, CastPosition.x, CastPosition.z)
											if myHero:GetSpellData(_Q).name == "blindmonkqtwo" then
											CastSpell(_Q) 
										end
						
			end
    end
end
end

function castQ2(target)
	for i, target in pairs(GetEnemyHeroes()) do
            CastPosition,  HitChance,  Position = VP:GetLineCastPosition(target, 0.1, 80, qrange, 1500, myHero, true)
            if HitChance >= 2 or 5 and (GetDistance(CastPosition) < 1050) then
						local collision = Collision(975, 1500, .1, 80)
                    willCollide = collision:GetMinionCollision(CastPosition, myHero)
                    if QREADY and (GetDistance(CastPosition, myHero) < 1050) and minionCollisionWidth == 0 then
                        CastSpell(_Q, CastPosition.x, CastPosition.z)
                    end
										if QREADY and GetDistance(CastPosition, myHero) < 975 and 80 > 0 and not willCollide and myHero:GetSpellData(_Q).name == "BlindMonkQOne" then 
											CastSpell(_Q, CastPosition.x, CastPosition.z)
										end
						
			end
    end
end


function GlobalInfos()
        QREADY = (myHero:CanUseSpell(_Q) == READY)
        WREADY = (myHero:CanUseSpell(_W) == READY)
        EREADY = (myHero:CanUseSpell(_E) == READY)
        RREADY = (myHero:CanUseSpell(_R) == READY)
        QMana = myHero:GetSpellData(_Q).mana
        WMana = myHero:GetSpellData(_W).mana
        EMana = myHero:GetSpellData(_E).mana
        RMana = myHero:GetSpellData(_R).mana
        MyMana = myHero.mana
       
        TemSlot = GetInventorySlotItem(3153)
        BOTRKREADY = (TemSlot ~= nil and myHero:CanUseSpell(TemSlot) == READY) --Blade Of The Ruined King
       
        TemSlot = GetInventorySlotItem(3144)    
        BCREADY = (TemSlot ~= nil and myHero:CanUseSpell(TemSlot) == READY) --Bilgewater Cutlass
       
        TemSlot = GetInventorySlotItem(3074)
        HYDRAREADY = (TemSlot ~= nil and myHero:CanUseSpell(TemSlot) == READY) --Ravenous Hydra
       
        TemSlot = GetInventorySlotItem(3077)
        TIAMATREADY = (TemSlot ~= nil and myHero:CanUseSpell(TemSlot) == READY) --Tiamat
       
        iReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end

function Ignite()
        if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2
        end
end


function CastItems(target)
        if not ValidTarget(target) then
                return
        else
                if GetDistance(ts.target) <=480 then
                        CastItem(3144, target) --Bilgewater Cutlass
                        CastItem(3153, target) --Blade Of The Ruined King
                end
                if GetDistance(ts.target) <=400 then
                        CastItem(3146, target) --Hextech Gunblade
                end
                if GetDistance(ts.target) <= 350 then
                        CastItem(3184, target) --Entropy
                        CastItem(3143, target) --Randuin's Omen
                        CastItem(3074, target) --Ravenous Hydra
                        CastItem(3131, target) --Sword of the Divine
                        CastItem(3077, target) --Tiamat
                        CastItem(3142, target) --Youmuu's Ghostblade
                end
                if GetDistance(ts.target) <= 1000 then
                        CastItem(3023, target) --Twin Shadows
                end
        end
end


function qtwo()
if myHero:GetSpellData(_Q).name == "blindmonkqtwo" then
CastSpell(_Q)
end
end

function qrqcombo(target)
if RREADY and GetDistance(ts.target) <= rrange then
CastSpell(_R, ts.target)
end
end
function eecombo(target)
 if EREADY and GetDistance(ts.target) <= erange then 
 CastSpell(_E)
	end
	end

function scriptpick()
	if QREADY then castQ()
	end
	end
	
function Fight(target)
	if QREADY then
		castQ(target)
	end
	if myHero:GetSpellData(_Q).name == "blindmonkqtwo" then
		qtwo()
	end
	if ts.target ~= nil then
		CastItems(ts.target)
	end
	if EREADY then
		eecombo()
		
	if Config.ComboS.useW and WREADY and myHero:GetSpellData(_Q).name == "blindmonkqtwo" then
  CastSpell(_W, myHero)
  end
	end
	
	if Config.ComboS.useR and RREADY then
	autoult()
	end
	
end

function Harass()
	castQ2()
	for k, ally in pairs(GetAllyHeroes()) do
		if GetDistance(ally) < wrange and myHero:GetSpellData(_Q).name == "blindmonkqtwo" and myHero:CanUseSpell(_W) == READY then
			qtwo()
			CastSpell(_W, ally)
		end
	end
    for k = 1, objManager.maxObjects do
		local minionObjectI = objManager:GetObject(k)
        if minionObjectI ~= nil and string.find(minionObjectI.name,"Minion_") == 1 and  minionObjectI.team == player.team and minionObjectI.dead == false and myHero:GetDistance(minionObjectI) < 700 and myHero:GetSpellData(_Q).name == "blindmonkqtwo" and myHero:CanUseSpell(_W) == READY then
            qtwo()
			CastSpell(_W, minionObjectI)
        end
    end
       
end

function CountEnemyHeroInRange(range)
 local enemyInRange = 0
 for i = 1, heroManager.iCount, 1 do
  local enemyheros = heroManager:getHero(i)
  if enemyheros.valid and enemyheros.visible and enemyheros.dead == false and enemyheros.team ~= myHero.team and GetDistance(enemyheros) <= range then
   enemyInRange = enemyInRange + 1
  end
 end
 return enemyInRange
end

function efarm()
                local myE = math.floor((player:GetSpellData(_E).level-1)*35 + 60 + player.damage * 1)
    for k = 1, objManager.maxObjects do
        local minionObjectI = objManager:GetObject(k)
                        if minionObjectI ~= nil and string.find(minionObjectI.name,"Minion_") == 1 and minionObjectI.team ~= player.team and minionObjectI.dead == false then
       if  player:GetDistance(minionObjectI) < 375 and minionObjectI.health <= player:CalcMagicDamage(minionObjectI, myE) and myHero:GetSpellData(_E).name == "BlindMonkEOne" then
                                        CastSpell(_E, minionObjectI)
       end
      end
                end
end

function wardjump()
        if myHero:CanUseSpell(_W) == READY and myHero:GetSpellData(_W).name == "BlindMonkWOne" then
                if lastTime > (GetTickCount() - 1000) then
                        if (GetTickCount() - lastTime) >= 10 then
                                CastSpell(_W, lastWard)
                        end
                elseif useSight ~= nil then
                        local wardX = mousePos.x
                        local wardZ = mousePos.z
                        if Config.misc.wardjumpmax then
                                local distanceMouse = GetDistance(myHero, mousePos)
                                if distanceMouse > 600 then
                                        wardX = myHero.x + (600 / distanceMouse) * (mousePos.x - myHero.x)
                                        wardZ = myHero.z + (600 / distanceMouse) * (mousePos.z - myHero.z)
                                end
                        end
                       
                        CastSpell(useSight, wardX, wardZ)
                end
        end
end
 
function OnCreateObj(object)
        if myHero.dead then return end
       
        if Config.misc.wardjump or Config.ComboS.Insec then
                if object ~= nil and object.valid and (object.name == "VisionWard" or object.name == "SightWard") then
                        lastWard = object
                        lastTime = GetTickCount()
                end
        end
end

function autoult()
        if RREADY then
                local rDmg = 0    
                for i = 1, heroManager.iCount, 1 do
                        local enemyhero = heroManager:getHero(i)
                        if ValidTarget(enemyhero, (rrange+50)) then
                                rDmg = getDmg("R", enemyhero, myHero)
                                if enemyhero.health <= rDmg then
                                        CastSpell(_R, enemyhero)
                                end
                        end
                end
        end
end

function OnProcessSpell(unit, spell)
    if not Config.ComboS.antigapcloser then return end
    local jarvanAddition = unit.charName == "JarvanIV" and unit:CanUseSpell(_Q) ~= READY and _R or _Q -- Did not want to break the table below.
    local isAGapcloserUnit = {
--        ['Ahri']        = {true, spell = _R, range = 450,   projSpeed = 2200},
--        ['Aatrox']      = {true, spell = _Q,                  range = 1000,  projSpeed = 1200, },
        ['Akali']       = {true, spell = _R,                  range = 800,   projSpeed = 2200, }, -- Targeted ability
        ['Alistar']     = {true, spell = _W,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
        ['Diana']       = {true, spell = _R,                  range = 825,   projSpeed = 2000, }, -- Targeted ability
        ['Gragas']      = {true, spell = _E,                  range = 600,   projSpeed = 2000, },
--        ['Graves']      = {true, spell = _E,                  range = 425,   projSpeed = 2000, exeption = true },
        ['Hecarim']     = {true, spell = _R,                  range = 1000,  projSpeed = 1200, },
--        ['Irelia']      = {true, spell = _Q,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
        ['JarvanIV']    = {true, spell = jarvanAddition,      range = 770,   projSpeed = 2000, }, -- Skillshot/Targeted ability
--        ['Jax']         = {true, spell = _Q,                  range = 700,   projSpeed = 2000, }, -- Targeted ability
--        ['Jayce']       = {true, spell = 'JayceToTheSkies',   range = 600,   projSpeed = 2000, }, -- Targeted ability
        ['Khazix']      = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
        ['Leblanc']     = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
--        ['LeeSin']      = {true, spell = 'blindmonkqtwo',     range = 1300,  projSpeed = 1800, },
        ['Leona']       = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
        ['Malphite']    = {true, spell = _R,                  range = 1000,  projSpeed = 1500 + unit.ms},
        ['Maokai']      = {true, spell = _Q,                  range = 600,   projSpeed = 1200, }, -- Targeted ability
--        ['MonkeyKing']  = {true, spell = _E,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
--        ['Pantheon']    = {true, spell = _W,                  range = 600,   projSpeed = 2000, }, -- Targeted ability
--        ['Poppy']       = {true, spell = _E,                  range = 525,   projSpeed = 2000, }, -- Targeted ability
        --['Quinn']       = {true, spell = _E,                  range = 725,   projSpeed = 2000, }, -- Targeted ability
--        ['Renekton']    = {true, spell = _E,                  range = 450,   projSpeed = 2000, },
        ['Sejuani']     = {true, spell = _Q,                  range = 650,   projSpeed = 2000, },
--        ['Shen']        = {true, spell = _E,                  range = 575,   projSpeed = 2000, },
        ['Tristana']    = {true, spell = _W,                  range = 900,   projSpeed = 2000, },
--        ['Tryndamere']  = {true, spell = 'Slash',             range = 650,   projSpeed = 1450, },
--       ['XinZhao']     = {true, spell = _E,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
    }
    if unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY and isAGapcloserUnit[unit.charName] and GetDistance(unit) < 2000 and spell ~= nil then
        if spell.name == (type(isAGapcloserUnit[unit.charName].spell) == 'number' and unit:GetSpellData(isAGapcloserUnit[unit.charName].spell).name or isAGapcloserUnit[unit.charName].spell) then
            if spell.target ~= nil and spell.target.name == myHero.name or isAGapcloserUnit[unit.charName].spell == 'blindmonkqtwo' then
--                print('Gapcloser: ',unit.charName, ' Target: ', (spell.target ~= nil and spell.target.name or 'NONE'), " ", spell.name, " ", spell.projectileID)
        CastSpell(_R, unit)
            else
                spellExpired = false
                informationTable = {
                    spellSource = unit,
                    spellCastedTick = GetTickCount(),
                    spellStartPos = Point(spell.startPos.x, spell.startPos.z),
                    spellEndPos = Point(spell.endPos.x, spell.endPos.z),
                    spellRange = isAGapcloserUnit[unit.charName].range,
                    spellSpeed = isAGapcloserUnit[unit.charName].projSpeed,
                    spellIsAnExpetion = isAGapcloserUnit[unit.charName].exeption or false,
                }
            end
        end
    end
    end
function Insec()
        if myHero:CanUseSpell(_R) == READY and friendlyObj ~= nil and targetObj ~= nil and friendlyObj.valid and targetObj.valid and ValidTarget(targetObj) then
                if myHero:GetDistance(targetObj) < 375 then
                        local dPredict = GetDistance(targetObj, myHero)
                       
                        local xE = myHero.x + ((dPredict + 500) / dPredict) * (targetObj.x - myHero.x)
                        local zE = myHero.z + ((dPredict + 500) / dPredict) * (targetObj.z - myHero.z)
                       
                        local position = {}
                        position.x = xE
                        position.z = zE
                       
                        local newDistance = GetDistance(friendlyObj, targetObj) - GetDistance(friendlyObj, position)
                        if newDistance > 0 and (newDistance / 500) > 0.7 then
                                CastSpell(_R, targetObj)
                                return true
                        end
                end
               
                if myHero:CanUseSpell(_W) == READY and myHero:GetSpellData(_W).name == "BlindMonkWOne" then
                        if lastTime > (GetTickCount() - 1000) then
                                if (GetTickCount() - lastTime) >= 10 then
                                        CastSpell(_W, lastWard)
                                        return true
                                end
                        elseif useSight ~= nil then
                                targetObj2 = targetObj
                               
                                local wardDistance = 300
                                local dPredict = GetDistance(targetObj2, friendlyObj)
                                local xE = friendlyObj.x + ((dPredict + wardDistance) / dPredict) * (targetObj2.x - friendlyObj.x)
                                local zE = friendlyObj.z + ((dPredict + wardDistance) / dPredict) * (targetObj2.z - friendlyObj.z)
                               
                                local position = {}
                                position.x = xE
                                position.z = zE
                                if GetDistance(myHero, position) < 600 then
                                        CastSpell(useSight, xE, zE)
                                        return true
                                end
                        end
                end
        end
       
        return false
end
 
function combo(insec)
        local QREADY = (myHero:CanUseSpell(_Q) == READY)
        local WREADY = (myHero:CanUseSpell(_W) == READY)
        local EREADY = (myHero:CanUseSpell(_E) == READY)
        local RREADY = (myHero:CanUseSpell(_R) == READY)
        local focusEnemy = nil
        local minimumHit = -1
        local lowPriority = false
       
        local rangeFocus = 400
        if QREADY then
                rangeFocus = 1000
        end
       
        local insecOk = false
        if insec ~= nil and insec.valid and ValidTarget(insec) then
                focusEnemy = insec
                insecOk = true
        else
                for i=1, heroManager.iCount do
                        local target = heroManager:GetHero(i)
                        if ValidTarget(target, rangeFocus) then
                                local dmg = getDmg("Q", target, myHero)
                                local hits = (target.health / dmg)
                                if minimumHit == -1 or (hits < minimumHit and Config.ComboS.disable["focus"..target.charName]) or hits <= 1.05 or (not lowPriority and minimumHit > 1.05) then
                                        focusEnemy = target
                                        minimumHit = hits
                                        lowPriority = Config.ComboS.disable["focus"..target.charName]
                                end
                        end
                end
        end
       
        if focusEnemy ~= nil then
                if QREADY then
                        if myHero:GetSpellData(_Q).name == "BlindMonkQOne" then
                                local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(focusEnemy, qDelay, qWidth, qRange, qSpeed, myHero, true)
                                if HitChance >= 2 then
                                        CastSpell(_Q, CastPosition.x, CastPosition.z)
                                        return
                                end
                        elseif targetHasQ(focusEnemy) and (myHero:GetDistance(focusEnemy) > 500 or insecOk or (getQDmg(focusEnemy, 0) + getDmg("AD", focusEnemy, myHero)) > focusEnemy.health or (GetTickCount() - lastTimeQ) > 2500) then
                                CastSpell(_Q)
                                return
                        end
                end
               
                if RREADY and Config.ComboS.disable["focus"..focusEnemy.charName] and myHero:GetDistance(focusEnemy) <= 375 then
                        local prociR = getDmg("R", focusEnemy, myHero) / focusEnemy.health
                        local healthLeft = focusEnemy.health - getDmg("R", focusEnemy, myHero)
                       
                        if (prociR > 1 and prociR < 2.5) or (getQDmg(focusEnemy, healthLeft) > healthLeft and targetHasQ(focusEnemy) and QREADY) then
                                CastSpell(_R, focusEnemy)
                                return
                        end
                end
               
                if WREADY and not insecOk then
                        local yourLifePercent = myHero.health / myHero.maxHealth
                        if myHero:GetSpellData(_W).name == "BlindMonkWOne" then
                                if yourLifePercent < 0.4 then
                                        CastSpell(_W, myHero)
                                        return
                                end
                        elseif yourLifePercent < 0.6 then
                                CastSpell(_W)
                                return
                        end
                end
               
        end
				end
 
function targetHasQ(target)
        local dd = false
        for b=1, target.buffCount do
                local buff = target:getBuff(b)
                if buff.valid and (buff.name == "BlindMonkQOne" or buff.name == "blindmonkqonechaos") and (buff.endT - GetGameTimer()) >= 0.3 then
                        dd = true
                        break
                end
        end
       
        return dd
end
 
function getQDmg(target, health)
        local dmg = 0
        local qDMG = 0
        if myHero:CanUseSpell(_Q) == READY then
                local spellQ = myHero:GetSpellData(_Q)
                if spellQ.name == "BlindMonkQOne" then
                        qDMG = qDmgs[spellQ.level] + bonusDmg
                else
                        local dmgHealth = (target.maxHealth - target.health) * 0.08
                        if health > 0 then
                                dmgHealth = (target.maxHealth - health) * 0.08
                        end
                        qDMG = qDmgs[spellQ.level] + bonusDmg + dmgHealth
                end
        end
       
        if qDMG > 0 then
                dmg = myHero:CalcDamage(target, qDMG)
        end
       
        return dmg
end

function DrawLine3Dcustom(x1, y1, z1, x2, y2, z2, width, color)
    local p = WorldToScreen(D3DXVECTOR3(x1, y1, z1))
    local px, py = p.x, p.y
    local c = WorldToScreen(D3DXVECTOR3(x2, y2, z2))
    local cx, cy = c.x, c.y
    DrawLine(cx, cy, px, py, width or 1, color or 4294967295)
end

function OnDraw()
        if RREADY and WREADY then
                if useSight ~= nil then
                        local validTargets = 0
                        if targetObj ~= nil and targetObj.valid and ValidTarget(targetObj) then
                                DrawCircle(targetObj.x, targetObj.y, targetObj.z, 70, 0x00CC00)
                                validTargets = validTargets + 1
                        end
                       
                        if friendlyObj ~= nil and friendlyObj.valid then
                                DrawCircle(friendlyObj.x, friendlyObj.y, friendlyObj.z, 70, 0x00CC00)
                                validTargets = validTargets + 1
                        end
                       
                        if validTargets == 2 and Config.Draw.drawInsec then
                                local dPredict = GetDistance(targetObj, friendlyObj)
                                local rangeR = 300
                                if myHero:GetDistance(targetObj) <= 1100 then
                                        rangeR = 800
                                end
                                local xQ = targetObj.x + (rangeR / dPredict) * (friendlyObj.x - targetObj.x)
                                local zQ = targetObj.z + (rangeR / dPredict) * (friendlyObj.z - targetObj.z)
                               
                                local positiona = {}
                                positiona.x = xQ
                                positiona.z = zQ
                               
                                DrawLine3Dcustom(targetObj.x, targetObj.y, targetObj.z, positiona.x, targetObj.y, positiona.z, 2)
                        end
                end
        end
				end

function enemiesAround(range)
        local playersCount = 0
        for i=1, heroManager.iCount do
                local target = heroManager:GetHero(i)
                if ValidTarget(target, range) then
                        playersCount = playersCount + 1
                end
        end
        return playersCount
end

function OnProcessSpell(unit, spell)
        if unit.name == myHero.name then
                if spell.name == "BlindMonkQOne" then
                        lastTimeQ = GetTickCount()
                end
        end
end