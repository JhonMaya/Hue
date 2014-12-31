<?php exit() ?>--by Jus 201.27.125.129
if myHero.charName ~= "Tristana" then Print("<font color=\"#FF0000\"><b>You Need Tristana to Play.</b></font>") return end

----requeriments-----------
require "Prodiction"
--require "VPrediction"
---------------------------

-------variables-----------
local Target 					=	nil
local menu 						=	nil
local Ts 						=	nil
local tp 						=	nil
local vp 						=	nil
local myPlayer					=	GetMyHero()
local enemyHeroes 				= 	GetEnemyHeroes()
---------------------------

-------random stuff--------
local lastAttack 				= 	0
local lastWindUpTime			=	0
local lastAttackCD	 			=	0
local CanOrb					= 	false
local wDamage					=	0
local eDamage					=	0
local rDamage   				=   0
---------------------------

------------Interrupt Table-------------
local InterruptList = {
    { charName = "Caitlyn", 		spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks",	spellName = "Crowstorm"},
    { charName = "FiddleSticks", 	spellName = "DrainChannel"},
    { charName = "Galio", 			spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", 		spellName = "FallenOne"},
    { charName = "Katarina", 		spellName = "KatarinaR"},
    { charName = "Lucian", 			spellName = "LucianR"},
    { charName = "Malzahar", 		spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", 	spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", 			spellName = "AbsoluteZero"},
    { charName = "Pantheon", 		spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", 			spellName = "ShenStandUnited"},
    { charName = "Urgot", 			spellName = "UrgotSwap2"},
    { charName = "Varus", 			spellName = "VarusQ"},
    { charName = "Warwick", 		spellName = "InfiniteDuress"}
}
ToInterrupt = {}
------------------------------------------

-------------Gap Closer Table-------------

local gapCloseList = {
		['Ashe']		= {true, spell = "EnchantedCrystalArrow"},
		['Annie']		= {true, spell = 'InfernalGuardian'		},
        ['Ahri']        = {true, spell = 'AhriTumble'			},
        ['Aatrox']      = {true, spell = 'AatroxQ'				},
        ['Akali']       = {true, spell = 'AkaliShadowDance'		}, 
        ['Alistar']     = {true, spell = 'Headbutt'				},
        ['Cassiopeia']	= {true, spell = 'CassiopeiaMiasma'		},
        ['Diana']       = {true, spell = 'DianaTeleport'		},
        ['Ezreal']		= {true, spell = 'EzrealTruehotBarrage' },
        ['Gragas']      = {true, spell = 'GragasE'				},
        ['Graves']      = {true, spell = 'GravesMove'			},
        ['Hecarim']     = {true, spell = 'HecarimUlt'			},
        ['Irelia']      = {true, spell = 'IreliaGatotsu'		},
        ['JarvanIV']    = {true, spell = 'JarvanIVDragonStrike'	},
        ['Jax']         = {true, spell = 'JaxLeapStrike'		}, 
        ['Jayce']       = {true, spell = 'JayceToTheSkies'		}, 		
        ['Katarina']	= {true, spell = 'KatarinaE'			},
        ['Khazix']      = {true, spell = 'KhazixW'				},
        ['KogMaw']      = {true, spell = 'KogMawVoidOoze'       },
        ['Leblanc']     = {true, spell = 'LeblancSlide'			},
        ['LeeSin']      = {true, spell = 'blindmonkqtwo'		},
        ['Leona']       = {true, spell = 'LeonaZenithBlade'		},
        -- ['Lucian']		= {true, spell = 'LucianQ'				},
        -- ['Lucian']		= {true, spell = 'LucianW'				},
        -- ['Lucian']		= {true, spell = 'LucianR'				},
        ['Malphite']    = {true, spell = 'UFSlash'				},
        ['Maokai']      = {true, spell = 'MaokaiTrunkLine'		}, 		
        ['MasterYi']	= {true, spell = 'AlphaStrike'			},	    
        ['MonkeyKing']  = {true, spell = 'MonkeyKingNimbus'		},
        ['Morgana']		= {true, spell = 'DarkBindingMissile'   },         
		['Pantheon']    = {true, spell = 'PantheonW'			}, 		
		['Pantheon']    = {true, spell = 'PantheonRJump'		},
        ['Pantheon']    = {true, spell = 'PantheonRFall'		},
        ['Poppy']       = {true, spell = 'PoppyHeroicCharge'	}, 		
        ['Renekton']    = {true, spell = 'RenektonSliceAndDice'	},
        ['Sejuani']     = {true, spell = 'SejuaniArcticAssault'	},
        ['Shen']        = {true, spell = 'ShenShadowDash'		},
        ['Tristana']    = {true, spell = 'RocketJump'			},
        ['Tryndamere']  = {true, spell = 'Slash'				},
        ['XinZhao']     = {true, spell = 'XenZhaoSweep'			} 		
}

-----------------------------------------------

function LoadVars()
	-----Common Variable-------
	qCombo, qHarass 		= 	menu.combo.q, menu.harass.q
	wCombo, wHarass			=	menu.combo.w, menu.harass.w
	wKillCombo, wKc, wBd	=	menu.combo.wKill, menu.combo.wKillw, menu.combo.behinde
	eCombo, eHarass			=	menu.combo.e, menu.harass.e
	rCombo					=	menu.combo.r
	rKillCombo, rWcombo		=	menu.combo.rKill, menu.combo.rW
	ComboMode				=	menu.combo.mode -- 1 ap, 2 ad
	ComboON, HarassON		=	menu.combo.key, menu.harass.key
	LastHitON				=	menu.farm.keyLastHit
	qmH, wmH, emH			=	menu.harass.extra.q, menu.harass.extra.w, menu.harass.extra.e
	wKill, rKill			=	menu.harass.extra.wKill, menu.harass.extra.rKill
	rInterrupt_				=	menu.extra.rInterrupt
	--wTurretProt				=	menu.combo.turret
	rGapClose_				=	menu.extra.rGapClose
	wGapClose_				=	menu.extra.wGapClose
	wGapCloseHealth_		=	menu.extra.wGapCloseHealth
	wGapCloseRange_			=	menu.extra.wGapCloseRange
	wGapCloseRangeWall_		=	menu.extra.wGapCloseRangeWall
	predictionMode 			=	menu.general.predMode	
	dTarget, tTarget		=	menu.draw.target, menu.draw.damagetext
	integration 			=	menu.general.integration
	if VIP_USER then 
		dQuality				=	menu.draw.quality
	end
	--------------KILL STEAL VARIABLES and DAMAGE COMBO-----------------
	eSteal, wSteal, rSteal	=	menu.extra.e, menu.extra.w, menu.extra.r
	wMana 					= 	math.floor(myPlayer:GetSpellData(_W).mana)
	eMana 					= 	math.floor(myPlayer:GetSpellData(_E).mana)
	rMana 					= 	math.floor(myPlayer:GetSpellData(_R).mana)
	wReady 					= 	myPlayer:CanUseSpell(_W) == READY
	eReady 					= 	myPlayer:CanUseSpell(_E) == READY
	rReady 					=	myPlayer:CanUseSpell(_R) == READY
	myMana 					=	math.floor(myPlayer.mana)	
	---------------------------	
end

function LoadMenu()
	menu 	=	scriptConfig("[Tristana by Jus]", "TristanaBME")
	--combo--
	menu:addSubMenu("[Combo System]", "combo")
	menu.combo:addParam("mode", "Tristana Mode:", SCRIPT_PARAM_LIST, 1, { "AP", "AD" } )
	menu.combo:addParam("q", "Use (Q) in combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("w", "Use (W) in combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("e", "Use (E) in combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("r", "Use (R) in combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("", "[Combo Settings]", SCRIPT_PARAM_INFO, "")
	menu.combo:addParam("manaDamage", "Calculate Damage/Mana/CD", SCRIPT_PARAM_ONOFF, false)
	menu.combo:addParam("", "- (W) Settings -", SCRIPT_PARAM_INFO, "")
	menu.combo:addParam("wKill", "Only (W) if # enemys <=", SCRIPT_PARAM_SLICE, 4, 1, 5, 0)
	menu.combo:addParam("wKillw", "Only (W) if killable", SCRIPT_PARAM_ONOFF, false)
	menu.combo:addParam("behinde", "Try (W) Behind Target", SCRIPT_PARAM_ONOFF, false)
	--menu.combo:addParam("turret", "(W) Turret Protected", SCRIPT_PARAM_ONOFF, false)
	menu.combo:addParam("", " - (R) Settings - ", SCRIPT_PARAM_INFO, "")
	menu.combo:addParam("rW", "Only (R) with (W)", SCRIPT_PARAM_ONOFF, false)
	menu.combo:addParam("rKill", "Only (R) if killable", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--extra--
	menu:addSubMenu("[Extra Spells Settings]", "extra")
	menu.extra:addParam("", "- (W) Settings -", SCRIPT_PARAM_INFO, "")
	menu.extra:addParam("wGapClose", "Use (W) to run away spells", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("wGapCloseHealth", "Health to (W) away", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
	menu.extra:addParam("wGapCloseRange", "Range to (W) away", SCRIPT_PARAM_SLICE, 900, 450, 900, 0)
	menu.extra:addParam("wGapCloseRangeWall", "Check (W) position wall", SCRIPT_PARAM_ONOFF, false)
	menu.extra:addParam("", "- (R) Settings -", SCRIPT_PARAM_INFO, "")
	menu.extra:addParam("rInterrupt", "Use (R) to Interrupt", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("rGapClose", "Use (R) to stop Gap Close", SCRIPT_PARAM_ONOFF, false)
	menu.extra:addParam("", "- Kill Steal -", SCRIPT_PARAM_INFO, "")
	menu.extra:addParam("w", "Use (W) Kill Steal", SCRIPT_PARAM_ONOFF, false)
	menu.extra:addParam("e", "Use (E) Kill Steal", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("r", "Use (R) Kill Steal", SCRIPT_PARAM_ONOFF, false)	
	--harass--
	menu:addSubMenu("[Harass System]", "harass")
	menu.harass:addParam("q", "Use (Q) in harass", SCRIPT_PARAM_ONOFF, false)
	menu.harass:addParam("w", "Use (W) in harass", SCRIPT_PARAM_ONOFF, false)
	menu.harass:addParam("e", "Use (E) in harass", SCRIPT_PARAM_ONOFF, true)
	menu.harass:addParam("key", "Harass Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	menu.harass:addSubMenu("[Extra Settings]", "extra")
	menu.harass.extra:addParam("", "[Mana Settings]", SCRIPT_PARAM_INFO, "")
	menu.harass.extra:addParam("q", "Stop (Q) if mana <= %", SCRIPT_PARAM_SLICE, 80, 0, 100, 0)
	menu.harass.extra:addParam("w", "Stop (W) if mana <= %", SCRIPT_PARAM_SLICE, 90, 0, 100, 0)
	menu.harass.extra:addParam("e", "Stop (E) if mana <= %", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
	menu.harass.extra:addParam("", "[Others]", SCRIPT_PARAM_INFO, "")
	menu.harass.extra:addParam("", "-(W) settings", SCRIPT_PARAM_INFO, "")
	menu.harass.extra:addParam("wKill", "Only (W) if killable", SCRIPT_PARAM_ONOFF, true)
	menu.harass.extra:addParam("", "-(R) settings", SCRIPT_PARAM_INFO, "")
	menu.harass.extra:addParam("rKill", "Use (R) if can kill", SCRIPT_PARAM_ONOFF, true)	
	--farm--
	menu:addSubMenu("[Farm System]", "farm")
	menu.farm:addParam("", "[Last Hit]", SCRIPT_PARAM_INFO, "")
	menu.farm:addParam("e", "Calculate (E) range passive", SCRIPT_PARAM_ONOFF, true)
	menu.farm:addParam("stopE", "Stop (E) if mana <= %", SCRIPT_PARAM_SLICE, 80, 0, 100, 0)
	menu.farm:addParam("keyLastHit", "Last Hit Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C")) 
	menu.farm:addParam("", "[Lane Clear]", SCRIPT_PARAM_INFO, "")
	menu.farm:addParam("qClear", "Use (Q) in lane clear", SCRIPT_PARAM_ONOFF, true)
	menu.farm:addParam("wClear", "Use (W) in lane clear", SCRIPT_PARAM_ONOFF, true)
	menu.farm:addParam("eClear", "Use (E) in lane clear", SCRIPT_PARAM_ONOFF, true)
	menu.farm:addParam("laneclearkey", "Lane Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	--draw--
	menu:addSubMenu("[Draw System]", "draw")
	menu.draw:addParam("", "- Draw Ranges -", SCRIPT_PARAM_INFO, "")	
	menu.draw:addParam("w", "Draw (W) Range", SCRIPT_PARAM_ONOFF, false)
	menu.draw:addParam("e", "Draw (E) Range", SCRIPT_PARAM_ONOFF, false)
	menu.draw:addParam("r", "Draw (R) Range", SCRIPT_PARAM_ONOFF, false)
	menu.draw:addParam("aa", "Draw Auto Attack Range", SCRIPT_PARAM_ONOFF, true)
	if VIP_USER then
		menu.draw:addParam("quality", "Draw Quality", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
	end
	menu.draw:addParam("", "- Target Draw -", SCRIPT_PARAM_INFO, "")
	menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
	menu.draw:addParam("damagetext", "Draw Damage Text", SCRIPT_PARAM_ONOFF, true)
	menu.draw:addParam("", "- Minions Draw -", SCRIPT_PARAM_INFO, "")
	menu.draw:addParam("lastHitdraw", "Last Hit Draw", SCRIPT_PARAM_ONOFF, true)
	--menu.draw:addParam("jungledraw", "Jungle Draw", SCRIPT_PARAM_ONOFF, true)
	--system--
	menu:addSubMenu("[General System]", "general")
	if VIP_USER then
		menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 1, { "Prodiction", "Normal", "Vip Prediction" })
	else
		menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 1, { "Prodiction", "Normal" })
	end
	menu.general:addParam("integration", "Use Integration", SCRIPT_PARAM_ONOFF, false)	
end

function ConfigTargetOnLoad()
	if menu.combo.mode == 1 then
		Ts = TargetSelector(TARGET_LESS_CAST, 1200, DAMAGE_MAGIC, false, true)
	elseif menu.combo.mode == 2 then
		Ts = TargetSelector(TARGET_LOW_HP, 1200, DAMAGE_PHYSICAL, false, true)
	elseif menu.combo.mode == nil then
		Ts = TargetSelector(TARGET_LESS_CAST, 1200, DAMAGE_MAGIC, false, true)
	end
end

function ConfigVars()	
	--------interrupt champions table pre-buff------
	for _, enemy in pairs(enemyHeroes) do
        for _, champ in pairs(InterruptList) do
            if enemy.charName == champ.charName then
                table.insert(ToInterrupt, champ.spellName)
            end
        end
    end
end

function LoadEveryThing()
	LoadMenu()	
	ConfigVars()	
	ConfigTargetOnLoad()
	Ts.name = "Tristana"
	menu:addTS(Ts)	
	LoadVars()
	PrintChat("<font color=\"#00F7EC\"><b>Tristana, Boost My Elo by</b></font><font color=\"#FFFFFF\"><b> Jus </b></font><font color=\"#00F7EC\"><b> loaded.</b></font> ")
end

function OnLoad()
	LoadEveryThing()
end

---------------------------------COMBO AND SPELLS CAST--------------------------------------

-----Cast W------

function IsHarass(myTarget)	
	if wKill then
		local wDamage = (getDmg("W", myTarget, myPlayer) or 0)
		return HarassON and myPlayer.mana/myPlayer.maxMana*100 > wmH and wDamage > myTarget.health
	elseif not wKill then
		return HarassON and myPlayer.mana/myPlayer.maxMana*100 > wmH
	end
end 

----VPrediction
-- local function VPredictionCastW(myTarget)
-- 	if wCombo or wHarass then
-- 		if ComboON or (HarassON and myPlayer.mana/myPlayer.maxMana*100 > wmH) then
			-- local function countEnemiesAround(unit)
			-- 	nEnemies = 0
			-- 	for _, enemy in pairs(enemyHeroes) do
			-- 		if not enemy.dead and unit.name ~= enemy.name and unit:GetDistance(enemy) < 800 then
			-- 			nEnemies = nEnemies + 1
			-- 		end
			-- 	end
			-- 	return nEnemies
			-- end
			-- if countEnemiesAround(myTarget) > wKillCombo then return end
-- 			if ComboMode == 1 then
-- 				mainCastPosition, mainHitChance = vp:GetCircularAOECastPosition(myTarget, 0.250, 450, 825, 1150, myPlayer, false)
-- 			elseif ComboMode == 2 then
-- 				mainCastPosition, mainHitChance = vp:GetCircularAOECastPosition(myTarget, 0.250, 450, 555, 1150, myPlayer, false)
-- 			end
-- 			if mainHitChance >= 2 then
-- 				CastSpell(_W, mainCastPosition.x, mainCastPosition.z)
-- 			end
-- 		end
-- 	end
-- end
----VipPrediction (all class)
local function VipPredictionCastW(myTarget)
	if wCombo or wHarass then
		if ComboON or IsHarass(myTarget) then						
			if ComboMode == 1 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end				
				if CountEnemyHeroInRange(825) > wKillCombo then return end
				tp 				= 	TargetPredictionVIP(825, 1150, 0.250, 270, myPlayer)
				Position 		= 	tp:GetPrediction(myTarget)				
				if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					CastSpell(_W, Position.x, Position.z)
				end
			elseif ComboMode == 2 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
				if CountEnemyHeroInRange(555) > wKillCombo then return end
				tp 				= 	TargetPredictionVIP(555, 1150, 0.250, 270, myPlayer)
				Position 		= 	tp:GetPrediction(myTarget)
				if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					CastSpell(_W, Position.x, Position.z)
				end
			end			
		end
	end
end
----Prodiction
local function ProdictionCastW(myTarget)
	if wCombo or wHarass then
		if ComboON or IsHarass(myTarget) then
			--if wKc and (getDmg("W", myTarget, myPlayer) or 0) < myTarget.health then return end							
			if ComboMode == 1 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
				if CountEnemyHeroInRange(825) > wKillCombo then return end
				Position, info = Prodiction.GetPrediction(myTarget, 825, 1150, 0.25, 270, myPlayer)
				if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					CastSpell(_W, Position.x, Position.z)
				end
				--Prodiction.AddCallbackAfterDash(825, 1150, 0.5, myPlayer, CastWDash)				 
			elseif ComboMode == 2 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
				if CountEnemyHeroInRange(555) > wKillCombo then return end
				Position, info = Prodiction.GetPrediction(myTarget, 555, 1150, 0.25, 270, myPlayer)
				if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
					CastSpell(_W, Position.x, Position.z)
				end
				--Prodiction.AddCallbackAfterDash(555, 1150, 0.5, myPlayer, CastWDash)
			end			
		end
	end
end

----Normal

local function NormalCastW(myTarget)
	if wCombo or wHarass then				
		if ComboON or IsHarass(myTarget) then			
			if ComboMode == 1 and GetDistance(myTarget) <= 825 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
				if CountEnemyHeroInRange(825) > wKillCombo then return end
				if wBd then
					local PosBehind = myPlayer + Vector(myTarget.x-myPlayer.x, myPlayer.y, myTarget.z-myPlayer.z):normalized()*(GetDistance(myPlayer, myTarget)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				else
					CastSpell(_W, myTarget.x, myTarget.z)
				end
			elseif ComboMode == 2 and GetDistance(myTarget) <= 555 then
				if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
				if CountEnemyHeroInRange(555) > wKillCombo then return end
				if wBd then
					local PosBehind = myPlayer + Vector(myTarget.x-myPlayer.x, myPlayer.y, myTarget.z-myPlayer.z):normalized()*(GetDistance(myPlayer, myTarget)+15)
					CastSpell(_W, PosBehind.x, PosBehind.z)
				else
					CastSpell(_W, myTarget.x, myTarget.z)
				end
			end
		end
	end
end

-----Cast Q----

local function CastQ(myTarget)
	if qCombo or qHarass then		
		if ComboON or (HarassON and myPlayer.mana/myPlayer.maxMana*100 > qmH) then
			if ComboMode == 1 then
				if GetDistance(myTarget) <= 555 then
					if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
						Packet("S_CAST", {spellId = _Q}):send()
					else
						CastSpell(_Q)
					end
				end
			else
				if GetDistance(myTarget) <= myPlayer.range then
					if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
						Packet("S_CAST", {spellId = _Q}):send()
					else
						CastSpell(_Q)
					end
				end
			end
		end
	end
end

-----Cast E----



local function CastE(myTarget)
	if not myPlayer:CanUseSpell(_E) == READY then return end
	if eCombo or eHarass and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then
		if ComboON or (HarassON and myPlayer.mana/myPlayer.maxMana*100 > emH) then
			if VIP_USER then
				Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_E, myTarget)
			end
		end
	end
end

-----Cast R----

local function CastR(myTarget)
	if ComboON and rCombo and GetDistance(myTarget) <= 640 then
		if rKillCombo and getDmg("R", myTarget, myPlayer) - 5 > myTarget.health then
			if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_R, myTarget)
			end
		elseif rWcombo and myPlayer:CanUseSpell(_W) ~= READY then
			if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_R, myTarget)
			end
		elseif not rKillCombo and not rWcombo then
			if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_R, myTarget)
			end
		end
	elseif HarassON and rKill and GetDistance(myTarget) <= 640 then
		local rDamage	=	getDmg("R", myTarget, myPlayer)
		if rDamage - 5 > myTarget.health then
			if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_R, myTarget)
			end
		end
	end
end

--------------------------------------------------------------------------------------------

--------------------------------ORBWALK----------------------------------------------------

local function getHitBoxRadius(hero_)
    return GetDistance(hero_.minBBox, hero_.maxBBox)/2
end

function AArange(myTarget) -- < myPlayer.range    
    local range = GetDistance(myTarget) - getHitBoxRadius(myTarget) - getHitBoxRadius(myPlayer)
    return range
end

function OrbWalking(myTarget)
	if ValidTarget(myTarget) and GetDistance(myTarget) <= myPlayer.range then
		if TimeToAttack() then
			myHero:Attack(myTarget)
	    elseif heroCanMove() then
	        moveToCursor()
	    end
    else
    	moveToCursor()
    end
end

function TimeToAttack()
    return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end

function moveToCursor()
	if GetDistance(mousePos) > 125 then
		local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end

---------------------------------PROCESS SPELL/ INTERRUPT AND RUN AWAY----------------------

-- function Spell:GetPrediction(target)
--     return self.predictionType == 1 and self:GetVPrediction(target) or self:GetProdiction(target)
-- end

-- function Spell:GetProdiction(target)
--     if self.skillshotType ~= nil then
--         local pos, info = Prodiction.GetPrediction(target, self.range, self.speed, self.delay, self.radius, self.sourcePosition)

--         return pos, info.collision and -1 or info.hitchance
--     end
-- end



function TargetIsValid(myTarget)
    return myTarget.type == "obj_AI_Hero" and myTarget.type ~= "obj_AI_Turret" and myTarget.type ~= "obj_AI_Minion" and not TargetHaveBuff("UndyingRage", myTarget) and not TargetHaveBuff("JudicatorIntervention", myTarget) and myTarget.health > 1
end

function OnProcessSpell(unit, spell)
	if unit.name == "KogMaw" then print(spell.name) end
	if #ToInterrupt > 0 and rInterrupt_ and myPlayer:CanUseSpell(_R) == READY then
        for _, ability in pairs(ToInterrupt) do
            if spell.name == ability and unit.team ~= myHero.team then
                if ValidTarget(unit) and GetDistance(unit) <= 640 then
                	if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
						Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
					else
						CastSpell(_R, myTarget)
					end
                end
            end
        end
    end
    if wGapClose_ and unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY then
		local spellName = spell.name
		if gapCloseList[unit.charName] and spellName == gapCloseList[unit.charName].spell and GetDistance(unit) < 2000 then
			if spell.name ~= nil and spell.target ~= nil and spell.target.name == myPlayer.name then
				local NewPos	=	myPlayer + (Vector(spell.endPos) - myPlayer):normalized()*wGapCloseRange_
				if wGapCloseHealth_ == nil then wGapCloseHealth_ = 100 end
				if myPlayer.health/myPlayer.maxHealth*100 <= wGapCloseHealth_ then
					-- local EnemyPos =	Prodiction.GetTimePrediction(unit, 1) 
					-- (predictionMode == 1 and EnemyPos ~= nil and GeDistance(myPlayer, EnemyPos) <= 150)
					if (wGapCloseRangeWall_ and IsWall(D3DXVECTOR3(NewPos.x, NewPos.y, NewPos.z)) == false) then
						CastSpell(_W, NewPos.x, NewPos.z)
					elseif not wGapCloseRangeWall_ then
						CastSpell(_W, NewPos.x, NewPos.z)
					end
				end
			end
		end		
	end
	if unit.isMe then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end
	end
end

------------------------------------------------------------------------------------------------------

------------------------------CAST COMBO E HARASS-----------------------------------------------------

local function GetDamageWithMana(myTarget)
	if wReady and eReady and rReady then
		if myMana > (wMana + eMana + rMana) and (wDamage + eDamage + rDamage) -1 > myTarget.health then
			return true
		else
			return false
		end
	else
		return false
	end
end

local function GetRangeMode()
	if ComboMode == 1 then
		return 825
	elseif ComboMode == 2 then
		return 555
	elseif ComboMode == nil then
		return 825
	end
end

function Combo(myTarget) --"Prodiction", "VPrediction", "Vip Prediction", "Normal"
	if ValidTarget(myTarget) then
		if menu.combo.manaDamage and not GetDamageWithMana(myTarget) then CastQ(myTarget) return end		
			if predictionMode == 1 then -- "Prodiction"						
				ProdictionCastW(myTarget)			
			elseif predictionMode == 2 then -- "Normal"					
				NormalCastW(myTarget)						
			elseif VIP_USER and predictionMode == 3 then -- "Vip Prediction"				
				VipPredictionCastW(myTarget)			
			elseif predictionMode == nil then			
				NormalCastW(myTarget)
			end
		CastE(myTarget)
		CastR(myTarget)
		CastQ(myTarget)			
	end	
end

function HarassCombo(myTarget)
	if ValidTarget(myTarget) then
		if predictionMode == 1 then -- "Prodiction"			
			ProdictionCastW(myTarget)			
		elseif predictionMode == 2 then -- "Normal"			
			NormalCastW(myTarget)						
		elseif VIP_USER and predictionMode == 3 then -- "Vip Prediction"		
			VipPredictionCastW(myTarget)			
		elseif predictionMode == nil then
			NormalCastW(myTarget)
		end
		CastE(myTarget)
		CastQ(myTarget)				
	end
end
------------------------------------------------------------------------------------------------------

------------------------------KILLSTEAL---------------------------------------------------------------

function CastWAndPred(myTarget)
	if predictionMode == 1 then -- "Prodiction"						
		ProdictionCastW(myTarget)			
	elseif predictionMode == 2 then -- "Normal"					
		NormalCastW(myTarget)						
	elseif VIP_USER and predictionMode == 3 then -- "Vip Prediction"				
		VipPredictionCastW(myTarget)			
	elseif predictionMode == nil then			
		NormalCastW(myTarget)
	end
end

function KillSteal(myTarget)
	if ValidTarget(myTarget) and TargetIsValid(myTarget) then			
		if wReady then 
			wDamage = (getDmg("W", myTarget, myPlayer) or 0)
		end
		if eReady then
			eDamage = (getDmg("E", myTarget, myPlayer) or 0)
		end
		if rReady then
			rDamage = (getDmg("R", myTarget, myPlayer) or 0)
		end
--825(w), 555(e)
	
		if wSteal and wReady and myMana > wMana and wDamage -1 > myTarget.health and GetDistance(myTarget) <= 825 then -- W
			CastWAndPred(myTarget)
		elseif eSteal and eReady and myMana > eMana and eDamage -1 > myTarget.health and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then -- E
			if VIP_USER then
				Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_E, myTarget)
			end
		elseif rSteal and rReady and myMana > rMana and rDamage -1 > myTarget.health and GetDistance(myTarget) <= 640 then -- R
			CastR(myTarget)
		elseif wSteal and eSteal and wReady and eReady and myMana > (wMana + eMana) and (wDamage + eDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then --W+E
			CastWAndPred(myTarget)
			if VIP_USER then
				Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_E, myTarget)
			end			
		elseif eSteal and rSteal and eReady and rReady and myMana > (eMana + rMana) and (eDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then -- E+R
			if VIP_USER then
				Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_E, myTarget)
				CastSpell(_R, myTarget)				
			end
		elseif wSteal and rSteal and wReady and rReady and myMana > (wMana + rMana) and (wDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then -- W+R
			CastWAndPred(myTarget)
			if VIP_USER then
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_R, myTarget)
			end
		elseif wSteal and eSteal and rSteal and wReady and eReady and rReady and myMana > (wMana + eMana + rMana) and (wDamage + eDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then --W+E+R
			CastWAndPred(myTarget)
			if VIP_USER then
				Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
				Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
			else
				CastSpell(_E, myTarget)
				CastSpell(_R, myTarget)				
			end			
		end
	end
end

local minion 	=	minionManager(MINION_ENEMY, myPlayer.range, myPlayer, MINION_SORT_HEALTH_ASC)
local minion_ 	=	nil
local lastHitT 	=	0

function LastHit() ---- (delay+distance/projspeed)
	if GetTickCount() + GetLatency()/2 > lastHitT then
		myPlayer:MoveTo(mousePos.x, mousePos.z)
	end
	minion:update()
	minion_ 	=	minion.objects[1]	
	if ValidTarget(minion_) then
		--for i=1, #minion_ do
			local aaDamage 	= 	(getDmg("AD", minion_, myPlayer) or 0)
			--local LichDmg 	= 	(getDmg("LICHBANE", minion_, myHero) or 0)
			--local SheenDmg 	= 	(getDmg("SHEEN", minion_, myHero) or 0)
			local TotalDmg	=	aaDamage --+ LichDmg + SheenDmg
			if TotalDmg >= minion_.health and GetDistance(minion_) <= myPlayer.range and GetTickCount() + GetLatency()/2 > lastHitT then
				myPlayer:Attack(minion_)
				lastHitT = GetTickCount() + GetLatency()/2 + (1000/myPlayer.attackSpeed)
			-- elseif ValidTarget(Target) and AArange(Target) <= myPlayer.range and GetTickCount() + GetLatency()/2 > lastHitT then
			-- 	myPlayer:Attack(Target)
			-- 	lastHitT = GetTickCount() + GetLatency()/2 + (1000/myPlayer.attackSpeed)
			end
		--end
	end
end

-- function GetBestCircularFarmPosition(range, radius, objects)
--     local BestPos 
--     local BestHit = 0
--     for i, object in ipairs(objects) do
--         local hit = CountObjectsNearPos(object.visionPos or object, radius, objects)
--         if hit > BestHit then
--             BestHit = hit
--             BestPos = Vector(object)
--             if BestHit == #objects then
--                break
--             end
--          end
--     end
--     return BestPos, BestHit
-- end

local function GetmyTarget()	
	if integration then
		if _G.MMA_Loaded or _G.AutoCarry then
			if _G.MMA_Target and _G.MMA_Target.type == myPlayer.type then return _G.MMA_Target end
			if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myPlayer.type then return _G.AutoCarry.Attack_Crosshair.target end
		end
	else
		Ts:update()
		if Ts.target ~= nil and TargetIsValid(Ts.target) then
			return Ts.target
		end
	end
end

function OnTick()
	if myPlayer.dead then return end
	LoadVars()	
	Target 	=	GetmyTarget()
	KillSteal(Target)	
	if ComboON then	Combo(Target) end
	if HarassON then HarassCombo(Target) end
	if LastHitON then LastHit() end
	if ComboON or HarassON then
		if not integration then 
			OrbWalking(Target)
		elseif integration and _G.Evadeee_impossibleToEvade or not _G.Evadeee then
			OrbWalking(Target)
		end
	end	
	if integration then CanOrb = false else CanOrb = true end
end

function OnDraw()
	if myPlayer.dead then return end
	local wD, eD, rD, aaD			=	menu.draw.w, menu.draw.e, menu.draw.r, menu.draw.aa
	if VIP_USER then
		if wD then
			DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, 825, dQuality, ARGB(255, 0, 255, 255))
		end
		if eD then
			DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer:GetSpellData(_E).range, dQuality, ARGB(255, 255, 255, 255))
		end
		if rD then
			DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, 640, dQuality, ARGB(255, 255, 0, 255))
		end
		if aaD then
			DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer.range, dQuality, ARGB(255, 255, 0, 255))
		end
		if dTarget and ValidTarget(Target) and TargetIsValid(Target) then
			DrawCircle3D(Target.x, Target.y, Target.z, 80, 3, ARGB(255, 0, 255, 255))
		end
		if menu.draw.lastHitdraw and ValidTarget(minion_) then
			DrawCircle3D(minion_.x, minion_.y, minion_.z, 80, 1, ARGB(255, 255, 255, 255))
		end
	else
		if wD then			
			DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, 825, ARGB(255, 0, 255, 255))
		end
		if eD then
			DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer:GetSpellData(_E).range, ARGB(255, 255, 255, 255))
		end
		if rD then
			DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, 640, ARGB(255, 255, 0, 255))
		end
		if aaD then
			DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer.range, ARGB(255, 255, 0, 255))
		end
		if dTarget and ValidTarget(Target) and TargetIsValid(Target) then			
			DrawCircle(Target.x, Target.y, Target.z, 80, ARGB(255, 0, 255, 255))
		end
		if menu.draw.lastHitdraw and ValidTarget(minion_) then
			DrawCircle(minion_.x, minion_.y, minion_.z, 80, ARGB(255, 255, 255, 255))
		end
	end	
end