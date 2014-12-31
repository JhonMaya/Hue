<?php exit() ?>--by ragequit 174.53.87.155
--[[ The Wonderful Riven ]]


--[[ The Wonderful Riven ]]
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer

local Names = {
 "Sida",
 "mrsithsquirrel",
 "ragequit",
 "iRes",
 "MrSkii",
 "marcosd",
 "WTayllor",
 "420yoloswag",
 "pyrophenix",
 "dragonne",
 "xxgowxx",
 "kriksi",
 "serpicos",
 "Clamity",
 "xtony211",
 "Mercurial4991",
 "khicon",
 "igotcslol",
 "xthanhz",
 "Lienniar",
 "420yoloswag",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if not f then return end
if f then PrintChat("Welcome Alpha Testers") end


 if myHero.charName ~= "Riven" then return end

class 'Plugin'

local SkillQ 
local SkillW 
local SkillE 
local SkillR 
local SkillRC
local Target
local QCount = 0
local NextQ = 0
local ShouldCancel = false
local DoQFix = false
local RetreatPos = nil
local DoCombo, DoHarass = false, false
local HasUlt = false
local REndsAt = 0
local BonusD = false
local ComboSelectorActive = true
local HarassSelectorActive = true
local PassiveStacks = 0

minRange = 100
displayRange = 1000
rotateMultiplier = 3 
rivencanq = true
--local needult = false
--local combos1 = true
--local combos2 = false
--local combos3 = false

local QRange, WRange, ERange = 260, 250, 325

pouncePosition = {
	{
		pA = {x = 6393.7299804688, y = -63.87451171875, z = 8341.7451171875},
		pB = {x = 6612.1625976563, y = 56.018413543701, z = 8574.7412109375}
	},
	{
		pA = {x = 7041.7885742188, y = 0, z = 8810.1787109375},
		pB = {x = 7296.0341796875, y = 55.610824584961, z = 9056.4638671875}
	},
	{
		pA = {x = 4546.0258789063, y = 54.257415771484, z = 2548.966796875},
		pB = {x = 4185.0786132813, y = 109.35539245605, z = 2526.5520019531}
	},
	{
		pA = {x = 2805.4074707031, y = 55.182941436768, z = 6140.130859375},
		pB = {x = 2614.3215332031, y = 60.193073272705, z = 5816.9438476563}
	},
	{
		pA =  {x = 6696.486328125, y = 61.310482025146, z = 5377.4013671875},
		pB = {x = 6868.6918945313, y = 55.616455078125, z = 5698.1455078125}
	},
	{
		pA =  {x = 1677.9854736328, y = 54.923847198486, z = 8319.9345703125},
		pB = {x = 1270.2786865234, y = 50.334892272949, z = 8286.544921875}
	},
	{
		pA =  {x = 2809.3254394531, y = -58.759708404541, z = 10178.6328125},
		pB = {x = 2553.8962402344, y = 53.364395141602, z = 9974.4677734375}
	},
	{
		pA =  {x = 5102.642578125, y = -62.845260620117, z = 10322.375976563},
		pB = {x = 5483, y = 54.5009765625, z = 10427}
	},
	{
		pA =  {x = 6000.2373046875, y = 39.544124603271, z = 11763.544921875},
		pB = {x = 6056.666015625, y = 54.385917663574, z = 11388.752929688}
	},
	{
		pA =  {x = 1742.34375, y = 53.561042785645, z = 7647.1557617188},
		pB = {x = 1884.5321044922, y = 54.930736541748, z = 7995.1459960938}
	},
	{
		pA =  {x = 3319.087890625, y = 55.027889251709, z = 7472.4760742188},
		pB = {x = 3388.0522460938, y = 54.486026763916, z = 7101.2568359375}
	},
	{
		pA =  {x = 3989.9423828125, y = 51.94282913208, z = 7929.3422851563},
		pB = {x = 3671.623046875, y = 53.906265258789, z = 7723.146484375}
	},
	{
		pA =  {x = 4936.8452148438, y = -63.064865112305, z = 10547.737304688},
		pB = {x = 5156.7397460938, y = 52.951190948486, z = 10853.216796875}
	},
	{
		pA =  {x = 5028.1235351563, y = -63.082695007324, z = 10115.602539063},
		pB = {x = 5423, y = 55.15357208252, z = 10127}
	},
	{
		pA =  {x = 6035.4819335938, y = 53.918266296387, z = 10973.666015625},
		pB = {x = 6385.4013671875, y = 54.63500213623, z = 10827.455078125}
	},
	{
		pA =  {x = 4747.0625, y = 41.584358215332, z = 11866.421875},
		pB = {x = 4743.23046875, y = 51.196254730225, z = 11505.842773438}
	},
	{
		pA =  {x = 6749.4487304688, y = 44.903495788574, z = 12980.83984375},
		pB = {x = 6701.4965820313, y = 52.563804626465, z = 12610.278320313}
	},
	{
		pA =  {x = 3114.1865234375, y = -42.718975067139, z = 9420.5078125},
		pB = {x = 2757, y = 53.77322769165, z = 9255}
	},
	{
		pA =  {x = 2786.8354492188, y = 53.645294189453, z = 9547.8935546875},
		pB = {x = 3002.0930175781, y = -53.198081970215, z = 9854.39453125}
	},
	{
		pA =  {x = 3803.9470214844, y = 53.730079650879, z = 7197.9018554688},
		pB = {x = 3664.1088867188, y = 54.18229675293, z = 7543.572265625}
	},
	{
		pA =  {x = 2340.0886230469, y = 60.165466308594, z = 6387.072265625},
		pB = {x = 2695.6096191406, y = 54.339839935303, z = 6374.0634765625}
	},
	{
		pA =  {x = 3249.791015625, y = 55.605854034424, z = 6446.986328125},
		pB = {x = 3157.4558105469, y = 54.080295562744, z = 6791.4458007813}
	},
	{
		pA =  {x = 3823.6242675781, y = 55.420352935791, z = 5923.9130859375},
		pB = {x = 3584.2561035156, y = 55.6123046875, z = 6215.4931640625}
	},
	{
		pA =  {x = 5796.4809570313, y = 51.673671722412, z = 5060.4116210938},
		pB = {x = 5730.3081054688, y = 54.921173095703, z = 5430.1635742188}
	},
	{
		pA =  {x = 6007.3481445313, y = 51.673641204834, z = 4985.3803710938},
		pB = {x = 6388.783203125, y = 51.673400878906, z = 4987}
	},
	{
		pA =  {x = 7040.9892578125, y = 57.192108154297, z = 3964.6728515625},
		pB = {x = 6668.0073242188, y = 51.671356201172, z = 3993.609375}
	},	
	{
		pA =  {x = 7763.541015625, y = 54.872283935547, z = 3294.3481445313},
		pB = {x = 7629.421875, y = 56.908012390137, z = 3648.0581054688}
	},
	{
		pA =  {x = 4705.830078125, y = -62.586814880371, z = 9440.6572265625},
		pB = {x = 4779.9809570313, y = -63.09009552002, z = 9809.9091796875}
	},
	{
		pA =  {x = 4056.7907714844, y = -63.152275085449, z = 10216.12109375},
		pB = {x = 3680.1550292969, y = -63.701038360596, z = 10182.296875}
	},
	{
		pA =  {x = 4470.0883789063, y = 41.59789276123, z = 12000.479492188},
		pB = {x = 4232.9799804688, y = 49.295585632324, z = 11706.015625}
	},
	{
		pA =  {x = 5415.5708007813, y = 40.682685852051, z = 12640.216796875},
		pB = {x = 5564.4409179688, y = 41.373748779297, z = 12985.860351563}
	},
	{
		pA =  {x = 6053.779296875, y = 40.587882995605, z = 12567.381835938},
		pB = {x = 6045.4555664063, y = 41.211364746094, z = 12942.313476563}
	},
	{
		pA =  {x = 4454.66015625, y = 42.799690246582, z = 8057.1313476563},
		pB = {x = 4577.8681640625, y = 53.31339263916, z = 7699.3686523438}
	},
	{
		pA =  {x = 7754.7700195313, y = 52.890430450439, z = 10449.736328125},
		pB = {x = 8096.2885742188, y = 53.66955947876, z = 10288.80078125}
	},
	{
		pA =  {x = 7625.3139648438, y = 55.008113861084, z = 9465.7001953125},
		pB = {x = 7995.986328125, y = 53.530490875244, z = 9398.1982421875}
	},
	{
		pA =  {x = 9767, y = 53.044532775879, z = 8839},
		pB = {x = 9653.1220703125, y = 53.697280883789, z = 9174.7626953125}
	},
	{
		pA =  {x = 10775.653320313, y = 55.35241317749, z = 7612.6943359375},
		pB = {x = 10665.490234375, y = 65.222145080566, z = 7956.310546875}
	},
	{
		pA =  {x = 10398.484375, y = 66.200691223145, z = 8257.8642578125},
		pB = {x = 10176.104492188, y = 64.849853515625, z = 8544.984375}
	},
	{
		pA =  {x = 11198.071289063, y = 67.641044616699, z = 8440.4638671875},
		pB = {x = 11531.436523438, y = 53.454048156738, z = 8611.0087890625}
	},
	{
		pA =  {x = 11686.700195313, y = 55.458232879639, z = 8055.9624023438},
		pB = {x = 11314.19140625, y = 58.438243865967, z = 8005.4946289063}
	},
	{
		pA =  {x = 10707.119140625, y = 55.350387573242, z = 7335.1752929688},
		pB = {x = 10693, y = 54.870254516602, z = 6943}
	},
	{
		pA =  {x = 10395.380859375, y = 54.869094848633, z = 6938.5009765625},
		pB = {x = 10454.955078125, y = 55.308219909668, z = 7316.7041015625}
	},
	{
		pA =  {x = 10358.5859375, y = 54.86909866333, z = 6677.1704101563},
		pB = {x = 10070.067382813, y = 55.294486999512, z = 6434.0815429688}
	},
	{
		pA =  {x = 11161.98828125, y = 53.730766296387, z = 5070.447265625},
		pB = {x = 10783, y = -63.57177734375, z = 4965}
	},
	{
		pA =  {x = 11167.081054688, y = -62.898971557617, z = 4613.9829101563},
		pB = {x = 11501, y = 54.571090698242, z = 4823}
	},
	{
		pA =  {x = 11743.823242188, y = 52.005855560303, z = 4387.4672851563},
		pB = {x = 11379, y = -61.565242767334, z = 4239}
	},
	{
		pA =  {x = 10388.120117188, y = -63.61775970459, z = 4267.1796875},
		pB = {x = 10033.036132813, y = -60.332069396973, z = 4147.1669921875}
	},
	{
		pA =  {x = 8964.7607421875, y = -63.284225463867, z = 4214.3833007813},
		pB = {x = 8569, y = 55.544258117676, z = 4241}
	},
	{
		pA =  {x = 5554.8657226563, y = 51.680099487305, z = 4346.75390625},
		pB = {x = 5414.0634765625, y = 51.611679077148, z = 4695.6860351563}
	},
	{
		pA =  {x = 7311.3393554688, y = 54.153884887695, z = 10553.6015625},
		pB = {x = 6938.5209960938, y = 54.441242218018, z = 10535.8515625}
	},
	{
		pA =  {x = 7669.353515625, y = -64.488967895508, z = 5960.5717773438},
		pB =  {x = 7441.2182617188, y = 54.347793579102, z = 5761.8989257813}
	},
	{
		pA =  {x = 7949.65625, y = 54.276401519775, z = 2647.0490722656},
		pB = {x = 7863.0063476563, y = 55.178623199463, z = 3013.7814941406}
	},
	{
		pA =  {x = 8698.263671875, y = 57.178703308105, z = 3783.1169433594},
		pB = {x = 9041, y = -63.242683410645, z = 3975}
	},
	{
		pA =  {x = 9063, y = 68.192077636719, z = 3401},
		pB = {x = 9275.0751953125, y = -63.257461547852, z = 3712.8935546875}
	},
	{
		pA =  {x = 12064.340820313, y = 54.830627441406, z = 6424.11328125},
		pB = {x = 12267.9375, y = 54.83561706543, z = 6742.9453125}
	},
	{
		pA =  {x = 12797.838867188, y = 58.281986236572, z = 5814.9653320313},
		pB = {x = 12422.740234375, y = 54.815074920654, z = 5860.931640625}
	},
	{
		pA =  {x = 11913.165039063, y = 54.050819396973, z = 5373.34375},
		pB = {x = 11569.1953125, y = 57.787326812744, z = 5211.7143554688}
	},	{
		pA =  {x = 9237.3603515625, y = 67.796775817871, z = 2522.8937988281},
		pB = {x = 9344.2041015625, y = 65.500213623047, z = 2884.958984375}
	},
	{
		pA =  {x = 7324.2783203125, y = 52.594970703125, z = 1461.2199707031},
		pB = {x = 7357.3852539063, y = 54.282878875732, z = 1837.4309082031}
	}


	
}

closest = minRange+1
startPoint = {}
endPoint = {}
directionVector = {}
directionPos = {}
lastUsedStart = {}
lastUsedEnd = {}

busy = false
rivenCanJump = false

function Plugin:__init() -- This function is pretty much the same as OnLoad, so you can do your load stuff in here
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 260, "Broken Wings", AutoCarry.SPELL_SELF_ATMOUSE, 0, false, false) -- register muh skrillz
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 250, "Ki Burst", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillE = AutoCarry.Skills:NewSkill(false, _E, 325, "Valor", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Blade of the Exile", AutoCarry.SPELL_SELF, 0, false, false)
	SkillRC = AutoCarry.Skills:NewSkill(false, _R, 900, "Wind Slash", AutoCarry.SPELL_CONE, 0, false, false, 1.5, 1130, 30, false)

	AutoCarry.Plugins:RegisterOnAttacked(OnAttacked)
	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	AutoCarry.Crosshair:SetSkillCrosshairRange(900)
	
	PrintChat("<font color='#00adec'> >>The Wonderful Riven LOADED!!<</font>")
	PrintChat("<font color='#00adee'> >>Combo 2/3 and Harass2/3 Disabed for now do not use<</font>")
	PrintChat("<font color='#00addd'> >>Quick Swap Disabled For now until new combos and harasses are unlocked<</font>")
	PrintChat("<font color='#00addd'> >>Anti over kill disabled until damage lib update<</font>")
end

function Plugin:OnLoad()
end	

function OnLoad()
Menu.Combo = true
Menu.Combo2 = false
Menu.Combo3 = false
Menu.Harass = true
Menu.Harass2 = false
Menu.Harass3 = false
end


function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()
	if AutoCarry.Keys.AutoCarry then DoCombo = true else DoCombo = false end
	if AutoCarry.Keys.MixedMode then DoHarass = true else DoHarass = false end
	if AutoCarry.Keys.LastHit then DoCombo = false DoHarass = false end
	if AutoCarry.Keys.LaneClear then DoCombo = false DoHarass = false end
	antimultiharass()
	antimulticombo()
	debugger()
	if Menu4.DEV then 
		PrintChat("<font color='#00adec'> >>YOUR NOT A DEV GO AWAY!!!<</font>")
		Menu4.DEV = false
	 end
	if Menu2.WallJump then 
		PrintChat("<font color='#dd3000'> >>Wall Jump disabled for now<</font>") 
		Menu2.WallJump = false
	end
	if Menu2.AntiOK then 
		PrintChat("<font color='#dd3000'> >>Anti Over Kill disabled for now<</font>")
		Menu2.AntiOK = false
	 end
	if Menu2.OKMode then 
		PrintChat("<font color='#dd3000'> >>OVERKILL Mode disabled for now<</font>")
		Menu2.OKMode = false
	 end




	--if Target then
		--if Menu4.Dev then AutoCarry.Helper:Debug("Range: "..GetDistance(Target)) end
	--end




	if Menu2.Select then 
		RotateSelectedCombo()
	else
		ComboSelectorActive = true
	end

	if Menu2.SelectH then 
		RotateSelectedHarass()
	else
		HarassSelectorActive = true
	end

 if Menu2.Run then Escape() end
	if Target and Menu2.AutoKS then Killsteal() end
	--[[ do hax ]]
	if Target and Menu2.AutoKS then
		if HasUlt and GetTickCount() > REndsAt then
			SkillRC:ForceCast(Target)
		end
	end	
	--[[hax end]]	
	--[[harass 1]]
		if Target and DoHarass and Menu.Harass then
			Harass(Target)
		end
	--[[harass 1]]
		if Target and DoHarass and Menu.Harass2 then
			Harass2(Target)
		end
			if Target and DoHarass and Menu.Harass3 then
			HarassNoC(Target)
		end
	--[[combo 1]]
		if Target and DoCombo and Menu.Combo then
			Combo(Target)
		end
	--[[combo 1]]
		if Target and DoCombo and Menu.Combo2 then
			Combo2(Target)
		end
			if Target and DoCombo and Menu.ComboNoC then
			ComboNoC(Target)
		end
	--[[farm]]

if Menu2.FarmQx and AutoCarry.Keys.LastHit then
	spellfarm()
	end
if Menu2.FarmQw and AutoCarry.Keys.LaneClear then
	waveclearfarm()
	end
end

--[[ Combo ]]

function Combo(Target) -- ult is needed combo
	if not HasUlt and GetDistance(Target) <= ERange then
		CastSpell(_R)
	end

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		CastSpell(_E, Target.x, Target.z)
	end
	if not ShouldCancel and QCount == 0 and not SkillQ:IsReady() then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end

end

function Combo2(Target) -- combo without ult to do if ult is not needed
		if not HasUlt and GetDistance(Target) <= ERange then
		CastSpell(_R)
	end

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		CastSpell(_E, Target.x, Target.z)
	end
	if not ShouldCancel and QCount == 0 and not SkillQ:IsReady() then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end

end	

function ComboNoC(Target)
	if Target then
	if not HasUlt and GetDistance(Target) <= ERange then
		CastSpell(_R)
		if getDistance(Target) <= 325 then CastSpell(_E, Target.x, Target.z) end
	end
		if myHero:CanUseSpell(_W) == READY and GetDistance(target) < 250 then
			CastSpell(_W)
		end
		if qCount > 0 and Target and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and GetDistance(target) < 260  then
			CastSpell(_Q, target.x, target.z)
		end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end
end
end	


--[[ Harass ]]

function Harass(Target)
	if not RetreatPos then
		RetreatPos = GetClickPos(Target)
	end
	if SkillW:IsReady() then
		AutoCarry.MyHero:AttacksEnabled(false)
	else
		AutoCarry.MyHero:AttacksEnabled(true)
	end
	if not ShouldCancel and QCount == 0 then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end	
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end
	if not SkillQ:IsReady() and not SkillW:IsReady() and QCount == 0 and NextQ - GetTickCount() < 2000 then
		if RetreatPos then
			if GetDistance(Target) <= 125 then
			CastSpell(_E, RetreatPos.x, RetreatPos.z)
			end
		end
	end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end
end

function Harass2(Target)

	if not RetreatPos then
		RetreatPos = GetClickPos(Target)
	end
	if SkillW:IsReady() then
		AutoCarry.MyHero:AttacksEnabled(false)
	else
		AutoCarry.MyHero:AttacksEnabled(true)
	end

	if not ShouldCancel and QCount == 0 then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end
	if not SkillQ:IsReady() and not SkillW:IsReady() and QCount == 0 and NextQ - GetTickCount() < 2000 then
		if RetreatPos then
			CastSpell(_E, RetreatPos.x, RetreatPos.z)
		end
	end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end
end

function HarassNoC(Target)
	if ValidTarget(Target) then
			if myHero:CanUseSpell(_W) == READY and GetDistance(Target) < 250 then
				CastSpell(_W)
			end
			if myHero:CanUseSpell(_Q) == READY and GetDistance(Target) < 260 then
			CastSpell(_Q, Target.x, Target.z)	
		end
		CastSpell(_E, Target.x, Target.z)
	end
	if Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target) then
		CastSpell(_Q, Target.x, Target.z)
	end
