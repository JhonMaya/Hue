<?php exit() ?>--by Incognito 70.78.192.224
if myHero.charName ~= "Velkoz" then return end
  require "Collision"
  require "Prodiction"

  local qRange, wRange, eRange = 1050, 1050, 850
  --(info, range, speed, delay, width) 
  local Prodiction = ProdictManager.GetInstance()
  local qp = Prodiction:AddProdictionObject(_Q, qRange, 750, 0.25, 150)
  local wp = Prodiction:AddProdictionObject(_W, wRange, 750, 0.25, 100)
  local ep = Prodiction:AddProdictionObject(_E, eRange, 1250, 0.75, 200)
  local qCol = Collision(qRange, 1200, 0.25, 150)


  function OnLoad()
   Config = scriptConfig("Vel'Koz","vk")
   Config:addSubMenu("Basic Settings", "Basic")
   Config:addSubMenu("Draw Settings", "Draw")

   --> Basic Settings
   Config.Basic:addParam("useIgnite", "Use Ignite", SCRIPT_PARAM_ONOFF, true)
   Config.Basic:addParam("autoCombo", "Auto Combo", SCRIPT_PARAM_ONOFF, true)
   Config.Basic:addParam("combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
   Config.Basic:addParam("poke", "Poke with E", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
   Config.Basic:permaShow("autoCombo")
   Config.Basic:permaShow("combo")
   Config.Basic:permaShow("poke")
   
   --> Draw Settings
   Config.Draw:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
   Config.Draw:addParam("drawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
   Config.Draw:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
   Config.Draw:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)
   Config.Draw:addParam("calc", "Draw Calculations", SCRIPT_PARAM_ONOFF, true)
   
   ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, eRange, DAMAGE_MAGIC, true)
   Config:addTS(ts)
   
   --ignite = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
  end

  function castQ(target)
   local qPred = qp:GetPrediction(target)
   if qPred and not qCol:GetMinionCollision(qPred) and GetDistance(qPred) <= qRange then 
    CastSpell(_Q, qPred.x, qPred.z) 
   end
  end

  function castW(target)
   local wPred = wp:GetPrediction(target)
   if wPred and GetDistance(wPred) <= wRange then 
    CastSpell(_W, wPred.x, wPred.z) 
   end
  end

  function castE(target)
   local ePred = ep:GetPrediction(target)
   if ePred and GetDistance(ePred) <= eRange then 
    CastSpell(_E, ePred.x, ePred.z)
   end
  end

  function UseItems(target)
   if GetInventorySlotItem(3128) ~= nil then CastSpell(GetInventorySlotItem(3128), target) end
  end


  function Checks()
   QREADY = ((myHero:CanUseSpell(_Q) == READY) or (myHero:GetSpellData(_Q).level > 0 and myHero:GetSpellData(_Q).currentCd <= 0.4)) WREADY = ((myHero:CanUseSpell(_W) == READY) or (myHero:GetSpellData(_W).level > 0 and myHero:GetSpellData(_W).currentCd <= 0.4))
   EREADY = ((myHero:CanUseSpell(_E) == READY) or (myHero:GetSpellData(_E).level > 0 and myHero:GetSpellData(_E).currentCd <= 0.4))
   RREADY = ((myHero:CanUseSpell(_R) == READY) or (myHero:GetSpellData(_R).level > 0 and myHero:GetSpellData(_R).currentCd <= 0.4))
   IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
   DFGREADY = GetInventorySlotItem(3128) ~= nil and myHero:CanUseSpell(GetInventorySlotItem(3128)) == READY
   ts:update()
  end

  function dmgCalc(target)
   if target then
    local pDmg =  25 + (10 * myHero.level)
    local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
    local wDmg = (WREADY and getDmg("W", target, myHero)) or 0
    local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
    local iDmg = (IREADY and GetDistance(target) < 600 and getDmg("IGNITE", target, myHero)) or 0
    local dfgDmg = (GetInventorySlotItem(3128) ~= nil and GetDistance(target) < 750 and getDmg("DFG", target, myHero)) or 0
    local damageAmp = (GetInventorySlotItem(3128) ~= nil and GetDistance(target) < 750 and 1.2) or 1
    local currentDamage = 0
    local spellcount = 0
    
    if QREADY then
     local count = false
     currentDamage = currentDamage + qDmg
     if count == false then
      spellcount = spellcount + 1
      count = true
     end
    end
   
    if WREADY then
     local count = false
     currentDamage = currentDamage + wDmg
     if count == false then
      spellcount = spellcount + 2
      count = true
     end
    end
   
    if EREADY then
     local count = false
     currentDamage = currentDamage +eDmg
     if count == false then
      spellcount = spellcount + 1
      count = true
     end
    end
      
    if DFGREADY then
     currentDamage = (currentDamage * damageAmp) + dfgDmg
    end
    
    if IREADY then
     currentDamage = currentDamage + iDmg
    end
     
    if spellcount >= 3 then
     currentDamage = currentDamage + pDmg
    end
    
    return currentDamage
   end
  end
  
  function doCombo()
   if ts.target then
    if DFGREADY then
     UseItems(ts.target)
    end
    if EREADY then
     castE(ts.target)
    end
    if WREADY then
     castW(ts.target)
    end
    if QREADY then
			if myHero:GetSpellData(_Q).name ~= "velkozqsplitactivate" then
				castQ(ts.target)
			end
    end
    if IREADY and Config.Basic.useIgnite then
     CastSpell(ignite, ts.target)
    end
   end
  end

  function KSCombo()
   if ts.target then
    local pDmg =  25 + (10 * myHero.level)
    local qDmg = (QREADY and getDmg("Q", ts.target, myHero)) or 0
    local wDmg = (WREADY and getDmg("W", ts.target, myHero)) or 0
    local eDmg = (EREADY and getDmg("E", ts.target, myHero)) or 0
    local iDmg = (IREADY and GetDistance(ts.target) < 600 and getDmg("IGNITE", ts.target, myHero)) or 0
    local dfgDmg = (GetInventorySlotItem(3128) ~= nil and GetDistance(ts.target) < 750 and getDmg("DFG", ts.target, myHero)) or 0
    local damageAmp = (GetInventorySlotItem(3128) ~= nil and GetDistance(ts.target) < 750 and 1.2) or 1
    local currentDamage = 0
    local spellcount = 0
    
    if QREADY then
     local count = false
     currentDamage = currentDamage + qDmg
     if count == false then
      spellcount = spellcount + 1
      count = true
     end
    end
    
    if WREADY then
     local count = false
     currentDamage = currentDamage + wDmg
     if count == false then
      spellcount = spellcount + 2
      count = true
     end
    end
    
    if EREADY then
     local count = false
     currentDamage = currentDamage +eDmg
     if count == false then
      spellcount = spellcount + 1
      count = true
     end
    end
    
    if DFGREADY then
     currentDamage = (currentDamage * damageAmp) + dfgDmg
    end
    
    if IREADY then
     currentDamage = currentDamage + iDmg
    end
    
    if spellcount >= 3 then
     currentDamage = currentDamage + pDmg
    end
    
    if currentDamage > ts.target.health then
     if DFGREADY then
      UseItems(ts.target)
     end
     if EREADY then
      castE(ts.target)
     end
     if WREADY then
      castW(ts.target)
     end
     if QREADY then
			if myHero:GetSpellData(_Q).name ~= "velkozqsplitactivate" then
				castQ(ts.target)
			end
     end
     if IREADY then
      CastSpell(ignite, ts.target)
     end
    end
   end
  end

  function OnTick()
  Checks()
	 
   if ts.target then
   if Config.Basic.autoCombo then
    KSCombo()
   end
   if Config.Basic.combo then
    doCombo()
   end
   if Config.Basic.poke then
    if EREADY then 
     castE(ts.target)
    end
   end
  end
  end

  
function OnDraw()
	if Config.Draw.drawQ then
		DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0xFFFF0000)
	end
	if Config.Draw.drawW then
		DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFF0000)
	end
	if Config.Draw.drawE then
		DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0xFFFF0000)
	end
	if Config.Draw.calc then
		for i=1, heroManager.iCount do
			local enemy = heroManager:getHero(i)
			if enemy.team ~= myHero.team and enemy.dead ~= true then
				if dmgCalc(enemy) > enemy.health then
					PrintFloatText(enemy, 0, "Killable")
				elseif dmgCalc(enemy) < enemy.health then
					PrintFloatText(enemy, 0, (""..math.round(enemy.health - dmgCalc(enemy))))
				end
			end
		end
	end
	
end