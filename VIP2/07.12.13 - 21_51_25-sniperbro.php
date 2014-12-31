<?php exit() ?>--by sniperbro 69.92.60.30
----------------- Champion Script: Zilean -----------------
----------------- Script Author  : Lonephenom -------------
----------------- 11/28/2013 6:23:39 PM ---------------------
if myHero.charName ~= "Zilean" then return end
local range = 700
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, range, DAMAGE_MAGIC or DAMAGE_PHYSICAL)
local enemyHeroes
allyHeroes = {}
-- spawn


SetupDistance= 850
function OnLoad()
	enemyHeroes = GetEnemyHeroes()
if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	end
	ChronoCheater = scriptConfig("ChronoKeeper","Helping Zilean") -- CONFIG SPIT.
	ChronoCheater:addParam("combo","Combo/TeamFight" , SCRIPT_PARAM_ONKEYDOWN, false, 219) 
	ChronoCheater:addParam("useE", "Use E" ,SCRIPT_PARAM_ONOFF, false)  
	ChronoCheater:addParam("AutoUlti","Auto Ulti" ,SCRIPT_PARAM_ONOFF, false)
	ChronoCheater:addParam("Draw","Q Range" ,SCRIPT_PARAM_ONOFF, false)
	ChronoCheater:addParam("DrawD", "Draw Text" ,SCRIPT_PARAM_ONOFF, false)  
	ChronoCheater:permaShow("combo")
	ChronoCheater:addParam("UltiTeam", "!!Ultimate low NOT FINISHED!!" ,SCRIPT_PARAM_ONOFF, false)
		ChronoCheater:addParam("igniteks", "!!Steal With ignite  NOT FINISHED!!" ,SCRIPT_PARAM_ONOFF, false)  
	ChronoCheater:addParam("UltiHealth", "Ultimate at ?",SCRIPT_PARAM_SLICE, 55, 230, 3000, 5)
	ChronoCheater:addParam("MANA", "Stop using spells on current mana:",SCRIPT_PARAM_SLICE, 15,85, 2000, 15)
	
	ts.name = "Zilean"
	ChronoCheater:addTS(ts)
PrintChat("<font color='#FF00FF'>>> C</font><font color='#FE2EF7'>h</font><font color='#FA58F4'>r</font><font color='#F781F3'>o</font><font color='#F5A9F2'>n</font><font color='#F5A9F2'>o</font><font color='#000000'> by Rmoises</font><font color='#FF00FF'> <<</font>")

end

function AutoIgnite()
	if not IGNITEReady then return end

	for _, enemy in pairs(enemyHeroes) do
		if ValidTarget(enemy, 600) then
			if getDmg("IGNITE", enemy, myHero) >= enemy.health then
				CastSpell(IGNITESlot, enemy)
			end
		end
	end
end

function OnTick()
	ts:update()
	Target = ts.target
		enemy = Target
	DoUlti()
	combo()
	CoolDown()
--AutoIgnite()
end
	
	





function combo()
	if not enemy then enemy = Target end
	if not myHero.dead and ValidTarget(enemy, range) then -- AM I FUCKING DEAD CHECK
			if ChronoCheater.combo and QReady  and myHero.mana > ChronoCheater.MANA then -- TOGGLED AND CAN US
		--	- distance n health check
				
	if VIP_USER then
			Packet("S_CAST", {spellId = _Q, targetNetworkId = enemy.networkID}):send()
			else
			CastSpell(_Q, enemy) -- CAST SPELL
	end
			end
			if EReady and M > ChronoCheater.MANA and ChronoCheater.combo and ChronoCheater.useE then 
	if VIP_USER then
			Packet("S_CAST", {spellId = _E, targetNetworkId = enemy.networkID}):send()
			else
			CastSpell(_E, enemy) -- CAST SPELL
	end
		end
	end
		if not QReady and WReady and myHero.mana > ChronoCheater.MANA and ChronoCheater.combo then 
			if VIP_USER then
			Packet("S_CAST", {spellId = _W, targetNetworkId = enemy.networkID}):send()
			else
			CastSpell(_W, enemy) -- CAST SPELL
	end
end
end



function DoUlti()
	
		if ChronoCheater.AutoUlti and RReady and H < ChronoCheater.UltiHealth and not myHero.dead then
		CastSpell(_R, myHero)
	end
end

function CoolDown()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
	IGNITEReady = (IGNITESlot ~= nil and myHero:CanUseSpell(IGNITESlot) == READY)
	H = myHero.health 
	M = myHero.mana
end


function OnDraw()

if ChronoCheater.Draw and not myHero.dead then
	DrawCircle(myHero.x, myHero.y, myHero.z, range,  0x00FFFF) -- RANGE CIRCLE
end
if ChronoCheater.DrawD and not myHero.dead then
		for i=1, heroManager.iCount do
			local Unit = heroManager:GetHero(i)
			if ValidTarget(Unit,3533) then
			
                       local qDmg = getDmg("Q", Unit, myHero)
                       local iDmg = getDmg("IGNITE",Unit,myHero)
                     if Unit.health  < qDmg then
                             	PrintFloatText(Unit, 0, "1 Bomb")
														
														else if Unit.health  < qDmg * 2 then
															 PrintFloatText(Unit, 0, "2 Bombs")
			
				else if Unit.health  < qDmg * 2  + iDmg then
															 PrintFloatText(Unit, 0, "2 Bombs + IGNITE")
														
												
															else if Unit.health  <  qDmg * 5 + iDmg  then
															 PrintFloatText(Unit, 0, "Poke em some more.")
														
												
			
end
end
end
end
end
end
end
end