end

function Killsteal()
	if myHero:CanUseSpell(_R) == READY then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) < 870 and enemy.health < getDmg("R", enemy, myHero) then
				if HasUlt then
					CastSpell(_R, enemy.x, enemy.z)
				else
					CastSpell(_R)
				end
			end
		end
	end
end

function AntiOverkill()  -- do anti overkill calcs here
	--calc if true then local need ult == true
end	

function GetEDashPos(Target)
	MyPos = Vector(myHero.x, myHero.y, myHero.z)
	MousePos = Vector(Target.x, Target.y, Target.z)
	return MyPos - (MyPos - MousePos):normalized() * ERange
end

--[[ Farm? ]]

function spellfarm() -- hmm q farms maybes
if AutoCarry.Minions.KillableMinion and AutoCarry.Orbwalker:AttackReady() then
    	local BlacklistMinion = AutoCarry.Minions.KillableMinion
  	end
  		if not AutoCarry.Orbwalker:AttackReady() and (myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_E) == READY) then
    		for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
      		if Minion ~= BlacklistMinion then
        		if GetDistance(Minion) < 260 then
          				if myHero:CanUseSpell(_Q) == READY and Menu.FarmQ then
           	 				if Minion.health <= getDmg("Q", Minion, myHero) then
              					CastSpell(_Q, Minion.x, Minion.z)
              			end		
            		end
          		end
  			end
		end	
	end
end	

function waveclearfarm()
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if GetDistance(Minion) < 260 then
	if myHero:CanUseSpell(_Q) == READY and Menu2.FarmQw then
	CastSpell(_Q, Minion.x, Minion.z)
	end
		end
	end	
end	

--[[ Dynamic Q Range ]]
function getQRadius(overrideStacks)
	local _QCount = (overrideStacks and overrideStacks or QCount)

	if not HasUlt then
		if _QCount == 0 or _QCount == 1 or _QCount == 3 then 
			return 112.5
		elseif _QCount == 2 then
			return 150
		end
	else
		if _QCount == 0 or _QCount == 1 or _QCount == 3 then 
			return 162.5
		elseif _QCount == 2 then
			return 200
		end
	end
end

function TargetInQRange(Target, Pos)
	local Position = Pos and Pos or myHero
	return GetDistance(Target, Position) <= 260 + getQRadius()
end

function FindEPos(Target)
	MyVector = Vector(myHero.x, myHero.y, myHero.z)
	TargetVector = Vector(Pos.x, Pos.y, Pos.z)

	if GetDistance(Target) < 650 then
		CastPoint = TargetVector - (TargetVector - MyVector):normalized() * 325
	end
end		

--[[ Exploit Q thingy ]]

--[[ Menu etc ]]

function Plugin:OnAnimation(Unit, Animation)
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 	
		if Unit.isMe and Animation:find("Spell1a") then 
			QCount = 1
			ShouldCancel = true
			DoQFix = false
		elseif Unit.isMe and Animation:find("Spell1b") then 
			QCount = 2
			ShouldCancel = true
			DoQFix = false
		elseif Unit.isMe and Animation:find("Spell1c") then 
			QCount = 3
			ShouldCancel = true
			DoQFix = false
		elseif Unit.isMe and Animation:find("Run") or Animation:find("Idle1") and ShouldCancel then
			ShouldCancel = false
			AutoCarry.MyHero:MovementEnabled(true)
			AutoCarry.MyHero:AttacksEnabled(true)
			AutoCarry.Orbwalker:ResetAttackTimer()
		end
	end
end	

function Plugin:OnProcessSpell(Unit, Spell)
	if Unit.isMe and Spell.name == "RivenFeint" then
		RetreatPos = nil
	end
end

function DoAnimationCancel(Target)
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 	
		if QCount > 0 then
			AutoCarry.MyHero:MovementEnabled(false)
			AutoCarry.MyHero:AttacksEnabled(false)
			local movePos = GetClickPos(Target)
			if movePos then
			myHero:MoveTo(movePos.x, movePos.z)
			end
		end
	end	
end	

function OnAttacked()
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 
		if Target and GetTickCount() > NextQ and SkillQ:IsReady() then 
			DoQFix = true
			CastSpell(_Q, Target.x, Target.z)
			NextQ = AutoCarry.Orbwalker:GetNextAttackTime()
		end
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		IncreaseRanges()
		HasUlt = true
		REndsAt = GetTickCount() + 14000
	end
   if Unit.isMe and Buff.name == "rivenpassiveaaboost" then BonusD = true end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "RivenTriCleave" then
		QCount = 0
	elseif Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		DecreaseRanges()
		HasUlt = false
	end
  if Unit.isMe and Buff.name == "rivenpassiveaaboost" then 
  	BonusD = false 
  	PassiveStacks = 0
  end
end

function OnUpdateBuff(Unit, Buff) 
	if (Unit == myHero and Buff.name == "RivenTriCleave" and Buff.stack == 2) then
		rivencanq = true
		rivenCanJump = true
	elseif Unit.isMe and Buff.name == "rivenpassiveaaboost" then
		PassiveStacks = Buff.stack
	end
end

function GetClickPos(Target)
	if Target then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
		MousePos = Vector(Target.x, Target.y, Target.z)
		return MyPos - (MyPos - MousePos):normalized() * -200
	end
end

function Plugin:OnDraw()
 if Menu.Combo and Menu3.Draw2 then DrawText("Combo 1 Active *SUGGESTED*",16,100, 100, 0xFF00FF00) end
 if Menu.Combo2 and Menu3.Draw2 then DrawText("Combo 2 Active",16,100, 100, 0xFF00FF00) end
 if Menu.Combo3 and Menu3.Draw2 then DrawText("Combo 3 Non-Animation Cancel Active",16,100, 100, 0xFF00FF00) end
 if Menu.Harass and Menu3.Draw1 then DrawText("Harass 1 Active *SUGGESTED*",16,100, 120, 0xFF00FF00) end
 if Menu.Harass2 and Menu3.Draw1 then DrawText("Harass 2 Active",16,100, 120, 0xFF00FF00) end
 if Menu.Harass3 and Menu3.Draw1 then DrawText("Harass 3 Non-Animation Cancel Active",16,100, 120, 0xFF00FF00) end
 if Menu3.Draw3 then DrawText("Swap Combo Key is T",16,100, 140, 0xffffff00)	end
 if Menu3.Draw4 then DrawText("Swap Harass Key is T",16,100, 160, 0xffffff00)	end
end


function IncreaseRanges()
	WRange = 260
end

function DecreaseRanges()
	WRange = 250
end

function Escape()
	local Distance = GetDistance(mousePos)
	local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
	local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
	local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)
	myHero:MoveTo(MoveX, MoveZ)
	CastSpell(_Q, MoveX, MoveZ)
	CastSpell(_E, MoveX, MoveZ)
end	

function antimulticombo()
if Menu.Combo then
Menu.Combo2 = false
Menu.Combo3 = false
end
if Menu.Combo2 then
Menu.Combo = false
Menu.Combo3 = false
end	
if Menu.Combo3 then
Menu.Combo = false
Menu.Combo2 = false
end		
end

function antimultiharass()
if Menu.Harass then
Menu.Harass2 = false
Menu.Harass3 = false
end
if Menu.Harass2 then
Menu.Harass = false
Menu.Harass3 = false
end	
if Menu.Harass3 then
Menu.Harass = false
Menu.Harass2 = false
end
end

function debugger()
--blank		
end

function RotateSelectedCombo()
	if ComboSelectorActive then
		if Menu.Combo then
			SetActiveCombo(2)
		elseif Menu.Combo2 then
			SetActiveCombo(3)
		elseif Menu.Combo3 then
			SetActiveCombo(1)
		end
		ComboSelectorActive = false
	end
end

function SetActiveCombo(selection)
	Menu.Combo = (selection == 1 and true or false)
	Menu.Combo2 = (selection == 2 and true or false)
	Menu.Combo3 = (selection == 3 and true or false)
end

function RotateSelectedHarass()
	if HarassSelectorActive then
		if Menu.Harass then
			SetActiveHarass(2)
		elseif Menu.Harass2 then
			SetActiveHarass(3)
		elseif Menu.Harass3 then
			SetActiveHarass(1)
		end
		HarassSelectorActive = false
	end
end

function SetActiveHarass(selection)
	Menu.Harass = (selection == 1 and true or false)
	Menu.Harass2 = (selection == 2 and true or false)
	Menu.Harass3 = (selection == 3 and true or false)
end


function buffdamage(Minion, HasUlt)
	local damage = 0
	if myHero.level == 1 or myHero.level == 2 then damage = 1.20 end
	if myHero.level == 3 or myHero.level == 4 or myHero.level == 5 then damage = 1.25 end
	if myHero.level == 6 or myHero.level == 7 or myHero.level == 8 then damage = 1.30 end
	if myHero.level == 9 or myHero.level == 10 or myHero.level == 11 then damage = 1.35 end
	if myHero.level == 12 or myHero.level == 13 or myHero.level == 14 then damage = 1.40 end
	if myHero.level == 15 or myHero.level == 16 or myHero.level == 17 then damage = 1.45 end
	if myHero.level == 18 then damage = 1.50 end
	if BonusD == true then
		local Damage = getDmg("AD", Minion, myHero)
		if HasUlt then
			Damage = Damage * 1.2
		end
		return Damage * damage - Damage
	end
	return 0
end

--expirimental 

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Riven")
AutoCarry.Plugins:RegisterBonusLastHitDamage(buffdamage)
Menu:addParam("sep", "-- AutoCarry Combos--", SCRIPT_PARAM_INFO, "")
Menu:addParam("Combo", "Combo one In Autocarry", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Combo2", "Combo 2 in Autocarry", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("Combo3", "Non-Animation Cancel Combo 3", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("sep", "-- Choose only 1 of the above combos or errors!--", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu:addParam("sep", "-- Mixmode Harasses--", SCRIPT_PARAM_INFO, "")
Menu:addParam("Harass", "Harass one in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Harass2", "Harass 2 in MixedMode", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("Harass3", "Non-Animation Cancel Harass 3", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("sep", "-- Choose only 1 of the above harasses or errors!--", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu2 = scriptConfig("The Wonderful Riven Misc Functions", "Nyanmisc")

Menu2:addParam("sep", "-- Farming --", SCRIPT_PARAM_INFO, "")
Menu2:addParam("FarmQx", "Farm With Q in LastHit", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("FarmQw", "Farm With Q in WaveClear", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu2:addParam("sep", "-- Escape --", SCRIPT_PARAM_INFO, "")
Menu2:addParam("Run", "Escape Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
Menu2:addParam("WallJump", "WallJump in Escape", SCRIPT_PARAM_ONOFF, false)
Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu2:addParam("sep", "-- Misc Functions --", SCRIPT_PARAM_INFO, "")
Menu2:addParam("AutoKS", "Auto KS with R", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("OKMode", "OVERKILL Mode", SCRIPT_PARAM_ONOFF, false)
Menu2:addParam("AntiOK", "Use AntiOverkill", SCRIPT_PARAM_ONOFF, false)
Menu2:addParam("Chase", "Chase With Q", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("Select", "Swap Combos", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
Menu2:addParam("SelectH", "Swap Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu3 = scriptConfig("The Wonderful Riven Draw Functions", "Nyandraw")

Menu3:addParam("sep", "-- Draw Functions --", SCRIPT_PARAM_INFO, "")
Menu3:addParam("Draw1", "Draw Current Harass", SCRIPT_PARAM_ONOFF, true)
Menu3:addParam("Draw2", "Draw Current Combo", SCRIPT_PARAM_ONOFF, true)
Menu3:addParam("Draw3", "Draw Quick Combo Switch Key", SCRIPT_PARAM_ONOFF, true)
Menu3:addParam("Draw4", "Draw Quick Harass Switch Key", SCRIPT_PARAM_ONOFF, true)

Menu4 = scriptConfig("The Wonderful Riven Dev Functions", "Nyandev")

Menu4:addParam("sep", "-- DEV Functions --", SCRIPT_PARAM_INFO, "")
Menu4:addParam("DEV", "Draw DEV Functions", SCRIPT_PARAM_ONOFF, false)






class 'ComboGenerator'

function ComboGenerator:__init()
	self.Enemies = AutoCarry.Helper.EnemyTable
	self.Combo = {}
end

function ComboGenerator:PerformCombo(Target)
	if #Combo > 0 then -- We have some spells to cast
		local Spell = Combo[1]

		if Spell == _Q then
			AutoCarry.Helper:Debug("Q")
		elseif Spell == _W then
			AutoCarry.Helper:Debug("W")
		elseif Spell == _E then
			AutoCarry.Helper:Debug("E")
		elseif Spell == _R then
			AutoCarry.Helper:Debug("R")
		end

		if Spell == _Q and QStacks == 3 then
			table.remove(Combo, 1)
		elseif Spell ~= _Q then
			table.remove(Combo, 1)
		end
	end
end

function GetCombo(Target)
	if Target.health < self:Q(Target) then
		Combo = {_Q}
	elseif Target.health < self:W(Target) then
		Combo = {_W}
	elseif Target.health < self:QW(Target) then
		Combo = {_Q, _W}
	elseif Target.health < self:QE(Target) then
		Combo = {_E, _Q}
	elseif Target.health < self:WE(Target) then
		Combo = {_E, _W}
	elseif Target.health < self:QWE(Target) then
		Combo = {_E, _Q, _W}
	elseif Target.health < self:GetRDamage(Target) + self:QR(Target) then
		Combo = {_R, _Q}
	elseif Target.health < self:GetRDamage(Target) + self:W(Target) then
		Combo = {_R, _W}
	elseif Target.health < self:GetRDamage(Target) + self:W(Target) + self:QR(Target) then
		Combo = {_R, _Q, _W}
	elseif Target.health < self:GetRDamage(Target) + self:QER(Target) then
		Combo = {_R, _E, _Q}
	elseif Target.health < self:GetRDamage(Target) + self:WE(Target) then
		Combo = {_R, _E, _W}
	elseif Target.health < self:GetRDamage(Target) + self:QWER(Target) then
		Combo = {_R, _E, _Q, W}
	end
end

function ComboGenerator:Q(Target)
	return self:GetQDamage(Target)
end

function ComboGenerator:W(Target)
	local WDamage = 0

	if myHero:CanUseSpell(_W) == READY and GetDistance(Target) < 250 then
		WDamage = getDmg("W", Target, myHero)
	end

	return WDamage
end

function ComboGenerator:QW(Target)
	local QDamage, WDamage = 0, 0

	QDamage = self:GetQDamage(Target)
	WDamage = (QDamage > 0 and self:GetWDamage(Target) or 0)

	return QDamage + WDamage
end

function ComboGenerator:QE(Target)
	local QDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos)

	return QDamage
end

function ComboGenerator:WE(Target)
	local EPos = nil
	local WDamage = 0

	EPos = FindEPos(Target)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return WDamage
end

function ComboGenerator:QWE(Target)
	local QDamage = 0
	local WDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return QDamage + WDamage
end

function ComboGenerator:QR(Target)
	return self:GetQDamage(Target, nil, true)
end

function ComboGenerator:QER(Target)
	local QDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos, true)

	return QDamage
end

function ComboGenerator:QWER(Target)
	local QDamage = 0
	local WDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos, true)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return QDamage + WDamage
end

function ComboGenerator:GetWDamage(Target, Pos)
	local Position = Pos and Pos or myHero

	if myHero:CanUseSpell(_W) == READY and GetDistance(Target, Position) < 250 then
		WDamage = getDmg("W", Target, myHero)
	end
end

function ComboGenerator:GetQDamage(Target, Pos, HasUlt)
	local Position = Pos and Pos or myHero
	local QDamage = 0

	if myHero:CanUseSpell(_Q) == READY or QStacks > 0 then
		if QStacks == 0 then
			if TargetInQRange(Target, Position) then																	-- Target is in range for 1st Q
				QDamage = getDmg("Q", Target, myHero) * 3
				QDamage = QDamage + (self:GetAADamage(Target, true, HasUlt) * 2)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(1) then 									-- Target is in range for 2nd Q
				QDamage = getDmg("Q", Target, myHero) * 2
				QDamage = QDamage + self:GetAADamage(Target, true, HasUlt)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(1) + getQRadius(2) then					-- Target is in range for 3rd Q
				QDamage = getDmg("Q", Target, myHero)
			end
		elseif QStacks == 1 then
			if TargetInQRange(Target, Position) then
				QDamage = getDmg("Q", Target, myHero) * 2
				QDamage = QDamage + self:GetAADamage(Target, true, HasUlt)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(2) then
				QDamage = getDmg("Q", Target, myHero)
			end 
		elseif QStacks == 2 then
			if TargetInQRange(Target, Position) then
				QDamage = getDmg("Q", Target, myHero)
			end
		end
	end
	return QDamage
end

function ComboGenerator:GetAADamage(Target, WillGainStack, HasUlt)
	local PassiveDamage = 0
	local MyDamage = 0
	if PassiveStacks > 0 or WillGainStack then
		if HasUlt then
			PassiveDamage = buffdamage(Target, true)
			MyDamage = getDmg("AA", Target, myHero) * 1.2
		else
			PassiveDamage = buffdamage(Target)
			MyDamage = getDmg("AA", Target, myHero)
		end
	end

	local TotalDamage = MyDamage + PassiveDamage

	return getDmg("AA", Target, myHero)
end

function ComboGenerator:GetRDamage(Target)
	return getDmg("R", Target, myHero)
end



class 'WallJumper'

function WallJumper:OnTick()
				MyAccurateDelay()
		if (Menu2.WallJump and busy == false and myHero:CanUseSpell(SpellToCast()) == READY and specialCondition()) then
				closest = minRange+1
				for i,group in pairs(pouncePosition) do
					if (GetDistance(group.pA) < closest or GetDistance(group.pB) < closest ) then
						busy = true
						if (GetDistance(group.pA) < GetDistance(group.pB)) then
							closest = GetDistance(group.pA)
							startPoint = group.pA
							endPoint = group.pB
						else
							closest = GetDistance(group.pB)
							startPoint = group.pB
							endPoint = group.pA
						 end
					end 
				end
				if (busy == true) then
					directionVector = Vector(startPoint):__sub(endPoint)
					myHero:HoldPosition()
					Packet('S_MOVE', {x = startPoint.x, y = startPoint.z}):send()
					delay = 0.19
					MyDelayAction(changeDirection1,delay)
				
				end
		end
end		

function WallJumper:jumpNeedsRotate() 
	if myHero.charName == "Riven" then
		return true
	else
		return false
	end
end

function WallJumper:oppositeCast()
	return false
end
function WallJumper:SpellToCast()
	if myHero.charName == "Riven" then
		return _Q
	end
	
end

function WallJumper:changeDirection1()
	if (jumpNeedsRotate()) then
		myHero:HoldPosition()
		Packet('S_MOVE', {x = startPoint.x+directionVector.x/rotateMultiplier, y = startPoint.z+directionVector.z/rotateMultiplier}):send()

		directionPos = Vector(startPoint)
		directionPos.x = startPoint.x+directionVector.x/rotateMultiplier
		directionPos.y = startPoint.y+directionVector.y/rotateMultiplier
		directionPos.z = startPoint.z+directionVector.z/rotateMultiplier
		
		delay = 0.06
		MyDelayAction(changeDirection2,delay)
	else 
			CastJump()
	end
end

function WallJumper:changeDirection2()
   
	Packet('S_MOVE', {x = startPoint.x, y = startPoint.z}):send()
	delay = 0.070
	MyDelayAction(CastJump,delay)
end

function WallJumper:CastJump()
	if (oppositeCast()) then
		CastSpell(SpellToCast(),startPoint.x+directionVector.x, startPoint.z+directionVector.z)
	else
		CastSpell(SpellToCast(),endPoint.x, endPoint.z)
	end
	myHero:HoldPosition()
	MyDelayAction(freeFunction,1)
end

function WallJumper:freeFunction()
	busy = false
end


functionDelay = 0;
functionToExecute = nil

function WallJumper:MyAccurateDelay()
		if (functionToExecute ~= nil and (functionDelay <= os.clock())) then
			functionDelay = nil
			functionToExecute()
			if (functionDelay == nil) then
				functionToExecute = nil
			end
		end
end

function WallJumper:MyDelayAction(b,a)
	functionDelay = a+os.clock()
	functionToExecute = b
end

function WallJumper:specialCondition()
	if (myHero.charName == "Riven") then
			return rivenCanJump;
	end		
end

function WallJumper:OnDraw()
				if (Menu2.WallJump and specialCondition()) then
				for i,group in pairs(pouncePosition) do
					
					if (GetDistance(group.pA) < displayRange or GetDistance(group.pB) < displayRange) then
						DrawCircle(group.pA.x, group.pA.y, group.pA.z, minRange, 0x00FF00)
						DrawCircle(group.pB.x, group.pB.y, group.pB.z, minRange, 0x00FF00)
					end
				end	
		end		
end			